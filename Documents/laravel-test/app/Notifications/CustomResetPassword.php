<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]));

        return (new MailMessage)
            ->subject('🔐 Réinitialisez votre mot de passe')
            ->greeting('Bonjour ' . $notifiable->name . ' 👋')
            ->line('Vous avez demandé à réinitialiser votre mot de passe.')
            ->action('Réinitialiser maintenant', $url)
            ->line('Si ce n’était pas vous, vous pouvez ignorer ce message.')
            ->salutation('À bientôt 👋 – L’équipe InnovGest');
    }
}
