<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MusicaTwo extends Model
{
    use HasFactory;
    protected $fillable=['tema','genero','descripcion','duracion','imagen','audio'];

}
