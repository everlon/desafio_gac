<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Wallet extends Model
{
    use HasFactory;

    // Definir os campos que podem ser preenchidos
    protected $fillable = [
        'user_id',
        'balance',
    ];

    // Relacionamento: a carteira pertence a um usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Atualizar o saldo da carteira
    public function updateBalance(float $amount)
    {
        $this->balance += $amount;
        $this->save();
    }

    // Verificar se o saldo é suficiente para uma transferência
    public function hasSufficientBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }
    

}
