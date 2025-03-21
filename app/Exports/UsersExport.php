<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class UsersExport implements FromCollection, WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::all();
    }
    public function headings(): array
    {
        return [
            ['User Report'],
            ['ID', 'Name', 'Email', 'Phone Number', 'Status', 'Created At', 'Edited At', 'Deleted At']
        ];
    }
    public function title(): string
    {
        return 'Users Data';
    }
}
