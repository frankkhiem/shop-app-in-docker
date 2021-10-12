<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductsImport implements FromArray, WithHeadings, WithTitle, WithStrictNullComparison
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
            '# Row Order', 'id', 'Status Import', 'Error code', 'Error name'
        ];
    }

    public function title(): string
    {
        return 'Log Products Import';
    }
}
