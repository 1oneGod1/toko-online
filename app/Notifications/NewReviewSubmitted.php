<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReviewSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // Kirim ke email dan database
    }

    public function toMail(object $notifiable): MailMessage
    {
        $productName = $this->review->product->name;
        return (new MailMessage)
                    ->subject("Ulasan Baru untuk Produk: {$productName}")
                    ->greeting('Halo Admin,')
                    ->line("Ada ulasan baru untuk produk {$productName}.")
                    ->line("Peringkat: {$this->review->rating}/5")
                    ->line("Komentar: \"{$this->review->comment}\"")
                    ->action('Lihat Produk', url('/products/' . $this->review->product->id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Ulasan baru dari {$this->review->user->name} untuk produk {$this->review->product->name}",
            'url' => url('/products/' . $this->review->product->id),
        ];
    }
}
