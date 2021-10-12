<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Storage;
use App\Events\NewImportFileStatus;

class ProductsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    // đường dẫn tới thư mục giải nén từ file zip chứa thư mục ảnh của sản phẩm
    private $pathDirImages;
    private $percentageStart = 0;
    private $percentageFinish = 0;
    private $totalRows = 0;
    private $currentRow = 0;
    private $arrayRowsFail = [];

    function __construct( $path, $percentageStart, $percentageFinish )
    {
        $this->pathDirImages = $path;
        $this->percentageStart = $percentageStart;
        $this->percentageFinish = $percentageFinish;
    }

    public function collection(Collection $rows)
    {
        $this->totalRows = count($rows);

        foreach ( $rows as $row ) {
            // sleep(1);
            ++$this->currentRow;

            if ( !$row['id'] || !$row['category_id'] || !$row['name'] || !$row['short_desc'] || 
                !$row['full_desc'] || !$row['price'] || !$row['status_product_id'] ) 
            {
                $infoRowFail = [];
                array_push($infoRowFail, $this->currentRow + 1, $row['id'], 'Thất bại');

                if ( !$row['id'] && !$row['category_id'] && !$row['name'] && !$row['short_desc'] &&
                    !$row['full_desc'] && !$row['price'] && !$row['status_product_id'] ) {
                    array_push($infoRowFail, '1', 'Hàng không có dữ liệu');
                } else {
                    array_push($infoRowFail, '2', 'Thiếu dữ liệu yêu cầu cần có');
                }
                $this->arrayRowsFail[] = $infoRowFail;
                continue;
            }

            $directories = Storage::allDirectories($this->pathDirImages);
            $regex = "/\/{$row['id']}$/";
            $dirImagesProduct = '';
            foreach ( $directories as $dir ) {
                if ( preg_match($regex, $dir) ) {
                    $dirImagesProduct = $dir;
                    break;
                }
            }
            
            // định dạng lại thumnail và imagesPath sau mỗi lần lặp row
            $imagesPath = [];
            $thumbnail = "";

            if ( $dirImagesProduct ) {
                $images = File::files( storage_path('app\\'.$dirImagesProduct) );

                if ( sizeof($images) > 0 ) {
                    foreach ( $images as  $image ) {
                        // di chuyển các ảnh sản phẩm vào folder root public
                        File::move( $image->getRealPath(), public_path( 'uploads/imagesProduct/'.$image->getFilename() ) );
                        $imagesPath[] = $image->getFilename();
                    }
                    $thumbnail = $images[0]->getFilename();
                }
            }

            Product::updateOrCreate(
                [ 
                    'id' => $row['id'] 
                ],
                [
                    'category_id' => $row['category_id'],
                    'name' => $row['name'],
                    'thumbnail' => $thumbnail,
                    'image' => json_encode($imagesPath),
                    'short_desc' => $row['short_desc'],
                    'full_desc' => $row['full_desc'],
                    'price' => $row['price'],
                    'status_product_id' => $row['status_product_id'],
                    'star' => $row['star'],
                ]
            );

            $infoRowSuccess = [];
            array_push($infoRowSuccess, $this->currentRow + 1, $row['id'], 'Thành công');
            $this->arrayRowsFail[] = $infoRowSuccess;

            if ( $this->currentRow % ceil($this->totalRows * 10 / 100) === 0 ) {
                $progressPercentage = round($this->getProgress() * ($this->percentageFinish - $this->percentageStart) / 100);
                broadcast( new NewImportFileStatus('executing', $this->percentageStart + $progressPercentage) );
            }

        }
    }

    public function getProgress() {
        return round($this->currentRow / $this->totalRows * 100);
    }

    public function getRowCount() {
        return $this->totalRows;
    }

    public function getRowsFail() {
        return $this->arrayRowsFail;
    }
}
