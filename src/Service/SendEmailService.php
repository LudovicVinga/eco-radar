<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class SendEmailService
{
    public function __construct(
        private MailerInterface $mailer,
    ) {
    }

    /**
     * Cette méthode permet d'envoyer l'email
     *
     * @param array<string, mixed> $data
     * @return void
     */
    public function send(array $data = []): void
    {
        $senderEamil = $data['sender_email'];
        $senderFullName = $data['sender_full_name'];
        $recipientEmail = $data['recipient_email'];
        $subject = $data['subject'];
        $htmlTemplate = $data['html_template'];
        $context = $data['context'];

        $email = new TemplatedEmail();

        $email
            ->from(new Address($senderEamil, $senderFullName))
            ->to($recipientEmail)
            ->subject($subject)
            ->htmlTemplate($htmlTemplate)
            ->context($context)
        ;

        try {
            $this->mailer->send($email);
        } catch (TransportException $th) {
            throw $th;
        }
    }
}
