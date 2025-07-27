<?php

namespace App\Exports;

use App\Models\Diklat;
use App\Models\DiklatParticipant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CertificateTemplate implements FromCollection, WithHeadings, WithStyles, WithTitle, WithCustomStartCell
{
    protected $diklat_id;
    protected $diklat;

    public function __construct($diklat_id)
    {
        $this->diklat_id = $diklat_id;
        $this->diklat = Diklat::findOrFail($diklat_id);
    }

    public function collection()
    {
        return DiklatParticipant::where('diklat_id', $this->diklat_id)
            ->with(['employee', 'diklat.vendor'])
            ->get()
            ->map(function ($participant, $index) {
                return [
                    'no' => $index + 1,
                    'diklat_participant_id' => $participant->id,
                    'employee_name' => $participant->employee->name,
                    'vendor_name' => $participant->diklat->vendor->name,
                    'fungsi' => '',
                    'certificate_number' => '',
                    'certificate_date' => '',
                    'certificate_expire' => ''
                ];
            });
    }

    public function headings(): array
    {
        return [
            'No',
            'Participant ID',
            'Employee Name',
            'Vendor Name',
            'Function',
            'Certificate Number',
            'Certificate Date',
            'Certificate Expire'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Get the highest row number
        $highestRow = $sheet->getHighestRow();

        // Set header information
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A1', 'Letter Number: ' . $this->diklat->letter_number);
        $sheet->setCellValue('A2', 'Diklat: ' . $this->diklat->name);

        // Style for header info
        $sheet->getStyle('A1:A2')->getFont()->setBold(true);
        $sheet->getStyle('A1:H2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');

        // Style for table headers
        $sheet->getStyle('A4:H4')->getFont()->setBold(true);
        $sheet->getStyle('A4:H4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('BDD7EE');

        // Style for non-editable columns (No, Participant ID, Employee Name, Vendor Name)
        $sheet->getStyle('A5:D'.$highestRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFEB9C');

        // Auto-size columns
        foreach(range('A','H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [
            4 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Certificate Template';
    }

    public function startCell(): string
    {
        return 'A4';
    }
}