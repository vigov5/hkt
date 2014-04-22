<?php

use Phalcon\Exception;
use Phalcon\Mvc\User\Component;

/**
 * Class Auth
 * Originally written in Voluro project, by cphalcon
 * @see https://github.com/phalcon/vokuro
 */
class Auth extends Component
{

    /**
     * Checks the user credentials
     *
     * @param array $credentials
     * @throws Exception
     * @return boolan
     */
    public function check($credentials)
    {

        // Check if the user exist
        $user = Users::findByKey($credentials['email']);
        if ($user == false) {
            $this->registerUserThrottling(0);
            throw new Exception('Wrong email/username and password combination');
        }

        // Check the password
        if (!$this->security->checkHash($credentials['password'], $user->password)) {
            $this->registerUserThrottling($user->id);
            throw new Exception('Wrong email/username and password combination');
        }

        // Register the successful login
        $this->saveSuccessLogin($user);

        // Check if the remember me was selected
        if (isset($credentials['remember'])) {
            $this->createRememberEnviroment($user);
        }

        $this->session->set('auth-identity', [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->getRoleValue(),
        ]);
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param Users $user
     * @throws Phalcon\Exception
     */
    public function saveSuccessLogin($user)
    {
        $success_login = new SuccessLogins();
        $success_login->user_id = $user->id;
        $success_login->ip_address = $this->request->getClientAddress();
        $success_login->user_agent = $this->request->getUserAgent();
        if (!$success_login->save()) {
            $messages = $success_login->getMessages();
            throw new Exception($messages[0]);
        }
    }

    /**
     * Implements login throttling
     * Reduces the efectiveness of brute force attacks
     *
     * @param int $userId
     */
    public function registerUserThrottling($userId)
    {
        $failed_login = new FailedLogins();
        $failed_login->user_id = $userId;
        $failed_login->ip_address = $this->request->getClientAddress();
        $failed_login->user_agent = $this->request->getUserAgent();
        $failed_login->save();

        $attempts = FailedLogins::count([
            'ip_address = ?0 AND created_at > ?1',
            'bind' => [
                $this->request->getClientAddress(),
                time() - 3600 * 6
            ]
        ]);

        switch ($attempts) {
            case 1:
            case 2:
                // no delay
                break;
            case 3:
            case 4:
                sleep(2);
                break;
            default:
                sleep(4);
                break;
        }
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param Users $user
     */
    public function createRememberEnviroment($user)
    {
        $userAgent = $this->request->getUserAgent();
        $token = md5($user->email . $user->password . $userAgent);

        $remember = new RememberTokens();
        $remember->user_id = $user->id;
        $remember->token = $token;
        $remember->user_agent = $userAgent;

        if ($remember->save() != false) {
            $expire = time() + 86400 * 8;
            $this->cookies->set('RMU', $user->id, $expire);
            $this->cookies->set('RMT', $token, $expire);
        }
    }

    /**
     * Check if the session has a remember me cookie
     *
     * @return boolean
     */
    public function hasRememberMe()
    {
        return $this->cookies->has('RMU');
    }

    /**
     * Logs on using the information in the coookies
     *
     * @return Phalcon\Http\Response
     */
    public function loginWithRememberMe()
    {
        $user_id = $this->cookies->get('RMU')->getValue();
        $cookie_token = $this->cookies->get('RMT')->getValue();

        $user = Users::findFirstById($user_id);
        if ($user) {

            $userAgent = $this->request->getUserAgent();
            $token = md5($user->email . $user->password . $userAgent);

            if ($cookie_token == $token) {

                $remember = RememberTokens::findFirst([
                    'user_id = ?0 AND token = ?1',
                    'bind' => [
                        $user->id,
                        $token,
                    ]
                ]);
                if ($remember) {

                    // Check if the cookie has not expired
                    if ((time() - (86400 * 8)) < $remember->created_at) {

                        // Register identity
                        $this->session->set('auth-identity', [
                            'id' => $user->id,
                            'username' => $user->username,
                            'email' => $user->email,
                            'role' => $user->getRoleValue(),
                        ]);

                        // Register the successful login
                        $this->saveSuccessLogin($user);

                        return $this->response->redirect('user');
                    }
                }
            }
        }

        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();

        return $this->response->redirect('session/login');
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity()
    {
        return $this->session->get('auth-identity');
    }

    /**
     * Returns the current identity
     *
     * @return string
     */
    public function getName()
    {
        $identity = $this->session->get('auth-identity');
        return $identity['username'];
    }

    /**
     * Removes the user identity information from session
     */
    public function remove()
    {
        if ($this->cookies->has('RMU')) {
            $this->cookies->get('RMU')->delete();
        }
        if ($this->cookies->has('RMT')) {
            $this->cookies->get('RMT')->delete();
        }

        $this->session->remove('auth-identity');
    }

    /**
     * Auths the user by his/her id
     *
     * @param $user
     * @internal param \Users $id
     */
    public function authUser($user)
    {
        $this->session->set('auth-identity', [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->getRoleValue(),
        ]);
    }

    /**
     * Auths the user by his/her id
     *
     * @param int $id
     * @throws Phalcon\Exception
     */
    public function authUserById($id)
    {
        $user = Users::findFirstById($id);
        if ($user == false) {
            throw new Exception('The user does not exist');
        }

        $this->session->set('auth-identity', [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->getRoleValue(),
        ]);
    }

    /**
     * Get the entity related to user in the active identity
     *
     * @throws Phalcon\Exception
     * @return Users
     */
    public function getUser()
    {
        $identity = $this->session->get('auth-identity');
        if (isset($identity['id'])) {
            $user = Users::findFirstById($identity['id']);
            if ($user == false) {
                throw new Exception('The user does not exist');
            }
            return $user;
        }

        return null;
    }
}
