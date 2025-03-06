<?php

namespace App\Notifications;

use App\Models\responsavel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class StudentEventNotification extends Notification
{
    use Queueable;

    protected $evento;
    protected $estudante;
    protected $responsavelId;
    protected $tipo;
    protected $dataNotificacao;

    /**
     * Cria uma nova instância da notificação.
     *
     * @param string $evento Tipo de evento: 'enter', 'exit', 'trip_start', 'trip_end', 'pedido_conexao', 'negacao_conexao'
     * @param \App\Models\Estudante $estudante Objeto do estudante envolvido
     * @param int|null $responsavelId ID do responsável (somente para pedidos/negações de conexão)
     */
    public function __construct($evento, $estudante, $responsavelId = null)
    {
        $this->evento = $evento;
        $this->estudante = $estudante;
        $this->responsavelId = $responsavelId;

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
            case 'pedido_conexao':
                $mensagem = "O estudante {$nomeEstudante} enviou um pedido de conexão.";
                break;
            case 'negacao_conexao':
                $mensagem = "O estudante {$nomeEstudante} negou seu pedido de conexão.";
                break;
            case 'remocao_conexao':
                $mensagem = "O estudante {$nomeEstudante} removeu a conexão contigo.";
                break;
            case 'aceite_conexao':
                $mensagem = "O estudante {$nomeEstudante} aceitou o teu pedido de conexão.";
                break;
            default:
                $mensagem = "Evento desconhecido para o estudante {$nomeEstudante}.";
                break;
        }

        $this->dataNotificacao = [
            'mensagem'     => $mensagem,
            'evento'       => $this->evento,
            'estudante_id' => $this->estudante->id,
            'responsavel_id' => $this->responsavelId,
            'timestamp'    => now()
        ];
    }

    /**
     * Define os canais pelos quais a notificação será enviada.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Retorna a representação da notificação para armazenamento no banco de dados.
     */
    public function toDatabase($notifiable)
    {
        return $this->dataNotificacao;
    }

    /**
     * Envia a notificação para os responsáveis certos.
     */
    public static function notifyResponsaveis($evento, $estudante, $responsavelId = null)
    {
        if (in_array($evento, ['enter', 'exit', 'trip_start', 'trip_end'])) {
            // Notificar todos os responsáveis ativos do estudante
            $responsaveis = DB::table('estudantes_responsavels')
                ->where('estudantes_id', $estudante->id)
                ->where('estado', 1)
                ->pluck('responsavels_id');

            foreach ($responsaveis as $id) {
                $responsavel = responsavel::find($id);
                if ($responsavel) {
                    $responsavel->notify(new self($evento, $estudante));
                }
            }
        } elseif (in_array($evento, ['pedido_conexao', 'negacao_conexao','remocao_conexao','aceite_conexao']) && $responsavelId) {
            // Notificar um único responsável para pedidos/negações de conexão
            $responsavel = responsavel::find($responsavelId);
            if ($responsavel) {
                $responsavel->notify(new self($evento, $estudante, $responsavelId));
            }
        }
    }
}
