<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class InfoProductsImport implements FromArray, WithHeadings, WithTitle
{
    protected $logRowsImport;

    public function __construct($logRowsImport) {
        $this->logRowsImport = $logRowsImport;
    }

    public function array(): array
    {
        return $this->logRowsImport;
    }

    public function headings(): array
    {
        return [
            '# Row Order', 'product_id', 'attribute', 'information', 'Status Import'
        ];
    }

    public function title(): string
    {
        return 'Log Info Products Import Fail';
    }
}
