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
use Illuminate\Support\Facades\Hash;

class EmployeeSheet1Import implements ToModel, WithValidation, WithHeadingRow
{
    // Default values for user creation
    private const DEFAULT_ROLE_ID = 5; // employee role
    private const DEFAULT_PASSWORD = 'pln#573*';

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

    public function array(array $array)
    {
        dd($array);
    }

    public function model(array $row)
    {
        // Split unit and area names
        [$unitName, $areaName] = explode(' | ', $row['kode_unit_area']);
        
        // Find unit and area
        $unit = Unit::where('name', trim($unitName))->first();
        $area = Area::where('name', trim($areaName))
                    ->where('unit_id', $unit->id)
                    ->first();

        if (!$unit || !$area) {
            throw new \Exception("Invalid unit and area combination: {$row['kode_unit_area']}");
        }

        // Handle date conversion
        $birthDate = $this->transformDate($row['tanggal_lahir']);
        User::create([
            'name' => $row['nama'],
            'email' => $row['email'],
            'password' => Hash::make(self::DEFAULT_PASSWORD),
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
            'password' => Hash::make(self::DEFAULT_PASSWORD),
        ]);
    }

    public function rules(): array
    {
        return [];
        return [
            'nip' => 'required|unique:employees,nip',
            'nama' => 'required',
            'jabatan' => 'required',
            'kode_unit_area' => [
                'required',
                function($attribute, $value, $fail) {
                    [$unitName, $areaName] = explode(' | ', $value);
                    
                    // Find unit
                    $unit = Unit::where('name', trim($unitName))->first();
                    if (!$unit) {
                        $fail("Unit '{$unitName}' not found");
                        return;
                    }

                    // Check if area exists for this unit
                    $area = Area::where('name', trim($areaName))
                                ->where('unit_id', $unit->id)
                                ->first();
                    if (!$area) {
                        $fail("Area '{$areaName}' not found in unit '{$unitName}'");
                    }
                }
            ],
            'email' => 'required|email|unique:employees,email',
            'tanggal_lahir' => 'required',
        ];
    }
}
