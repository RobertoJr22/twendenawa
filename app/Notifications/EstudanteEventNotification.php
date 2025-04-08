<?php

namespace App\Notifications;

use App\Models\estudante;
use App\Models\responsavel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EstudanteEventNotification extends Notification implements ShouldQueue        
{
    use Queueable;

    protected $evento;
    protected $estudante;
    protected $dataNotificacao;
    protected $responsavel;

    /**
     * Cria uma nova instância da notificação.
     *
     * @param string $evento       Tipo de evento: 'enter', 'exit', 'trip_start', 'trip_end'
     * @param \App\Models\estudante $estudante  Objeto do estudante envolvido
     */
    public function __construct($evento, $estudante, $responsavel = null)
    {
        $this->evento = $evento;
        $this->estudante = $estudante;
        $this->responsavel = $responsavel;

        $nomeResponsavel = $this->responsavel ? $this->responsavel->user->name : '';

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
            case 'pedido_conexao':
                $mensagem = "O responsavel {$nomeResponsavel}, fez um pedido de conexão.";
                break;
            case 'negacao_conexao':
                $mensagem = "O responsavel {$nomeResponsavel}, negou seu pedido de conexão.";
                break;
            case 'remocao_conexao':
                $mensagem = "O responsavel {$nomeResponsavel}, desfez a conexão contigo.";
                break;
            case 'aceite_conexao':
                $mensagem = "O responsavel {$nomeResponsavel}, aceitou o teu pedido de conexão.";
                break;
            default:
                $mensagem = "Evento desconhecido às {$hora} do dia {$data}.";
                break;
        }

        $this->dataNotificacao = [
            'mensagem'     => $mensagem,
            'evento'       => $this->evento,
            'estudante_id' => $this->estudante->id,
            'responsavel_id' => $this->responsavel ? $this->responsavel->id : null,
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

    public static function NotifyEstudante($evento, $estudanteId, $responsavelId = null){
        if(in_array($evento, ['enter', 'exit', 'trip_start', 'trip_end'])){
                //Notificar apenas as atividade do estudante ao estudante 
            $estudante = estudante ::find($estudanteId);
            if($estudante){
                $estudante->notify(new self($evento,$estudante));
            }
        }elseif(in_array($evento,['pedido_conexao', 'negacao_conexao','remocao_conexao','aceite_conexao'])  && $responsavelId){
            $responsavel = responsavel::find($responsavelId);
            $estudante = estudante::find($estudanteId);

            $estudante->notify(new self($evento,$estudante,$responsavel));
        }
    }
}