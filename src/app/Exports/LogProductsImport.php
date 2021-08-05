<?php

namespace App\Exports;

use App\Exports\Sheets\InfoProductsImport;
use App\Exports\Sheets\ProductsImport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class LogProductsImport implements WithMultipleSheets
{
    protected $logProducts;
    protected $logInfoProducts;

    public function __construct($logProductsImport, $logInfoProductsImport)
    {
        $this->logProducts = $logProductsImport;
        $this->logInfoProducts = $logInfoProductsImport;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new ProductsImport($this->logProducts);
        $sheets[] = new InfoProductsImport($this->logInfoProducts);
        return $sheets;
    }
}
