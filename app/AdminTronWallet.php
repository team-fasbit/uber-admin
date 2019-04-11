<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminTronWallet extends Model
{
    protected $table = 'admin_tron_wallet';
    
    public function getTableName()
    {
        return $this->table;
    }

    public static function tablename()
    {
        return 'admin_tron_wallet';
    }


    public function generatedOn($timezone)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->setTimezone($timezone)->format('d M, Y');
    }

}