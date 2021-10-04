<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user', 'id');
    }


    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user', 'id');
    }

    public function pooling()
    {
        return $this->hasMany(Pooling::class, 'transaction_id','id');
    }
}
