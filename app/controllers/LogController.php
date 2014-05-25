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

    public function walletAction($page = 1)
    {
        if ($page < 1) {
            $page = 1;
        }
        $builder = $this->modelsManager->createBuilder()
            ->from('WalletLogs')
            ->where('user_id = ' . $this->current_user->id)
            ->andWhere('type = ' . WalletLogs::TYPE_MONEY)
            ->orderBy('id desc');

        $paginator = new Phalcon\Paginator\Adapter\QueryBuilder(array(
            'builder' => $builder,
            'limit' => self::LOGS_PER_PAGE,
            'page' => $page
        ));
        $page = $paginator->getPaginate();
        $this->view->pagination = new Pagination($page, "/log/wallet");
        $this->view->logs = $page->items;
        $this->view->current_page = 'wallet';
    }

    public function hcoinAction($page = 1)
    {
        if ($page < 1) {
            $page = 1;
        }
        $builder = $this->modelsManager->createBuilder()
            ->from('WalletLogs')
            ->where('user_id = ' . $this->current_user->id)
            ->andWhere('type = ' . WalletLogs::TYPE_HCOIN)
            ->orderBy('id desc');

        $paginator = new Phalcon\Paginator\Adapter\QueryBuilder(array(
            'builder' => $builder,
            'limit' => self::LOGS_PER_PAGE,
            'page' => $page
        ));

        $page = $paginator->getPaginate();

        $this->view->pagination = new Pagination($page, "/log/hcoin");
        $this->view->logs = $page->items;
        $this->view->current_page = 'hcoin';
    }
}

