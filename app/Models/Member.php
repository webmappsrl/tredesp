<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Card;

class Member extends Model
{
    use HasFactory;
    public function cards() {
        return $this->hasMany(Card::class);
    }
}
