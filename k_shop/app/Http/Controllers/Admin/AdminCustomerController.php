<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LogSortJPNames;
use App\Http\Controllers\Controller;
use App\User;
use Collator;
use Illuminate\Http\Request;
use App\Http\Services\SortJapaneseService;
use App\Http\Helpers\JapaneseHelper;
use Maatwebsite\Excel\Facades\Excel;

class AdminCustomerController extends Controller
{
    //
    public function listCustomer(Request $request) {
        $keywork = $request->q;
        if( $keywork ) {
            $listCustomer = User::where('role_id', 1)
                                ->where(function($query) use ($keywork) {
                                    $query->where('name', 'like', '%'.$keywork.'%')
                                            ->orWhere('email', 'like', '%'.$keywork.'%');
                                })
                                ->orderBy('furigana_code')
                                ->orderBy('id')
                                ->paginate(10);
        }
        else {
            $listCustomer = User::where('role_id', 1)
                                ->orderBy('furigana_code')
                                ->orderBy('id')
                                ->paginate(10);
        }

        // dd( $listCustomer );
        // dd( JapaneseHelper::nameSlice('Ipai') );

        // $listCustomer = $listCustomer->sort( array("App\Http\Services\SortJapaneseService", "compareFurigana2User") );

        // Test ham sort ten tieng Nhat

        // Đầu vào là danh sách các tên tiếng Nhật
        // $namesList = [
        //     'Tamura', 'Akiyama', 'TaNakA', 'Yamanami', 'Shikichi', 
        //     'Fujimoto', 'Osadasu', 'Igarashi', 'Keiki', 'Aikawa',
        //     'Shiba', 'Hashimoto', 'Igarashi', 'Saburou', 'Kyou',
        //     'Goro', 'Sadao', 'Hiraku', 'Ichirou', 'Kouki',
        //     'Aki', 'Susumu', 'Aoi', 'Kenshin', 'Ryuu', 'Ipai',
        //     'Seiichi', 'Takahiro', 'Hotaka', 'Masaru', 'Yuki',
        //     'Ryouta', 'Akihiko', 'Shigeo', 'Hideki', 'Yuuki',
        //     'Yoshirou', 'Ryuunosuke', 'Takashi', 'Masashi', 'Iwao',
        //     'Shouta', 'Jurou', 'Takeshi', 'Shouta', 'Kyou',
        //     'Ryouta', 'Ryuunosuke', 'Zasshi', 'Kekkon', 'Ippai',
        // ];
        
        // // Gọi service sắp xếp danh sách các tên tiếng Nhật
        // dd( SortJapaneseService::sortNamesList( $namesList ) );

        // date_default_timezone_set("Asia/Bangkok");
        // $now = date("His_d-m-Y");
        // $logFile = "log_sort/log_sort_japanese_names". $now. ".csv";
        // Excel::store(new LogSortJPNames( SortJapaneseService::sortNamesList( $namesList ) ), $logFile);
        

        return view('admin.customer.adminCustomer',
                    [
                        'listCustomer' => $listCustomer,
                    ]);
    }


    public function listCustomerBanned(Request $request) {
        $keywork = $request->q;
        if( $keywork ) {
            $listCustomer = User::onlyTrashed()
                                    ->where('role_id', 1)
                                    ->where(function($query) use ($keywork) {
                                        $query->where('name', 'like', '%'.$keywork.'%')
                                                ->orWhere('email', 'like', '%'.$keywork.'%');
                                    })
                                    ->paginate(10);
        }
        else {
            $listCustomer = User::onlyTrashed()->where('role_id', 1)->paginate(10);
        }
        return view('admin.customer.adminCustomerBanned',
                    [
                        'listCustomer' => $listCustomer,
                    ]);
    }
    public function banCustomer($id) {
        User::findOrFail($id)->delete();
        return redirect()->route('listCustomer');
    }
    public function unbanCustomer($id) {
        User::withTrashed()
                ->where('id', $id)
                ->restore();
        return redirect()->route('listCustomerBanned');
    }
}
