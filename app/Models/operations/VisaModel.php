<?php

namespace App\Models\operations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use App\Models\operations\CustomerModel;
use App\Models\User;

class VisaModel extends Model
{
    protected $table = 'tbl_visa';
    protected $primaryKey = 'visaID';
    protected $fillable = [
        'customerID', 
        'amount', 
        'visaType', 
        'expireDate', 
        'remindDate',
        'is_Active',
        'remindDate',
        'staff_id',
        'userID',
        'created_at',
        'updated_at'

    ];
    
    public function customer()
    {
        return $this->belongsTo(CustomerModel::class, 'customerID', 'customerID');
    }   
     
    public function contact()
    {
        return $this->belongsTo(ContactModel::class, 'customerID', 'customerID');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }

    use HasFactory, Sortable;
}
