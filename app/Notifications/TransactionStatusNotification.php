<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Transaction;

class TransactionStatusNotification extends Notification
{
    use Queueable;

    protected $transaction;
    protected $status;

    public function __construct(Transaction $transaction, $status)
    {
        $this->transaction = $transaction;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $message = new MailMessage;
        $message->greeting('Hello ' . $this->transaction->user->name);

        if ($this->status === 'approved') {
            $message->line('Your transaction has been approved.');
        } elseif ($this->status === 'rejected') {
            $message->line('Your transaction has been rejected.');
        }

        $message->line('Transaction Details:')
            ->line('Type: ' . $this->transaction->type)
            ->line('Amount: ' . $this->transaction->amount)
            ->line('Description: ' . $this->transaction->description)
            ->line('Status: ' . $this->status)
            ->line('Thank you for using our application!');

        return $message;
    }

    public function toArray($notifiable)
    {
        return [
            'transaction_id' => $this->transaction->id,
            'status' => $this->status,
        ];
    }
}
