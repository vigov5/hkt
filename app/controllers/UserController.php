<?php

class UserController extends ControllerBase
{

    const USERS_PER_PAGE = 100;
    const LOGS_PER_PAGE = 50;
    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'user';
    }

    public function indexAction($page = 1)
    {
        $page = intval($page);
        if ($page < 1) {
            $page = 1;
        }
        $builder = $this->modelsManager->createBuilder()
            ->from('Users')
            ->orderBy('role desc');

        $paginator = new Phalcon\Paginator\Adapter\QueryBuilder([
            'builder' => $builder,
            'limit' => self::USERS_PER_PAGE,
            'page' => $page
        ]);
        $page = $paginator->getPaginate();

        $this->view->pagination = new Pagination($page, '/user/index');
        $this->view->users = $page->items;
        $this->view->current_page = 'users';
    }

    public function facebookAction()
    {
        $facebook_id = $this->facebook->getUser();
        if (!$facebook_id) {
            $this->flash->error("Invalid Facebook Call.");

            return $this->response->redirect('login');
        }
        try {
            $facebook_user = $this->facebook->api('/me');
        } catch (\FacebookApiException $e) {
            $this->flash->error("Could not fetch your facebook user.");
            //return $this->response->redirect('login');
        }
        $user = Users::findByKey($facebook_user['email']);
        if (!$user) {
            $user = new Users();
            $user->assign([
                    'display_name' => $facebook_user['name'],
                    'username' => $facebook_user['email'],
                    'email' => $facebook_user['email'],
                    'register_type' => Users::REGISTER_FACEBOOK_TYPE,
                    'password' => $this->security->hash(Phalcon\Text::random(Phalcon\Text::RANDOM_ALNUM, 8)),
                ]
            );
            if ($user->save()) {
                $this->auth->authUserById($user->id);
                $user->createRequest(Requests::TYPE_REGISTER);

                return $this->response->redirect('item/onsale');
            } else {
                $this->flash->error('There was an error connecting your facebook user.');
            }
        } else {
            $this->auth->authUserById($user->id);
            $this->auth->saveSuccessLogin($user);

            return $this->response->redirect('shop/open');
        }
    }

    public function loginAction()
    {
        $form = new LoginForm();

        try {
            if (!$this->request->isPost()) {
                if ($this->auth->hasRememberMe()) {
                    return $this->auth->loginWithRememberMe();
                }
            } else {
                if ($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $this->auth->check([
                            'email' => $this->request->getPost('email'),
                            'password' => $this->request->getPost('password'),
                            'remember' => $this->request->getPost('remember')
                        ]
                    );

                    return $this->response->redirect('shop/open');
                }
            }
        } catch (Exception $e) {
            $this->flash->error($e->getMessage());
        }
        $this->view->form = $form;
    }

    public function logoutAction()
    {
        $this->auth->remove();
        $this->getCurrentUser();
        if ($this->facebook->getUser()) {
            $logout_url = $this->facebook->getLogoutUrl(['next' => $this->url->get('index')]);
            $this->facebook->destroySession();
            $this->facebook->setAccessToken('');

            return $this->response->redirect($logout_url, true);
        }

        return $this->response->redirect('index');
    }

    public function registerAction()
    {
        $form = new SignUpForm();

        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost()) != false) {

                $user = new Users();

                $user->assign([
                        'username' => $this->request->getPost('username', 'striptags'),
                        'display_name' => $this->request->getPost('username', 'striptags'),
                        'email' => $this->request->getPost('email'),
                        'password' => $this->security->hash($this->request->getPost('password')),
                        'register_type' => Users::REGISTER_NORMAL_TYPE,
                    ]
                );
                if ($user->save()) {
                    $this->auth->authUserById($user->id);
                    $user->createRequest(Requests::TYPE_REGISTER);

                    return $this->dispatcher->forward([
                            'controller' => 'index',
                            'action' => 'index'
                        ]
                    );
                }
                $this->flash->error($user->getMessages());
            }
        }
        $this->view->current_page = 'register';
        $this->view->form = $form;
    }

    public function forgotPasswordAction()
    {
        $form = new ForgotPasswordForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $user = Users::findByKey($this->request->getPost('email'));
                if (!$user) {
                    $this->flash->success('There is no account associated to this email/username');
                } else {
                    $reset_url =
                        'http://' . $_SERVER['SERVER_NAME'] . "/user/resetpassword/{$user->email}/{$user->secret_key}";
                    $this->mail->send(
                        $user->email,
                        'HKT Password Recovery',
                        'reset',
                        ['reset_url' => $reset_url]
                    );
                    $this->flash->success('Success! Please check your messages for an email reset password');
                    return $this->forward('index/index');
                }
            }
        }
        $this->view->current_page = 'forgotpassword';
        $this->view->form = $form;
    }

    public function resetPasswordAction($email = '', $secret_key = '')
    {
        $user = Users::findFirstByEmail($email);
        if (!$user || $user->secret_key != $secret_key) {
            $this->response->redirect('');
        }
        $form = new ResetPasswordForm();

        if ($this->request->isPost()) {
            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $user->updatePassword($this->request->getPost('new_password'));
                $user->updateSecretKey();
                $this->flash->success('Your password was successfully changed');
                $this->auth->authUserById($user->id);

                return $this->forward('index/index');
            }
        }
        $this->view->email = $email;
        $this->view->secret_key = $secret_key;
        $this->view->form = $form;
    }

    public function changePasswordAction()
    {
        $form = new ChangePasswordForm();
        if ($this->request->isPost()) {
            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                if ($this->current_user->validatePassword($this->request->getPost('password'))) {
                    $this->current_user->updatePassword($this->request->getPost('new_password'));
                    $this->flash->success('Your password was successfully changed');
                    Phalcon\Tag::resetInput();
                } else {
                    $this->flash->error('Current password is incorrect');
                }
            }
        }
        $this->view->form = $form;
    }

    public function viewAction($id = null)
    {
        if (!$id) {
            $user = $this->current_user;
        } else {
            $user = Users::findFirst($id);
            if (!$user) {
                $user = $this->current_user;
            }
        }
        $favorite = $this->current_user->getFavorite($user);
        if ($favorite) {
            $favorite->increaseViews();
            $this->view->favorite = true;
        } else {
            $this->view->favorite = false;
        }
        $this->view->user = $user;
    }

    public function changeDisplayNameAction()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $user_id = $this->request->getPost('user_id', 'int');
            $display_name = $this->request->getPost('display_name', 'striptags');
            if ($user_id != $this->current_user->id) {
                $response = [
                    'status' => 'fail',
                    'message' => 'Invalid User',
                ];
            } else {
                $this->current_user->changeDisplayName($display_name);
                $response = [
                    'status' => 'success',
                    'message' => 'Display name changed!',
                    'display_name' => $this->current_user->getUserDisplayName(),
                ];
            }
            echo json_encode($response);
            return ;
        }
        $this->forwardNotFound();
    }

    public function changePlaceAction()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $place = $this->request->getPost('place', 'int');
            $user_id = $this->request->getPost('user_id', 'int');
            if (!isset(Users::$place_value[$place])) {
                $response = [
                    'status' => 'fail',
                    'message' => 'Invalid Place',
                ];
            } else {
                $user = Users::findFirst($user_id);
                if ($user_id != $this->current_user->id && !$this->current_user->isRoleOver(Users::ROLE_ADMIN) && !$user) {
                    $response = [
                        'status' => 'fail',
                        'message' => 'Invalid User',
                    ];
                } else {
                    $user->changePlace($place);
                    $response = [
                        'status' => 'success',
                        'message' => 'Place changed!',
                    ];
                }
            }
            echo json_encode($response);
            return ;
        }
        $this->forwardNotFound();
    }

    public function likeAction()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $response = [];
            $target_id = $this->request->getPost('target_id', 'int');
            $target_type = $this->request->getPost('target_type', 'int');
            $fav = $this->current_user->getFavorite($target_id, $target_type);
            if ($fav) {
                $fav->delete();
                $response = [
                    'status' => 'success',
                    'message' => 'Unliked!',
                ];
            } else {
                $target_class = Favorites::getTargetClass($target_type);
                if ($target_class) {
                    $target = $target_class::findFirstById($target_id);
                    if ($target && $this->current_user->canRegisterFavorite($target)) {
                        $this->current_user->registerFavorite($target_id, $target_type);
                        $response = [
                            'status' => 'success',
                            'message' => 'Liked!',
                        ];
                    }
                }
            }
            echo json_encode($response);
            return;
        }
    }

    public function donateAction($page = 1)
    {
        $form = new DonateCoinForm($this->current_user);
        if ($this->request->isPost()) {
            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $target_user = Users::findFirstById($this->request->getPost('target_user_id', 'int'));
                if (!$target_user || $target_user->id == $this->current_user->id) {
                    $this->flash->error('Invalid User !');
                    Phalcon\Tag::resetInput();
                } else {
                    $amount = $this->request->getPost('amount', 'int');
                    if($this->current_user->makeHCoinDonation($target_user, $amount)) {
                        $this->mail->send(
                            $target_user->email,
                            'HCoin Donation Received !',
                            'donation',
                            ['amount' => $amount, 'sender' => $this->current_user->display_name]
                        );
                        $this->flash->success('Donation Success !');
                    } else {
                        $this->flash->error('Error in processing Donation!');
                    }
                }
            }
        }

        if ($page < 1) {
            $page = 1;
        }
        $builder = $this->modelsManager->createBuilder()
            ->from('WalletLogs')
            ->where('transaction_id <> 0')
            ->andWhere('user_id = ' . $this->current_user->id)
            ->andWhere('type = ' . WalletLogs::TYPE_HCOIN)
            ->orderBy('id desc');

        $paginator = new Phalcon\Paginator\Adapter\QueryBuilder([
            'builder' => $builder,
            'limit' => self::LOGS_PER_PAGE,
            'page' => $page
        ]);
        $page = $paginator->getPaginate();
        $this->view->pagination = new Pagination($page, "/user/donate");
        $this->view->logs = $page->items;
        $this->view->form = $form;
        $this->view->current_page = 'donate';
    }

    public function transferAction($page = 1)
    {
        $form = new TransferMoneyForm($this->current_user);
        if ($this->request->isPost()) {
            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $target_user = Users::findFirstById($this->request->getPost('target_user_id', 'int'));
                if (!$target_user || $target_user->id == $this->current_user->id) {
                    $this->flash->error('Invalid User !');
                    Phalcon\Tag::resetInput();
                }
            }
        }

        if ($page < 1) {
            $page = 1;
        }
        $builder = $this->modelsManager->createBuilder()
            ->from('MoneyTransfers')
            ->andWhere('from_user_id = ' . $this->current_user->id)
            ->orderBy('id desc');

        $paginator = new Phalcon\Paginator\Adapter\QueryBuilder([
            'builder' => $builder,
            'limit' => self::LOGS_PER_PAGE,
            'page' => $page
        ]);
        $page = $paginator->getPaginate();
        $this->view->pagination = new Pagination($page, "/user/transfer");
        $this->view->transfers = $page->items;
        $this->view->fee_ratio = Setting::getTransferRate();
        $this->view->form = $form;
        $this->view->current_page = 'transfer';
    }

    public function createTransferAction()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $target_user_id = $this->request->getPost('target_user_id', 'int');
            $target_user = Users::findFirstById($target_user_id);
            $fee_bearer = $this->request->getPost('fee_bearer', 'int');
            $amount = $this->request->getPost('amount', 'int');
            if (!$target_user || !$amount || !$fee_bearer ||
                !in_array($fee_bearer, [MoneyTransfers::SENDER_FEE, MoneyTransfers::RECIPIENT_FEE])) {
                $response = [
                    'status' => 'fail',
                    'message' => 'Can not create money transfer !',
                ];
            } else {
                $error = $this->current_user->canTransferMoney($target_user, $amount, $fee_bearer);
                if ($error != 'OK') {
                    $response = [
                        'status' => 'fail',
                        'message' => $error,
                    ];
                } else {
                    $transfer = $this->current_user->createMoneyTransfer($target_user, $amount, $fee_bearer);
                    if ($transfer) {
                        $auth = CryptoHelper::calculateHMAC($transfer->nonce, $transfer->getEncodeData($transfer->nonce));
                        $handler = '';
                        if ($transfer->fee_bearer == MoneyTransfers::SENDER_FEE) {
                            $handler = 'You pay the transfer fee';
                        } elseif ($transfer->fee_bearer == MoneyTransfers::RECIPIENT_FEE) {
                            $handler = 'Recipient pay the transfer fee';
                        }

                        $confirm_url = 'http://' . $_SERVER['SERVER_NAME'] . "/user/confirmtransfer/{$transfer->id}/{$auth}";
                        $this->mail->send(
                            $this->current_user->email,
                            '[HKT] Money Transfer Confirmation',
                            'transfer_confirm',
                            [
                                'recipient' => $target_user->display_name,
                                'amount' => $transfer->transfer_amount,
                                'handler' => $handler,
                                'created_at' => $transfer->created_at,
                                'confirm_url' => $confirm_url,
                                'expire_time' => MoneyTransfers::EXPIRE_TIME / 60
                            ]
                        );

                        $response = [
                            'status' => 'success',
                            'message' => "Transfer has been created ! Please check your email ({$this->current_user->email}) to confirm the transfer."
                        ];
                    } else {
                        $response = [
                            'status' => 'fail',
                            'message' => 'Unknown error. Cannot create money transfer !'
                        ];
                    }
                }
            }
            echo json_encode($response);
        }
        return ;
    }

    public function confirmTransferAction($id = null, $auth = null){
        $valid_transfer = true;
        $confirm_done = false;
        if (!$id || !$auth) {
            $this->forwardNotFound();
        }
        $form = new ConfirmTransferMoneyForm();
        $transfer = MoneyTransfers::findFirstById($id);
        if ($transfer) {
            $transfer->updateStatus();
            if ($transfer->isProcessing() && $transfer->from_user_id == $this->current_user->id &&
                $transfer->isValidConfirmation($auth) && !$transfer->isTransferExpired()) {
                $valid_transfer = true;
            } else {
                $valid_transfer = false;
            }
        } else{
            $valid_transfer = false;
        }

        if ($this->request->isPost()) {
            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $data = $this->request->getPost('data');
                if (gettype($data) == 'array') {
                    if (array_key_exists('process', $data)) {
                        $this->current_user->processMoneyTransfer($transfer, MoneyTransfers::STATUS_TRANSFER);
                        $confirm_done = true;
                        $this->flash->success('Transfer is processed successfully !');
                    } elseif (array_key_exists('cancel', $data)) {
                        $this->current_user->processMoneyTransfer($transfer, MoneyTransfers::STATUS_CANCEL);
                        $confirm_done = true;
                        $this->flash->success('Transfer in canceled successfully !');
                    } else {
                        $this->flash->error('Error in processing request');
                    }
                } else {
                    $this->flash->error('Error in processing request');
                }
            }
        }

        $this->view->auth = $auth;
        $this->view->valid_transfer = $valid_transfer;
        $this->view->confirm_done = $confirm_done;
        $this->view->transfer = $transfer;
        $this->view->form = $form;
        $this->view->current_page = 'confirmtransfer';
    }

    public function allAction()
    {
        if ($this->request->isAjax()) {
            $all_users = Users::find(['columns' => 'id, username, display_name']);

            $usernames = [];
            foreach ($all_users as $user) {
                if ($user->id != $this->current_user->id) {
                    $usernames[] = [
                        'user_id' => $user->id,
                        'full_name' => sprintf("%s (%s)", $user->display_name, $user->username)
                    ];
                }
            }
            $this->view->disable();
            echo json_encode($usernames);
            return ;
        }
        $this->forwardNotFound();
    }

    public function readAnnouncementAction()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $user_announcement_id = $this->request->getPost('user_announcement_id', 'int');
            $user_announcement = UserAnnouncements::findFirst($user_announcement_id);
            if ($user_announcement && $user_announcement->user_id == $this->current_user->id) {
                $user_announcement->increaseReadTime();
                $response = [
                    'status' => 'success',
                ];
            } else {
                $response = [
                    'status' => 'fail',
                    'message' => 'Invalid User Announcement Id'
                ];
            }

            echo json_encode($response);
            return;
        }
        $this->forwardNotFound();
    }
}

