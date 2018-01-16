<?php

namespace AppBundle\Service\Mailer;

class Mailer
{
    public $swiftMAiler;

    public $contact;


    /**
     * @param $swiftMailer
     * @param $contact
     */
    public function __construct($swiftMailer, $contact)
    {
        $this->swiftMAiler = $swiftMailer;
        $this->contact = $contact;
    }


    public function sendContactMail($mailBody)
    {
        $message = (new \Swift_Message('Bicycle contact email'))
            ->setFrom($this->contact)
            ->setTo($this->contact)
            ->setBody($mailBody, 'text/html');
        $this->swiftMAiler->send($message);

        return true;
    }
}