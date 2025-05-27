<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Kyslik\ColumnSortable\Sortable;

class MotorbikeInfo extends Model
{
    protected $table = 'tbl_motorInfor';
    protected $primaryKey = 'motorID';
    protected $fillable = [
        'motorno', 
        'year',
        'plateNo', 
        'engineNo', 
        'chassisNo', 
        'motorColor', 
        'motorType', 
        'motorModel', 
        'purchaseDate', 
        'motorStatus', 
        'compensationPrice', 
        'totalPurchasePrice',
        'customerID',
        'is_Active',
        'userID'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }

    use HasFactory, Sortable;
}
