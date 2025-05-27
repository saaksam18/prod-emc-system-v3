<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountriesModel extends Model
{
    protected $table = 'countries';
    protected $fillable = [
        'num_code', 
        'alpha_2_code', 
        'alpha_3_code', 
        'en_short_name', 
        'nationality', 
        'alpha_2_code', 
        'alpha_3_code'
    ];

    use HasFactory;
}
