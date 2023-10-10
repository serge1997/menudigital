<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealType extends Model
{
    use HasFactory;

    protected $table = 'menu_mealtype';
    protected $fillable = [
        'desc_type',
        'foto_type',
    ];
}
