<?php

namespace App\Models\operations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use App\Models\operations\RentalModel;
use App\Models\operations\DepositModel;
use App\Models\operations\ContactModel;
use App\Models\User;

class CustomerModel extends Model
{
    protected $table = 'tbl_customer';
    protected $primaryKey = 'customerID';
    protected $fillable = [
        'CustomerName', 
        'gender', 
        'nationality',
        'address', 
        'comment',
        'created_at',
        'updated_at',
        'userID'
    ];

    public function deposit()
    {
        return $this->hasMany(DepositModel::class, 'customerID', 'customerID');
    }

    public function contact()
    {
        return $this->hasMany(ContactModel::class, 'customerID', 'customerID');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }

    use HasFactory, Sortable;
}
