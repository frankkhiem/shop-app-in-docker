<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LogSortJPNames implements FromCollection, WithHeadings
{
    protected $nameSorted;

    public function __construct($nameSorted)
    {
        $this->nameSorted = $nameSorted;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->nameSorted;
    }

    public function headings(): array
    {
        return [
            'Name', 'Slice', 'Encrypt',
        ];
    }
}
