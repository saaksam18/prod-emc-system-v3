<?php

namespace App\Models\operations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

use App\Models\User;
use App\Models\operations\CustomerModel;
use App\Models\MotorbikeInfo;

class ExchangeMotorModel extends Model
{
    use HasFactory, Sortable;

    protected $table = 'tbl_exchange_motor';
    protected $primaryKey = 'motoExchangeID';
    protected $fillable = [
        'customerID', 
        'preMotoID', 
        'currMotorID', 
        'comment',
        'created_at',
        'updated_at',
        'staff_id',
        'userID'
    ];
    
    public function customer()
    {
        return $this->belongsTo(CustomerModel::class, 'customerID', 'customerID');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }
}

