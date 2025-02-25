<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentEventNotification extends Notification
{
    use Queueable;

    protected $evento;
    protected $estudante;
    protected $dataNotificacao;

    /**
     * Cria uma nova instância da notificação.
     *
     * @param string $evento       Tipo de evento: 'enter', 'exit', 'trip_start', 'trip_end'
     * @param \App\Models\estudante $estudante  Objeto do estudante envolvido
     */
    public function __construct($evento, $estudante)
    {
        $this->evento = $evento;
        $this->estudante = $estudante;
        
        // Obtém o nome do estudante através do relacionamento com User
        $nomeEstudante = $this->estudante->user->name;

        // Define a mensagem com base no tipo de evento
        switch ($this->evento) {
            case 'enter':
                $mensagem = "O estudante {$nomeEstudante} entrou no veículo.";
                break;
            case 'exit':
                $mensagem = "O estudante {$nomeEstudante} saiu do veículo.";
                break;
            case 'trip_start':
                $mensagem = "Uma viagem foi iniciada com o estudante {$nomeEstudante} a bordo.";
                break;
            case 'trip_end':
                $mensagem = "A viagem com o estudante {$nomeEstudante} foi concluída.";
                break;
            default:
                $mensagem = "Evento desconhecido para o estudante {$nomeEstudante}.";
                break;
        }

        $this->dataNotificacao = [
            'mensagem'     => $mensagem,
            'evento'       => $this->evento,
            'estudante_id' => $this->estudante->id,
            'timestamp'    => now()
        ];
    }

    /**
     * Define os canais pelos quais a notificação será enviada.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Utilizamos apenas o canal 'database'
        return ['database'];
    }

    /**
     * Retorna a representação da notificação para armazenamento no banco de dados.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return $this->dataNotificacao;
    }
}