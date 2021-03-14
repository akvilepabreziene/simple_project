<?php


namespace App\Event;


use App\DataType\EmailMessage;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class EmailSendEvent
 */
class EmailSendEvent extends Event
{
    public const NAME = 'email.send';
    /**
     * @var EmailMessage
     */
    private $message;

    /**
     * EmailSendEvent constructor.
     * @param EmailMessage $message
     */
    public function __construct(EmailMessage $message)
    {
        $this->message = $message;
    }

    /**
     * @return EmailMessage
     */
    public function getMessage(): EmailMessage
    {
        return $this->message;
    }

}