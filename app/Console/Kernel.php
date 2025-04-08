<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Defina os comandos do Artisan para o aplicativo.
     *
     * @var array
     */
    protected $commands = [
        // Comandos personalizados aqui
    ];

    /**
     * Defina o agendamento dos comandos.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Defina as tarefas programadas aqui
    }

    /**
     * Registre os comandos para o aplicativo.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
