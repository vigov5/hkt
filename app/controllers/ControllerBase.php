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
        $this->tag->setTitle('Framgia Hyakkaten');
        if (!$this->current_user) {
            $this->getCurrentUser();
        }
        $this->view->current_page = '';
        if (!$this->current_user) {
            $this->view->login_form = new LoginForm();
        }
    }

    protected function getCurrentUser()
    {
        $current_user = $this->auth->getUser();
        $this->current_user = $current_user;
        $this->view->current_user = $current_user;
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

        // Only check permissions on private controllers
        if ($this->acl->isPrivate($controller_name, $action_name)) {

            // Get the current identity
            $identity = $this->auth->getIdentity();

            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {
                $this->flash->notice('You don\'t have access to this module: private');
                $dispatcher->forward(array(
                    'controller' => 'index',
                    'action' => 'notFound',
                ));
                return false;
            }

            // Check if the user have permission to the current option
            if (!$this->acl->isAllowed($identity['role'], $controller_name, $action_name)) {

                $this->flash->error('You don\'t have access to this module: ' . $controller_name . ':' . $action_name);

                $dispatcher->forward(array(
                    'controller' => 'index',
                    'action' => 'notFound',
                ));

                return false;
            }
        }
    }
}
