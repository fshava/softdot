<?php

namespace App\Exports;

use App\Models\Api\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExpenditureExport implements FromCollection, WithHeadings, ShouldAutoSize
{
/**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Payment::all();
    }

    public function headings(): array
    {
        return [
            'SUPPLIER',
            'PRODUCT',
            'CATEGORY',
            'AMOUNT',
        ];
    }
}
