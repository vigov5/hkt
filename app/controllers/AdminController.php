<?php


class AdminController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'admin';
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $users = Users::find('role >= ' . Users::ROLE_ADMIN);
        $this->view->total_user = Users::count();
        $this->view->total_wallet = Users::sum(['column' => 'wallet']);
        $this->view->total_hcoin = Users::sum(['column' => 'hcoin']);
        $this->view->total_success_invoices = Invoices::count('status = ' . Invoices::STATUS_ACCEPT . ' AND item_type = ' . Items::TYPE_NORMAL);
        $this->view->total_success_invoices_amount = Invoices::sum([
            'column' => 'price',
            'conditions' => 'status = ' . Invoices::STATUS_ACCEPT . ' AND item_type = ' . Items::TYPE_NORMAL
        ]);
        $deposits = Invoices::sum([
            'column' => 'price',
            'group' => 'updated_by',
            'conditions' => 'status = ' . Invoices::STATUS_ACCEPT . ' AND item_type = ' . Items::TYPE_DEPOSIT,
        ]);
        $withdraws = Invoices::sum([
            'column' => 'price',
            'group' => 'updated_by',
            'conditions' => 'status = ' . Invoices::STATUS_ACCEPT . ' AND item_type = ' . Items::TYPE_WITHDRAW,
        ]);
        $deposit_total = 0;
        foreach ($deposits as $deposit) {
            $deposit_total += $deposit->sumatory;
        }

        $withdraw_total = 0;
        foreach ($withdraws as $withdraw) {
            $withdraw_total += $withdraw->sumatory;
        }

        $this->view->deposits = $deposits;
        $this->view->withdraws = $withdraws;
        $this->view->withdraw_total = $withdraw_total;
        $this->view->deposit_total = $deposit_total;
        $this->view->users = $users;
    }
}
