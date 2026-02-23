<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $fillable = ['user_id', 'name', 'cal', 'pro', 'type', 'time', 'date', 'score'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
