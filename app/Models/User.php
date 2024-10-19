<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'count_number',
    ];

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

    // Relacionamento um-para-um com a carteira
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    // Gera o número aleatório quando o usuário for criado
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->count_number)) {
                $user->count_number = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            }
        });

        // Cria a carteira automaticamente após a criação do usuário
        static::created(function ($user) {
            $user->wallet()->create([
                'balance' => 0, // Valor inicial da carteira
            ]);
        });
    }

    // Transações enviadas pelo usuário
    public function sentTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'sender_id');
    }

    // Transações recebidas pelo usuário
    public function receivedTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'receiver_id');
    }
}
