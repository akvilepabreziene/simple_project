<?php

namespace App\Model;

use App\DataType\EmailMessage;
use App\Event\EmailSendEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserModel
 */
class UserModel
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var EntityManagerInterface
     */
    private $em;


    /**
     * UserModel constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param string $password
     * @param string $email
     */
    public function sendUserLoginDetails(string $password, string $email)
    {
        $body = sprintf('Jūsų prisijungimo slaptažodis:%s', $password);
        $message = (new EmailMessage())
            ->setBody($body)
            ->setSubject('Nauja registracija')
            ->setTo($email);

        $event = new EmailSendEvent($message);
        $this->dispatcher->dispatch($event, 'email.send');
    }
}