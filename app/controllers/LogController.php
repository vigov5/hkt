<?php

class LogController extends ControllerBase
{
    const LOGS_PER_PAGE = 50;

    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'log';
    }

    public function indexAction()
    {
        return $this->forwardUnderConstruction();
    }

    public function walletAction($user_id = 0, $page = 1)
    {
        if (!$this->current_user->isRoleOver(Users::ROLE_ADMIN)) {
            $user = $this->current_user;
        } else {
            $user = Users::findFirstById($user_id);
            if (!$user) {
                $user = $this->current_user;
            }
        }
        if ($page < 1) {
            $page = 1;
        }
        $builder = $this->modelsManager->createBuilder()
            ->from('WalletLogs')
            ->where('user_id = ' . $user->id)
            ->andWhere('type = ' . WalletLogs::TYPE_MONEY)
            ->orderBy('id desc');

        $paginator = new Phalcon\Paginator\Adapter\QueryBuilder(array(
            'builder' => $builder,
            'limit' => self::LOGS_PER_PAGE,
            'page' => $page
        ));
        $page = $paginator->getPaginate();
        $this->view->pagination = new Pagination($page, "/log/wallet/$user->id");
        $this->view->logs = $page->items;
        $this->view->current_page = 'wallet';
        $this->view->user = $user;
    }

    public function hcoinAction($user_id = 0, $page = 1)
    {
        if (!$this->current_user->isRoleOver(Users::ROLE_ADMIN)) {
            $user = $this->current_user;
        } else {
            $user = Users::findFirstById($user_id);
            if (!$user) {
                $user = $this->current_user;
            }
        }
        if ($page < 1) {
            $page = 1;
        }
        $builder = $this->modelsManager->createBuilder()
            ->from('WalletLogs')
            ->where('user_id = ' . $user->id)
            ->andWhere('type = ' . WalletLogs::TYPE_HCOIN)
            ->orderBy('id desc');

        $paginator = new Phalcon\Paginator\Adapter\QueryBuilder(array(
            'builder' => $builder,
            'limit' => self::LOGS_PER_PAGE,
            'page' => $page
        ));

        $page = $paginator->getPaginate();

        $this->view->pagination = new Pagination($page, "/log/hcoin/$user->id");
        $this->view->logs = $page->items;
        $this->view->current_page = 'hcoin';
        $this->view->user = $user;
    }
}

