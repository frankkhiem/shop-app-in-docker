<?php

namespace App\Imports;

use App\Models\InfoProduct;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Events\BeforeImport;
use App\Events\NewImportFileStatus;
use Maatwebsite\Excel\Concerns\WithEvents;

class InfoProductsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithEvents
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $currentRow = 0;
    private $totalRows = 0;
    private $percentageStart = 0;
    private $percentageFinish = 0;
    private $arrayRowsFail = [];

    function __construct($percentageStart, $percentageFinish)
    {
        $this->percentageStart = $percentageStart;
        $this->percentageFinish = $percentageFinish;
    }

    public function model(array $row)
    {
        ++$this->currentRow;
        
        if ( $this->currentRow % ceil($this->totalRows * 10 / 100) === 0 ) {
            $progressPercentage = round($this->getProgress() * ($this->percentageFinish - $this->percentageStart) / 100);
            broadcast( new NewImportFileStatus('executing', $this->percentageStart + $progressPercentage) );
        }

        if ( !isset($row['product_id']) || !isset($row['attribute']) || !isset($row['information']) ) {
            $infoRowFail = [];
            array_push($infoRowFail, $this->currentRow + 1, $row['product_id'], $row['attribute'], $row['information']);
            array_push($infoRowFail, 'Thất bại');
            $this->arrayRowsFail[] = $infoRowFail;
            return null;
        }

        return new InfoProduct([
            'product_id' => $row['product_id'],
            'attribute' => $row['attribute'],
            'information' => $row['information'],
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    // Hàm tính số hàng đã được nhập
    public function getRowCount(): int
    {
        return $this->currentRow;
    }

    public function getRowsFail() {
        return $this->arrayRowsFail;
    }

    // Hàm tính tiến độ hoàn thành nhập
    public function getProgress()
    {
        return round($this->currentRow / $this->totalRows * 100);
    }

    public function registerEvents(): array
    {
        return [
            // Trước khi nhập tính tổng số hàng dữ liệu của file
            BeforeImport::class => function (BeforeImport $event) {
                $rowsInWorksheet = $event->getReader()->getTotalRows();
                $this->totalRows = $rowsInWorksheet['Worksheet'] - 1;
            }
        ];
    }
}
