<?php

namespace App\Exports;
use App\Models\operations\ContactModel;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class Exports implements FromCollection, WithHeadings
{
    use Exportable;
    
    public function collection()
    {
        $customers = DB::table('tbl_contact')
        ->join('tbl_customer', 'tbl_customer.customerID', 'tbl_contact.customerID')
        ->select(
            'tbl_customer.CustomerName', 
            'tbl_contact.contactType', 
            'tbl_contact.contactDetail',
            'tbl_customer.address',
            )
        ->get();

        return $customers;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Contact Type',
            'Phone',
            'Address'
        ];
    }
}
