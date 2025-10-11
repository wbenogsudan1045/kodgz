<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class BoardActivityNotification extends Notification
{
    use Queueable;

    protected $board;
    protected $message;

    public function __construct($board, $message)
    {
        $this->board = $board;
        $this->message = $message;
    }

    // Deliver via database
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'board_id' => $this->board->id,
            'board_name' => $this->board->name,
            'message' => $this->message,
            'url' => route('boards.show', $this->board->id),
        ];
    }
}
