<?php

namespace App\EventSubscriber;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LoginSuccessEventSubscriber implements EventSubscriberInterface
{

    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function onLoginSuccessEvent(LoginSuccessEvent $event): void
    {
        //Acces au user depuis l'event
        $user = $event->getAuthenticatedToken()->getUser();
        //Envoi d'un mail
        $mail = (new Email())
            ->to($user->getEmail())
            ->from("admin@admin.com")
            ->subject("Connexion utilisateur")
            ->text("Bonjour " . $user->getEmail() . ", vous êtes connecté sur notre site");
        $this->mailer->send($mail);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccessEvent',
        ];
    }
}
