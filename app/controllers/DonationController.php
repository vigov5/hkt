<?php

class DonationController extends ControllerBase
{
    public function viewAction($id)
    {
        $donation = CoinDonations::findFirstById($id);
        if (!$donation) {
            return $this->forwardNotFound();
        }
        $this->view->donation = $donation;
    }
}