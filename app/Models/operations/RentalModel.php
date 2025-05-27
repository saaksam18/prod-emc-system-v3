<?php

namespace App\Models\operations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use App\Models\operations\CustomerModel;
use App\Models\operations\ContactModel;
use App\Models\operations\DepositModel;
use App\Models\MotorbikeInfo;
use App\Models\User;

class RentalModel extends Model
{
    use HasFactory, Sortable;

    protected $table = 'tbl_rental';
    protected $primaryKey = 'rentalID';
    protected $fillable = [
        'customerID',
        'motorID',
        'transactionType', 
        'rentalDay', 
        'returnDate', 
        'commingDate',
        'rentalPeriod',
        'price',
        'created_at',
        'updated_at',
        'staff_id',
        'userID',
        'is_Active'
    ]; 

    public function customer()
    {
        return $this->belongsTo(CustomerModel::class, 'customerID', 'customerID');
    }
    
    public function contact()
    {
        return $this->belongsTo(ContactModel::class, 'customerID', 'customerID');
    }
    
    public function deposit()
    {
        return $this->belongsTo(DepositModel::class, 'customerID', 'customerID');
    }

    public function motorInfor()
    {
        return $this->belongsTo(MotorbikeInfo::class, 'motorID', 'motorID');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }
}
