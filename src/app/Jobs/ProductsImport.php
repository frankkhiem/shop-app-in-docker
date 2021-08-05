<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport as ImportProducts;
use App\Imports\InfoProductsImport;
use ZipArchive;
use App\Events\NewImportFileStatus;
use App\Events\ResultProductsImport;
use App\Exports\LogProductsImport;
use Exception;
use stdClass;
use Throwable;

use function PHPSTORM_META\map;

class ProductsImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $tries = 1;

    protected $pathZipFile;


    public function __construct($pathZip)
    {
        $this->pathZipFile = $pathZip;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(1);
        $zip = new ZipArchive();
        if ( $zip->open( storage_path('app\\'.$this->pathZipFile) ) ) {
            $zip->extractTo( storage_path('app\\temp\\'. File::name($this->pathZipFile)) );
            $zip->close();
            
            // Đường dẫn tương đối tới thư mục giải nén
            $pathDirExtract = 'temp\\'.File::name($this->pathZipFile);
        } else {
            echo('Extract fail!!!!!!');
        }
        broadcast( new NewImportFileStatus('executing', 10) );

        $productsData = ""; 
        $infoProductsData = "";
        $filesInDir = Storage::files( $pathDirExtract );
        foreach ( $filesInDir as $file ) {
            if ( File::name($file) === "products_data" ) $productsData = $file;
            if ( File::name($file) === "information_products_data" ) $infoProductsData = $file;
        }
        broadcast( new NewImportFileStatus('executing', 20) );

        // Nếu không tồn tại 2 tệp thỏa mãn thì fail job
        if ( !$productsData || !$infoProductsData ) {
            Storage::deleteDirectory('temp');
            throw new Exception("Không tồn tại file dữ liệu đúng chuẩn. Thực hiện nhập thất bại. Vui lòng thử lại!");
        }

        $productsImport = new ImportProducts( $pathDirExtract, 20, 70 );
        Excel::import($productsImport, $productsData);

        $infoProductsImport = new InfoProductsImport(70, 100);
        Excel::import($infoProductsImport, $infoProductsData);

        Storage::deleteDirectory('temp');
        
        // Object mô tả kết quả xử lý sau khi import 2 file dữ liệu
        $description = new stdClass;

        $description->productsImport = new stdClass;
        $description->productsImport->totalRowsReaded = $productsImport->getRowCount();
        $description->productsImport->arrayRowsFail = $productsImport->getRowsFail();

        $description->infoProductsImport = new stdClass;
        $description->infoProductsImport->totalRowsReaded = $infoProductsImport->getRowCount();
        $description->infoProductsImport->arrayRowsFail = $infoProductsImport->getRowsFail();
        
        // Lưu file log vào storage
        date_default_timezone_set("Asia/Bangkok");
        $now = date("His_d-m-Y");
        $logFile = "log_import/log_products_import_". $now. ".xlsx";
        Excel::store(new LogProductsImport($productsImport->getRowsFail(), $infoProductsImport->getRowsFail()), $logFile);
        $description->pathLogFile = $logFile;

        // sleep(1);
        broadcast( new ResultProductsImport('finished', 'success', $description) );
    }

    public function failed(Throwable $exception = null)
    {
        broadcast( new ResultProductsImport('failed', $exception->getMessage()) );
    }
}
