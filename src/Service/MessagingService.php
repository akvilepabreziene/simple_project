<?php


namespace App\Service;

use App\DataType\EmailMessage;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * Class MessagingService
 */
class MessagingService
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * MessagingService constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param EmailMessage $emailMessage
     * @return $this
     * @throws TransportExceptionInterface
     */
    public function sendEmail(EmailMessage $emailMessage): self
    {
        $message = (new Email())
            ->text($emailMessage->getBody())
            ->to($emailMessage->getTo())
            ->subject($emailMessage->getSubject())
            ->from('akvile.pabreziene@gmail.com');

        $this->mailer->send($message);

        return $this;
    }
}