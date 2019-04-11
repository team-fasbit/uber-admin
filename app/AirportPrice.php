<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AirportPrice extends Model
{
    public function airport_details() {
		return $this->belongsTo('App\AirportDetail');
	}

	public function location_details() {
		return $this->belongsTo('App\LocationDetail');
	}

	public function service_type() {
		return $this->belongsTo('App\ServiceType');
	}
}
