<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class storeProduct extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $fillable=['id','id_Store','Reference','Price','Qte'];
}
