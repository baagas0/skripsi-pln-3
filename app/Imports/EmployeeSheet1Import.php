<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\User;
use App\Models\Employee;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Validator;

class EmployeeSheet1Import implements ToCollection, WithHeadingRow, WithChunkReading
{
    // Default values for user creation
    private const DEFAULT_ROLE_ID = 5; // employee role
    private const DEFAULT_PASSWORD = 'pln#573*';
    private $units = [];
    private $areas = [];
    private $employees = [];
    private $users = [];
    private $pass;
    private $errors = [];
    private $successCount = 0;

    public function __construct()
    {
        // Cache all units and areas at the beginning
        $this->units = Unit::all()->keyBy('name');
        $this->areas = Area::with('unit')->get();
        $this->employees = Employee::select('email', 'nip')->get()->keyBy('nip');
        $this->users = User::select('email')->get()->keyBy('email');
        $this->pass = Hash::make(self::DEFAULT_PASSWORD);
    }

    public function chunkSize(): int
    {
        return 500; // Read 100 rows at a time
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        
        try {
            $employeeBatch = [];
            $userBatch = [];
            $rowNumber = 1; // Track row number for error reporting

            foreach ($rows as $row) {
                $rowNumber++;
                
                try {
                    // Validate row data
                    $this->validateRow($row->toArray(), $rowNumber);
                    
                    // Process the row
                    $processedData = $this->processRow($row->toArray(), $rowNumber);
                    
                    $userBatch[] = $processedData['user'];
                    $employeeBatch[] = $processedData['employee'];
                    
                } catch (\Exception $e) {
                    // If any row fails, rollback and throw exception
                    DB::rollBack();
                    throw new \Exception("Row {$rowNumber}: " . $e->getMessage());
                }
            }

            // Insert all users first
            if (!empty($userBatch)) {
                User::insert($userBatch);
            }

            // Insert all employees
            if (!empty($employeeBatch)) {
                Employee::insert($employeeBatch);
            }

            $this->successCount = count($employeeBatch);
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function validateRow(array $row, int $rowNumber)
    {
        $rules = [
            'nip' => 'required',
            'nama' => 'required',
            'jabatan' => 'required',
            'kode_unit_area' => 'required|string',
            'email' => 'required|email',
            'tanggal_lahir' => 'required',
        ];

        $validator = Validator::make($row, $rules);
        
        if ($validator->fails()) {
            $errors = implode(', ', $validator->errors()->all());
            throw new \Exception("Validation failed: {$errors}");
        }

        // Check for duplicate NIP
        if ($this->employees->has(trim($row['nip']))) {
            throw new \Exception("NIP {$row['nip']} already exists in database");
        }

        // Check for duplicate email in database
        if ($this->users->has(trim($row['email']))) {
            throw new \Exception("Email {$row['email']} already exists in database");
        }

        // Check for duplicate email in current batch
        static $currentBatchEmails = [];
        if (in_array(trim($row['email']), $currentBatchEmails)) {
            throw new \Exception("Duplicate email {$row['email']} found in import file");
        }
        $currentBatchEmails[] = trim($row['email']);

        // Check for duplicate NIP in current batch
        static $currentBatchNips = [];
        if (in_array(trim($row['nip']), $currentBatchNips)) {
            throw new \Exception("Duplicate NIP {$row['nip']} found in import file");
        }
        $currentBatchNips[] = trim($row['nip']);
    }

    private function processRow(array $row, int $rowNumber)
    {
        // Split unit and area names
        if (!str_contains($row['kode_unit_area'], ' | ')) {
            throw new \Exception("Invalid unit area format. Expected 'Unit | Area' format");
        }

        [$unitName, $areaName] = explode(' | ', $row['kode_unit_area']);
        
        // Find unit
        $unit = $this->units->get(trim($unitName));
        if (!$unit) {
            throw new \Exception("Unit '{$unitName}' not found");
        }

        // Find area
        $area = $this->areas->firstWhere(function ($area) use ($areaName, $unit) {
            return $area->name === trim($areaName) && $area->unit_id === $unit->id;
        });

        if (!$area) {
            throw new \Exception("Area '{$areaName}' not found in unit '{$unitName}'");
        }

        // Handle date conversion
        $birthDate = $this->transformDate($row['tanggal_lahir']);

        $now = now();
        
        return [
            'user' => [
                'name' => trim($row['nama']),
                'email' => trim($row['email']),
                'password' => $this->pass,
                'role_id' => self::DEFAULT_ROLE_ID,
                'unit_id' => $unit->id,
                'area_id' => $area->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'employee' => [
                'nip' => trim($row['nip']),
                'name' => trim($row['nama']),
                'position' => trim($row['jabatan']),
                'unit_id' => $unit->id,
                'area_id' => $area->id,
                'email' => trim($row['email']),
                'birth_date' => $birthDate,
                'password' => $this->pass,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ];
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
            throw new \Exception("Invalid date format for '{$value}'. Please use YYYY-MM-DD format or valid Excel date");
        }

        throw new \Exception("Invalid date format for '{$value}'. Please use YYYY-MM-DD format or valid Excel date");
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}