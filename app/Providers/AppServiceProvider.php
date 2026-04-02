<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paksa HTTPS hanya jika bukan di localhost atau 127.0.0.1
        if (!in_array(request()->getHost(), ['localhost', '127.0.0.1'])) {
            URL::forceScheme('https');
        }

        // 1. Kustomisasi Email Verifikasi (Bahasa Indonesia)
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verifikasi Alamat Email - LaundryKuy')
                ->greeting('Halo, ' . $notifiable->name . '!')
                ->line('Terima kasih telah mendaftar di LaundryKuy. Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda agar dapat mengakses layanan kami.')
                ->action('Verifikasi Email Sekarang', $url)
                ->line('Tautan verifikasi ini akan kedaluwarsa dalam 60 menit.')
                ->line('Jika Anda tidak merasa mendaftar akun, abaikan email ini.')
                ->salutation('Salam hangat, Tim LaundryKuy');
        });

        // 2. Kustomisasi Email Lupa Password (Bahasa Indonesia)
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Atur Ulang Kata Sandi - LaundryKuy')
                ->greeting('Halo, ' . $notifiable->name . '!')
                ->line('Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda.')
                ->action('Atur Ulang Kata Sandi', $url)
                ->line('Tautan ini akan kedaluwarsa dalam 60 menit.')
                ->line('Jika Anda tidak merasa meminta pengaturan ulang kata sandi, abaikan email ini.')
                ->salutation('Salam hangat, Tim LaundryKuy');
        });
    }
}
