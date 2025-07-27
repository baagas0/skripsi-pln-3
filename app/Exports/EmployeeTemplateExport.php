<?php

namespace App\Exports;

use App\Exports\Sheets\AreasMasterSheet;
use App\Exports\Sheets\EmployeeTemplateSheet;
use App\Exports\Sheets\UnitsMasterSheet;
use App\Models\Unit;
use App\Models\Area;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EmployeeTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Template' => new EmployeeTemplateSheet(),
            'Master Units' => new UnitsMasterSheet(),
        ];
    }
}