<?php

class TransactionTask extends \Phalcon\CLI\Task
{
    public function notifyDonationAction($encoded_data)
    {
        $data = unserialize(base64_decode($encoded_data));
        $mail = new Mail();
        $mail->send(
            $data['recipient'],
            '[HKT] HCoin Donation Received !',
            'donation',
            ['amount' => $data['amount'], 'sender' => $data['sender']]
        );
    }

    public function confirmTransferAction($transfer_id)
    {
        $transfer = MoneyTransfers::findFirstById($transfer_id);
        if ($transfer) {
            $auth = CryptoHelper::calculateHMAC($transfer->nonce, $transfer->getEncodeData($transfer->nonce));
            $confirm_url = "{$this->config->application->baseUri}user/confirmtransfer/{$transfer->id}/{$auth}";
            $mail = new Mail();
            $mail->send(
                $transfer->fromUser->getNotificationEmail(),
                '[HKT] Money Transfer Confirmation',
                'transfer_confirm',
                [
                    'recipient' => $transfer->toUser->display_name,
                    'amount' => $transfer->transfer_amount,
                    'handler' => $transfer->getHandlerText(),
                    'created_at' => $transfer->created_at,
                    'confirm_url' => $confirm_url,
                    'expire_time' => MoneyTransfers::EXPIRE_TIME / 60
                ]
            );
        }
    }

    public function notifyTransferAction($transfer_id)
    {
        $transfer = MoneyTransfers::findFirstById($transfer_id);
        if ($transfer) {
            $mail = new Mail();
            $mail->send(
                $transfer->toUser->getNotificationEmail(),
                '[HKT] Money Transfer Received',
                'transfer_notice',
                [
                    'sender' => $transfer->fromUser->display_name,
                    'amount' => $transfer->transfer_amount,
                    'handler' => $transfer->getHandlerText(),
                    'updated_at' => $transfer->updated_at,
                ]
            );
        }
    }
}
