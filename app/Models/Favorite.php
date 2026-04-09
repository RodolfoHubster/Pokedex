<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = ['user_id', 'pokemon_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
