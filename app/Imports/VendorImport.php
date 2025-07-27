<?php

namespace App\Imports;

use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class VendorImport implements ToModel, WithValidation, WithHeadingRow
{

    public function model(array $row)
    {
        $vendor = new Vendor([
            'name' => $row['name'],
        ]);
        
        // Save the vendor first to get the ID
        $vendor->save();
        
        $users = [
            'name' => 'Vendor ' . $vendor->name,
            'email' => str_replace(' ', '', strtolower($vendor->name)) . '@gmail.com',
            'password' => Hash::make('pln#573*'),
            'role_id' => 2, // Assuming 2 is vendor role
            'vendor_id' => $vendor->id,
        ];

        User::create($users);
        
        return $vendor;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:vendors,name',
        ];
    }
    
    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'name.required' => 'Nama vendor wajib diisi',
            'name.string' => 'Nama vendor harus berupa teks',
            'name.max' => 'Nama vendor maksimal 255 karakter',
            'name.unique' => 'Nama vendor sudah terdaftar',
        ];
    }
}
