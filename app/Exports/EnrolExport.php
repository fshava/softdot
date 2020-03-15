<?php

namespace App\Exports;

use App\Models\Receipts\Pupil;
use Maatwebsite\Excel\Concerns\FromCollection;

class EnrolExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Pupil::all();
    }
}
