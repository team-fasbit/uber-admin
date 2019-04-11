<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TronWallet extends Model
{
    protected $table = 'users_tron_wallet';
    
    public function getTableName()
    {
        return $this->table;
    }

    public static function tablename()
    {
        return 'users_tron_wallet';
    }

}