<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class CallCenterManager extends Authenticatable
{
     protected $table = 'call_center_managers';
}
