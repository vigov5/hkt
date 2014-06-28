<?php

class TransferController extends ControllerBase
{
    public function viewAction($id)
    {
        $transfer = MoneyTransfers::findFirstById($id);
        if (!$transfer) {
            return $this->forwardNotFound();
        }
        $transfer->updateStatus();
        $this->view->transfer = $transfer;
    }
}
