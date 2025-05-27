<?php

namespace App\Models\operations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\operations\RentalModel;
use App\Models\operations\CustomerModel;
use App\Models\User;

class ContactModel extends Model
{
    protected $table = 'tbl_contact';
    protected $fillable = [
        'customerID', 
        'pre_contactType', 
        'pre_contactDetail',
        'contactType', 
        'contactDetail',
        'is_Active',
        'created_at',
        'updated_at',
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

    use HasFactory;


}
