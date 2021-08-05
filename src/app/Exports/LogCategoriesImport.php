<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LogCategoriesImport implements FromArray, WithHeadings
{
    protected $logRowsImport;

    public function __construct($logRowsImport)
    {
        $this->logRowsImport = $logRowsImport;
    }

    public function array(): array
    {
        return $this->logRowsImport;
    }

    public function headings(): array
    {
        return [
            '# Row Order', 'name', 'short_desc', 'full_desc','Status Import'
        ];
    }
}
