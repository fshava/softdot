<?php

namespace App\Imports;

use App\Models\Receipts\Pupil;
use Maatwebsite\Excel\Concerns\ToModel;

class PupilsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Pupil([
            'name'     => $row[0],
            'surname'    => $row[1], 
            'grade' => $row[2],
            'sex' => $row[3],
        ]);
    }
}
