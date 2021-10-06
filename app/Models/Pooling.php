<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pooling extends Model
{
    use HasFactory;

    public function transactions()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id', 'id');
    }
}
