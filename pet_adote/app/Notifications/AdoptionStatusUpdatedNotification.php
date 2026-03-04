<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdoptionStatusUpdatedNotification extends Notification
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
        $status = $this->adoptionRequest->status;

        $mail = (new MailMessage)
            ->subject('Atualização no pedido de adoção: ' . $petName)
            ->greeting('Olá, ' . $notifiable->name . '!');

        if ($status === 'em_analise') {
            $mail->line('O doador do pet **' . $petName . '** está analisando seu perfil e colocou seu pedido **Em Análise/Entrevista**.')
                 ->line('Fique atento(a), pois o doador pode entrar em contato com você em breve pelo telefone ou e-mail cadastrado!');
        } elseif ($status === 'aprovado') {
            $mail->line('🎉 PARABÉNS! Sua solicitação para adotar o pet **' . $petName . '** foi **APROVADA**!')
                 ->line('Entre em contato com o doador para combinar a entrega do seu novo melhor amigo.');
        } elseif ($status === 'rejeitado') {
            $mail->line('Infelizmente, sua solicitação para o pet **' . $petName . '** foi rejeitada desta vez.');
            
            // Se o doador preencheu o motivo da rejeição, nós mostramos no e-mail
            if ($this->adoptionRequest->motivo_rejeicao) {
                $mail->line('**Motivo informado pelo doador:** ' . $this->adoptionRequest->motivo_rejeicao);
            }
            
            $mail->line('Não desanime! Existem muitos outros pets no sistema esperando por um lar cheio de amor.');
        }

        $mail->action('Acompanhar Meus Pedidos', route('adoptions.meus_pedidos'))
             ->salutation('Equipe PetAdote');

        return $mail;
    }
}