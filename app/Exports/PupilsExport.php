<?php

namespace App\Exports;

use App\Models\Receipts\Pupil;
// use Maatwebsite\Excel\Concerns\Exportable;
// use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use DB; 
use App\Models\Receipts\Invoice; 

class PupilsExport implements FromQuery
{
    // use Exportable;

    public function __construct()
    {
        // $this->sex = $cl;
    }

    public function query()
    {
        // $invoice = Invoice::findOrFail(1);
        // $v=$invoice->products();
        // $v = Pupil::all();
        // pupils and their balances in a record
        $records = [];
        // get all invoices 
          $invoices = Invoice::all();
          // for each invoice
          foreach ($invoices as $invoice) {
              // get balance 
              $balance = $invoice->products()->sum('balance');
              // get pupil
              $pupil = Pupil::findOrFail($invoice->id);
              // one record
              $record = ["name"=>$pupil->name,"surname"=>$pupil->surname,"grade"=>$pupil->grade,"class"=>$pupil->class,"balance"=>$balance];
              // push to records array
              array_push($records,$record);
            }
            return $records;
            // dump and die $records
        //   dd($records);
    }

    // public function headings(): array
    // {
    //     return [
    //         'name',
    //         'surname',
    //         'grade ',
    //         'class',
    //         'dob',
    //         'deleted_at',
    //         'created_at',
    //         'updated_at',
    //     ];
    // }
}
