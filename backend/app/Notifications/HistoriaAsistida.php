<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class HistoriaAsistida extends Notification
{
    use Queueable;

    protected $historia;
    protected $userType;

    public function __construct($historia, $userType)
    {
        $this->historia = $historia;
        $this->userType = $userType;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Se acaba de marcar una historia como asistida.',
            'historia_id' => $this->historia->id,
            'user_type' => $this->userType,
        ];
    }
}
