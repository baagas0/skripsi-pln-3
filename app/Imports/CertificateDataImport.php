<?php

namespace App\Imports;

use App\Models\Certificate;
use App\Models\DiklatParticipant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CertificateDataImport implements ToModel, WithHeadingRow, WithValidation, WithStartRow
{
    public function model(array $row)
    {
        // dd($row);
        $participant = DiklatParticipant::findOrFail($row['participant_id']);

        // return Certificate::updateOrCreate(
        //     [
        //         'diklat_id' => $participant->diklat_id,
        //         'employee_id' => $participant->employee_id,
        //     ],
        //     [
        //         'vendor_id' => $participant->diklat->vendor_id,
        //         'fungsi' => $row['function'],
        //         'certificate_number' => $row['certificate_number'],
        //         'certificate_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['certificate_date']),
        //         'certificate_expire' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['certificate_expire'])
        //     ]
        // );
        return Certificate::create([
            'diklat_id' => $participant->diklat_id,
            'employee_id' => $participant->employee_id,
            'vendor_id' => $participant->diklat->vendor_id,
            'fungsi' => $row['function'],
            'certificate_number' => $row['certificate_number'],
            'certificate_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['certificate_date']),
            'certificate_expire' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['certificate_expire']),
            'certificate_path' => null,
        ]);
    }

    public function rules(): array
    {
        return [
            'participant_id' => 'required|exists:diklat_participants,id',
            'function' => 'required',
            'certificate_number' => 'required|unique:certificates,certificate_number',
            'certificate_date' => 'required',
            'certificate_expire' => 'required',
        ];
    }

    public function startRow(): int
    {
        return 5;
    }

    public function headingRow(): int
    {
        return 4;
    }
}