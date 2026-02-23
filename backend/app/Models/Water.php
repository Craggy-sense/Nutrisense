<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Water extends Model
{
    protected $fillable = ['user_id', 'glasses', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
