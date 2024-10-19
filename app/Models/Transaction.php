<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Transaction extends Model
{
    use HasFactory;

    // Definir os campos que podem ser preenchidos
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'amount',
        'type',
    ];

    // Relacionamento: a transação pode ter um remetente (usuário que envia dinheiro)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relacionamento: a transação pode ter um destinatário (usuário que recebe dinheiro)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Função auxiliar para saber se a transação é um depósito
    public function isDeposit()
    {
        return $this->type === 'deposit';
    }

    // Função auxiliar para saber se a transação é uma transferência
    public function isTransfer()
    {
        return $this->type === 'transfer';
    }

}
