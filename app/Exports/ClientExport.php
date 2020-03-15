<?php

namespace App\Exports;

use App\Models\Api\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClientExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Client::all();
    }

    public function headings(): array
    {
        return [
            '#',
            'NAME',
            'SURNAME',
            'GRADE',
            'CLASS',
            'SEX',
            'D.O.B',
            'BALANCE',
            'DELETED AT',
            'CREATED AT',
            'UPDATED AT',
        ];
    }
}
