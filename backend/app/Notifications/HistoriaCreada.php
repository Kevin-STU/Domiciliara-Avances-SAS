<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class HistoriaCreada extends Notification
{
    use Queueable;

    protected $historia;

    public function __construct($historia)
    {
        $this->historia = $historia;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Se ha creado una nueva historia médica.',
            'historia_id' => $this->historia->id,
            'user_type' => 'patient',
        ];
    }
}
