<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CertificateImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new CertificateDataImport(),
        ];
    }
}