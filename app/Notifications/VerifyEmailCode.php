<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Setting;

class VerifyEmailCode extends Notification
{
    use Queueable;

    private $code;

    /**
     * Create a new notification instance.
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $setting = Setting::first();
        $appName = $setting->shop_name ?? config('app.name');
        
        return (new MailMessage)
                    ->subject('Kode Verifikasi Akun ' . $appName . ' - ' . $this->code)
                    ->greeting('Halo, ' . $notifiable->name . '!')
                    ->line('Terima kasih telah bergabung dengan ' . $appName . '.')
                    ->line('Berikut adalah kode verifikasi Anda untuk mengaktifkan akun:')
                    ->line('**' . $this->code . '**')
                    ->line('Silakan masukkan kode tersebut pada halaman verifikasi di aplikasi.')
                    ->line('Jika Anda tidak merasa mendaftar akun di aplikasi kami, silakan abaikan email ini.')
                    ->salutation('Salam, Tim ' . $appName);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
