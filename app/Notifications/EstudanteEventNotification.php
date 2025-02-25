<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EstudanteEventNotification extends Notification
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

        // Captura o timestamp atual e formata a hora e a data
        $timestamp = now();
        $hora = $timestamp->format('H:i');
        $data = $timestamp->format('d/m/Y');
        
        // Para o estudante, mensagens no formato "Você ..." com data e hora incluídos
        switch ($this->evento) {
            case 'enter':
                $mensagem = "Você entrou no veículo às {$hora} do dia {$data}.";
                break;
            case 'exit':
                $mensagem = "Você saiu do veículo às {$hora} do dia {$data}.";
                break;
            case 'trip_start':
                $mensagem = "Sua viagem foi iniciada às {$hora} do dia {$data}.";
                break;
            case 'trip_end':
                $mensagem = "Sua viagem foi concluída às {$hora} do dia {$data}.";
                break;
            default:
                $mensagem = "Evento desconhecido às {$hora} do dia {$data}.";
                break;
        }

        $this->dataNotificacao = [
            'mensagem'     => $mensagem,
            'evento'       => $this->evento,
            'estudante_id' => $this->estudante->id,
            'timestamp'    => $timestamp
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