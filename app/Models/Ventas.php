<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    protected $table = 'ventas';
    protected $primaryKey = 'id';
    protected $fillable = ['cantidad', 'producto_id', 'cliente_id'];
    public $timestamps = false;
}
