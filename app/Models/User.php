<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * GANTI BAGIAN INI.
     * Dulu pakai $fillable (yang membatasi), sekarang pakai $guarded (yang membebaskan).
     * guarded = [] artinya "Tidak ada yang dilarang, semua kolom boleh diisi".
     */
    protected $guarded = ['id']; 

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Helper untuk menampilkan Avatar atau Inisial
     */
    public function getAvatarHtml($size = '40px', $fontSize = '1rem')
    {
        if ($this->avatar) {
            // Jika avatar adalah URL (dari Google)
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                $src = $this->avatar;
            } else {
                $src = asset('storage/' . $this->avatar);
            }
            return '<img src="' . $src . '" class="w-100 h-100 object-fit-cover" alt="' . $this->name . '">';
        }

        // Jika kosong, tampilkan inisial
        $initial = strtoupper(substr($this->name, 0, 1));
        return '<div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary text-white fw-bold" style="font-size: ' . $fontSize . ';">' . $initial . '</div>';
    }

    /**
     * Generate 6 Digit Code
     */
    public function generateVerificationCode()
    {
        $code = rand(100000, 999999);
        $this->update(['verification_code' => $code]);
        return $code;
    }

    /**
     * Ganti Notifikasi Verifikasi Email Bawaan Laravel
     */
    public function sendEmailVerificationNotification()
    {
        $code = $this->verification_code;
        if (!$code) {
            $code = $this->generateVerificationCode();
        }
        $this->notify(new \App\Notifications\VerifyEmailCode($code));
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    /**
     * Akses Role (Biar bersih, gak ada spasi, dan huruf kecil semua)
     */
    public function getRoleAttribute($value)
    {
        return trim(strtolower($value));
    }
}