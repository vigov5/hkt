<?php

require_once __DIR__ . '/../library/Swift/swift_required.php';
use Phalcon\Mvc\User\Component;
use Phalcon\Mvc\View;

/**
 * Sends e-mails based on pre-defined templates
 */
class Mail extends Component
{

    protected $transport;

    /**
     * Applies a template to be used in the e-mail
     *
     * @param string $name
     * @param array $params
     */
    public function getTemplate($name, $params)
    {
        return $this->view->getRender('email_template', $name, $params, function ($view) {
            $view->setRenderLevel(View::LEVEL_LAYOUT);
        });

        return $view->getContent();
    }

    /**
     *
     * @param array $to
     * @param string $subject
     * @param string $name
     * @param array $params
     * @return bool
     */
    public function send($to, $subject, $name, $params)
    {

        // Settings
        $mail_settings = $this->config->mail;

        $message = $this->getTemplate($name, $params);
        $headers =  "From: {$mail_settings->from_name} <{$mail_settings->from_email}> \r\n" . "X-Mailer: PHP/" . phpversion();
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf8' . "\r\n";
        if (is_array($to)) {
            foreach ($to as $to_email) {
                EmailLogs::createNew($to_email, $subject);
                mail($to_email, $subject, $message, $headers);
            }
        } else {
            EmailLogs::createNew($to, $subject);
            mail($to, $subject, $message, $headers);
        }
    }
}
