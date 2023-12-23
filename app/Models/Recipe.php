<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'ingredients', 'instructions', 'dietary_restrictions', 'image', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class); // Specifies that a Recipe belongs to a User
    }
}
