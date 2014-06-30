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
        $type = ' (item_type = ' . Items::TYPE_NORMAL . ' OR item_type = ' . Items::TYPE_TAX_FREE . ') ';
        $users = Users::find('role >= ' . Users::ROLE_ADMIN);
        $this->view->total_user = Users::count();
        $this->view->total_wallet = Users::sum(['column' => 'wallet']);
        $this->view->total_hcoin = Users::sum(['column' => 'hcoin']);
        $this->view->total_success_invoices = Invoices::count('status = ' . Invoices::STATUS_ACCEPT . " AND $type");
        $this->view->total_success_invoices_amount = Invoices::sum([
            'column' => 'price',
            'conditions' => 'status = ' . Invoices::STATUS_ACCEPT  . " AND $type"
        ]);
        $this->view->total_unaccepted_invoices_amount = Invoices::sum([
            'column' => 'price',
            'conditions' => 'status = ' . Invoices::STATUS_SENT . " AND $type"
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
        $current_wallet = [];
        foreach ($deposits as $deposit) {
            $deposit_total += $deposit->sumatory;
            if (isset($current_wallet[$deposit->updated_by])) {
                $current_wallet[$deposit->updated_by] += $deposit->sumatory;
            } else {
                $current_wallet[$deposit->updated_by] = $deposit->sumatory;
            }
        }

        $withdraw_total = 0;
        foreach ($withdraws as $withdraw) {
            $withdraw_total += $withdraw->sumatory;
            if (isset($current_wallet[$withdraw->updated_by])) {
                $current_wallet[$withdraw->updated_by] -= $withdraw->sumatory;
            } else {
                $current_wallet[$withdraw->updated_by] = -$withdraw->sumatory;
            }
        }

        $this->view->deposits = $deposits;
        $this->view->withdraws = $withdraws;
        $this->view->withdraw_total = $withdraw_total;
        $this->view->deposit_total = $deposit_total;
        $this->view->current_wallet = $current_wallet;
        $this->view->users = $users;
    }
}
