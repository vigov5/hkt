<?php
use Phalcon\Mvc\Dispatcher;

class ControllerBase extends BController
{
    /**
     * @var Users $current_user
     */
    public $current_user = null;

    public function initialize()
    {
        //var_dump($this->getPrevUrl());die();
        $this->tag->setTitle('Framgia Hyakkaten');
        if (!$this->current_user) {
            $this->getCurrentUser();
        }
        $this->view->current_page = '';
        if (!$this->current_user) {
            $this->view->login_form = new LoginForm();
        }
        $this->flashSession();
    }

    /**
     * @return Users $current_user
     */
    protected function getCurrentUser()
    {
        $current_user = $this->auth->getUser();
        $this->current_user = $current_user;
        $this->view->current_user = $current_user;
        return $current_user;
    }

    /**
     * Execute before the router so we can determine if this is a provate controller, and must be authenticated, or a
     * public controller that is open to all.
     *
     * @param Dispatcher $dispatcher
     * @return boolean
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $controller_name = $dispatcher->getControllerName();
        $action_name = $dispatcher->getActionName();

        if ($this->acl->isPublic($controller_name, $action_name)) {
            return true;
        }
        // Get the current identity
        $identity = $this->auth->getIdentity();

        // If there is no identity available the user is redirected to index/index
        if (!is_array($identity)) {
            $this->flash->notice('You don\'t have access to this module: private');
            $dispatcher->forward(array(
                    'controller' => 'index',
                    'action' => 'notFound',
                )
            );

            return false;
        }

        if (!Users::checkAuthorized($identity['role'])) {
            $this->flash->error('Your account is unauthorized. Please contact administrators !');

            $dispatcher->forward(array(
                    'controller' => 'index',
                    'action' => 'notFound',
                )
            );

            return false;
        }

        // Only check permissions on private controllers
        if ($this->acl->isPrivate($controller_name, $action_name)) {

            // Check if the user have permission to the current option
            if (!$this->acl->isAllowed($identity['role'], $controller_name, $action_name)) {

                $this->flash->error('You don\'t have access to this module: ' . $controller_name . ':' . $action_name);

                $dispatcher->forward(array(
                        'controller' => 'index',
                        'action' => 'notFound',
                    )
                );

                return false;
            }
        }
    }

    public function setPrevUrl($url)
    {
        $this->session->set('prev_url', $this->url->get($url));
    }

    public function getPrevUrl()
    {
        return $this->session->get('prev_url');
    }

    public function redirectToPrevUrl()
    {
        $this->response->redirect($this->getPrevUrl());
    }

    public function setFlashSession($type, $message)
    {
        $this->session->set('flash_session', [$type, $message]);
    }

    public function getFlashSession()
    {
        return $this->session->get('flash_session');
    }

    public function removeFlashSession()
    {
        return $this->session->remove('flash_session');
    }

    public function flashSession()
    {
        $flash = $this->getFlashSession();
        if ($flash) {
            $type = $flash[0];
            $message = $flash[1];
            $this->flash->$type($message);
            $this->removeFlashSession();
        }
    }

    public function forwardNotFound()
    {
        return $this->forward('index/notFound');
    }

    public function forwardUnderConstruction()
    {
        return $this->forward('index/underConstruction');
    }
}
