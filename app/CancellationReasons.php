<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class CancellationReasons extends Authenticatable
{
     protected $table = 'cancellation_reasons';
}
