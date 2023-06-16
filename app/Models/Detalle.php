<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    protected $table = 'detalles';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
