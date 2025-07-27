<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\User;
use App\Models\Employee;
use App\Models\Unit;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class EmployeeSheet1Import implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    // Default values for user creation
    private const DEFAULT_ROLE_ID = 5; // employee role
    private const DEFAULT_PASSWORD = 'pln#573*';
    private $units = [];
    private $areas = [];
    private $employees = [];
    private $pass = 'ddefault';

    public function __construct()
    {
        // Cache all units and areas at the beginning
        $this->units = Unit::get();
        $this->areas = Area::with('unit')->get();
        $this->employees = Employee::select('email', 'nip')->get();
        $this->pass = Hash::make(self::DEFAULT_PASSWORD);
    }

    public function batchSize(): int
    {
        return 50; // Process 50 records at a time
    }

    public function chunkSize(): int
    {
        return 100; // Read 100 rows at a time
    }

    private function transformDate($value)
    {
        try {
            // If it's already a formatted date string
            if (strtotime($value)) {
                return Carbon::parse($value)->format('Y-m-d');
            }

            // If it's an Excel date number
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
        } catch (\Exception $e) {
            throw new \Exception("Invalid date format. Please use YYYY-MM-DD format.");
        }

        throw new \Exception("Invalid date format. Please use YYYY-MM-DD format.");
    }

    public function model(array $row)
    {
        // Split unit and area names
        [$unitName, $areaName] = explode(' | ', $row['kode_unit_area']);
        
        // Find unit and area
        // $unit = Unit::where('name', trim($unitName))->first();
        // $area = Area::where('name', trim($areaName))
        //             ->where('unit_id', $unit->id)
        //             ->first();

        // $unit = $this->units->get(trim($unitName));
        $unit = $this->units->where('name', trim($unitName))->first();
        // $areaKey = trim($unitName) . ' | ' . trim($areaName);
        // $area = $this->areas->get($areaKey)?->first();
        $area = $this->areas->firstWhere(function ($area) use ($areaName, $unit) {
            return $area->name === trim($areaName) && $area->unit_id === $unit->id;
        });

        if (!$unit || !$area) {
            throw new \Exception("Invalid unit and area combination: {$row['kode_unit_area']}");
        }

        $checkNip = $this->employees->firstWhere('nip', trim($row['nip']));
        if ($checkNip) {
            throw new \Exception("NIP {$row['nip']} already exists.");
        }
        $checkEmail = $this->employees->firstWhere('email', trim($row['email']));
        if ($checkEmail) {
            throw new \Exception("Email {$row['email']} already exists.");
        }

        // Handle date conversion
        $birthDate = $this->transformDate($row['tanggal_lahir']);
        User::create([
            'name' => $row['nama'],
            'email' => $row['email'],
            'password' => $this->pass,
            'role_id' => self::DEFAULT_ROLE_ID,
            'unit_id' => $unit->id,
            'area_id' => $area->id,
        ]);
        return new Employee([
            'nip' => $row['nip'],
            'name' => $row['nama'],
            'position' => $row['jabatan'],
            'unit_id' => $unit->id,
            'area_id' => $area->id,
            'email' => $row['email'],
            'birth_date' => $birthDate,
            'password' => $this->pass,
        ]);
    }

    public function rules(): array
    {
        return [
            'nip' => 'required',
            'nama' => 'required',
            'jabatan' => 'required',
            'kode_unit_area' => [
                'required',
                'string'
                // function($attribute, $value, $fail) {
                //     [$unitName, $areaName] = explode(' | ', $value);
                    
                //     // Find unit
                //     $unit = Unit::where('name', trim($unitName))->first();
                //     if (!$unit) {
                //         $fail("Unit '{$unitName}' not found");
                //         return;
                //     }

                //     // Check if area exists for this unit
                //     $area = Area::where('name', trim($areaName))
                //                 ->where('unit_id', $unit->id)
                //                 ->first();
                //     if (!$area) {
                //         $fail("Area '{$areaName}' not found in unit '{$unitName}'");
                //     }
                // }
            ],
            'email' => 'required|email',
            'tanggal_lahir' => 'required',
        ];
    }
}
