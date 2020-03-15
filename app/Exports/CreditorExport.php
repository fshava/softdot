<?php

namespace App\Exports;

use App\Models\Api\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CreditorExport implements FromCollection, WithHeadings, ShouldAutoSize
{
/**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Supplier::where('balance','>',0)->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'NAME',
            'PARTNER NUMBER',
            'ID NUMBER',
            'ADDRESS 1',
            'ADDRESS 2',
            'CONTACT NUMBER',
            'EMAIL',
            'BALANCE',
        ];
    }
}
