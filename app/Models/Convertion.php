<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convertion extends Model
{
    use HasFactory;

    public $fillable = [
        'currency_from','currency_to', 'cambio'
    ];
}
