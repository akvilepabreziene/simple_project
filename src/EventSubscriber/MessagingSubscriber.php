<?php

namespace App\EventSubscriber;

use App\Event\EmailSendEvent;
use App\Service\MessagingService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class MessagingSubscriber
 */
class MessagingSubscriber implements EventSubscriberInterface
{
    /**
     * @var MessagingService
     */
    private $service;

    /**
     * MessagingSubscriber constructor.
     * @param MessagingService $service
     */
    public function __construct(MessagingService $service)
    {
        $this->service = $service;
    }


    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EmailSendEvent::NAME => 'onEmailSend'
        ];
    }

    /**
     * @param EmailSendEvent $event
     */
    public function onEmailSend(EmailSendEvent $event)
    {
        $this->service->sendEmail($event->getMessage());
    }
}