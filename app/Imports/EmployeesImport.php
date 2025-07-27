<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EmployeesImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new EmployeeSheet1Import(),
        ];
    }
    
}