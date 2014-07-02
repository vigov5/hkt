<?php

class MainTask extends \Phalcon\CLI\Task
{
    public function KpiAction($date = null)
    {
        $day = DateHelper::day_before(1, $date);
        $day_start = $day . ' 00:00:00';
        $day_end = $day . ' 23:59:59';
        $total_users = Users::count();
        $new_users = Users::count("created_at >= '{$day_start}' and created_at <= '{$day_end}'");
        $login_users = SuccessLogins::count("created_at >= '{$day_start}' and created_at <= '{$day_end}'");
        $invoices = Invoices::find("updated_at >= '{$day_start}' and updated_at <= '{$day_end}' and status = " . Invoices::STATUS_ACCEPT);
        $deposit = 0;
        $withdraw = 0;
        $purchase = 0;
        $deposit_users = [];
        $withdraw_users = [];
        $purchase_users = [];
        $hcoin = 0;
        foreach ($invoices as $invoice) {
            if ($invoice->item->isDepositItem()) {
                $deposit += $invoice->price;
                if (!isset($deposit_users[$invoice->from_user_id])) {
                    $deposit_users[] = $invoice->from_user_id;
                }
            } elseif ($invoice->item->isWithdrawItem()) {
                $withdraw += $invoice->price;
                if (!isset($withdraw_users[$invoice->from_user_id])) {
                    $withdraw_users[] = $invoice->from_user_id;
                }
            } elseif ($invoice->item->isNormalItem() || $invoice->item->isTaxFreeItem()) {
                $purchase += $invoice->price;
                if (!isset($purchase_users[$invoice->from_user_id])) {
                    $purchase_users[] = $invoice->from_user_id;
                }
            }
            $hcoin += $invoice->hcoin_receive;
        }

        $kpi = Kpis::findFirstByDay($day);
        if (!$kpi) {
            $kpi = new Kpis();
        }
        $kpi->day = $day;
        $kpi->total_users = $total_users;
        $kpi->new_users = $new_users;
        $kpi->login_users = $login_users;
        $kpi->deposit_users = count($deposit_users);
        $kpi->withdraw_users = count($withdraw_users);
        $kpi->purchase_users = count($purchase_users);
        $kpi->deposit = $deposit;
        $kpi->withdraw = $withdraw;
        $kpi->purchase = $purchase;
        $kpi->hcoin = $hcoin;
        $kpi->save();

        $mail = new Mail();
        $emails = Users::getAdminEmails();
        $mail->send($emails, 'KPI Information', 'kpi', ['kpi' => $kpi]);
    }

    public function invoiceAction()
    {
        $datetime = DateHelper::now();
        $before = DateHelper::minute_before(5, $datetime);
        $invoices = Invoices::find(["created_at > '$before'"]);
        $special_invoices = [];
        $normal_invoices = [];
        foreach ($invoices as $invoice) {
            if ($invoice->isSpecialInvoice()) {
                $special_invoices[] = $invoice;
            } else {
                $normal_invoices[] = $invoice;
            }
        }
        $mail = new Mail();
        if (count($special_invoices)) {
            $emails = Users::getAdminEmails();
            $mail->send($emails, 'Special Invoices Received', 'special_invoice', ['count' => count($special_invoices)]);
        }

        /* Send email to shop's staff. Underconstruction
        if (count($normal_invoices)) {
            $shops = [];
            $users = [];
            foreach($normal_invoices as $invoice) {
                if ($invoice->to_shop_id) {
                    $shop_id = $invoice->to_shop_id;
                    if (isset($shops[$shop_id])) {
                        $shops[] = $shop_id;
                    }
                } elseif ($invoice->to_user_id) {
                    $user_id = $invoice->to_user_id;
                    if (isset($users[$user_id])) {
                        $users[] = $user_id;
                    }
                }
            }
        }
        */
    }

    public function updateTransferAction()
    {
        $before = DateHelper::minute_before(5);
        $transfers = MoneyTransfers::find([
            "created_at > '$before'",
            'status' => MoneyTransfers::STATUS_CREATE
        ]);
        foreach ($transfers as $transfer) {
            $transfer->updateStatus();
        }
    }
}
