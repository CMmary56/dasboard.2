<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $table = 'cuentas';

    protected $fillable = [
        'user_id',
        'tipo',
        'numero_cuenta',
        'saldo',
        'moneda'
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    // public function transacciones()
    // {
    //     return $this->hasMany(Transaccion::class);
    // }
}