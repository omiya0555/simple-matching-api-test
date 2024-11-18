<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIcon extends Model
{
    use HasFactory;

    protected $fillable = ['icon_path'];

    public function users()
    {
        return $this->hasMany(User::class, 'icon_id');
    }
}
