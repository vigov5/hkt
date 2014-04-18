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
     * Sends e-mails via Gmail based on predefined templates
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

        $template = $this->getTemplate($name, $params);
        // Create the message
        $message = Swift_Message::newInstance()
            ->setSubject($subject)
            ->setTo($to)
            ->setFrom([
                $mail_settings->from_email => $mail_settings->from_name
            ])
            ->setBody($template, 'text/html');

        if (!$this->transport) {
            $this->transport = Swift_SmtpTransport::newInstance(
                $mail_settings->smtp->server,
                $mail_settings->smtp->port,
                $mail_settings->smtp->security
            )
            ->setUsername($mail_settings->smtp->username)
            ->setPassword($mail_settings->smtp->password);
        }

        // Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($this->transport);

        try {
            return $mailer->send($message);
        } catch (\Phalcon\Exception $e) {
            $p = new \Phalcon\Utils\PrettyExceptions();
            $p->handle($e);
            exit;
        }
    }
}
