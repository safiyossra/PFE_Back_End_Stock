<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $fillable=['id','id_Store','id_delivery'.'id_vehicule','id_TypePanne','DateMvt','Reference','NumBon','Qte','depot','Tva','Price','observation','Kilometrage','Extra','idCiterne'];
}



