<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExampleEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $message; // Dado a ser transmitido

    /**
     * Criar uma nova instância do evento.
     *
     * @param string $message
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Obter os canais em que o evento deve ser transmitido.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('example-channel'); // Canal privado
    }

    /**
     * Obter o nome do evento que deve ser transmitido.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'example.event'; // Nome do evento transmitido
    }
}
