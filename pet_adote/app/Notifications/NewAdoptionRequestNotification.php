<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAdoptionRequestNotification extends Notification
{
    use Queueable;

    protected $adoptionRequest;

    public function __construct($adoptionRequest)
    {
        $this->adoptionRequest = $adoptionRequest;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $petName = $this->adoptionRequest->pet->nome;
        $adopterName = $this->adoptionRequest->user->name;

        return (new MailMessage)
            ->subject('Nova solicitação de adoção para: ' . $petName)
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Ótimas notícias! Alguém se interessou em adotar o seu pet **' . $petName . '**. 🎉')
            ->line('**Interessado(a):** ' . $adopterName)
            ->action('Ver Perfil e Solicitação', route('adoptions.index'))
            ->line('Acesse o painel para iniciar a entrevista, aprovar ou rejeitar o pedido.')
            ->salutation('Equipe PetAdote');
    }
}