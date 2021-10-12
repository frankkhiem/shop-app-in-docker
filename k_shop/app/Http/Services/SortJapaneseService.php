<?php

namespace App\Http\Services;
use App\User;
use Illuminate\Support\Collection;
use stdClass;

class SortJapaneseService {
  public static $alphabet = [
    'a', 'i', 'u', 'e', 'o',
    'ka', 'kya', 'kyu', 'kyo', 'ki', 'ku', 'ke', 'ko',
    'sa', 'sha', 'shu', 'sho', 'shi', 'su', 'se', 'so',
    'ta', 'cha', 'chu', 'cho', 'chi', 'TSU', 'tsu', 'te', 'to',
    'na', 'nya', 'nyu', 'nyo', 'ni', 'nu', 'ne', 'no',
    'ha', 'hya', 'hyu', 'hyo', 'hi', 'fu', 'he', 'ho',
    'ma', 'mya', 'myu', 'myo', 'mi', 'mu', 'me', 'mo',
    'ya', 'yu', 'yo',
    'ra', 'rya', 'ryu', 'ryo', 'ri', 'ru', 're', 'ro',
    'wa', 'wo', 'n',
    'ga', 'gya', 'gyu', 'gyo', 'gi', 'gu', 'ge', 'go',
    'za', 'ja', 'ju', 'jo', 'ji', 'zu', 'ze', 'zo',
    'da', 'di', 'du', 'de', 'do',
    'ba', 'bya', 'byu', 'byo', 'bi', 'bu', 'be', 'bo',
    'pa', 'pya', 'pyu', 'pyo', 'pi', 'pu', 'pe', 'po',
  ];

  // Mảng ánh xạ âm tiết sang mã
  public static $mappingAlphabet = [
    'a' => 'b1', 'i' => 'b2', 'u' => 'b3', 'e' => 'b4', 'o' => 'b5',
    'ka' => 'c1', 'kya' => 'c2', 'kyu' => 'c3', 'kyo' => 'c4', 'ki' => 'c5', 'ku' => 'c6', 'ke' => 'c7', 'ko' => 'c8',
    'sa' => 'd1', 'sha' => 'd2', 'shu' => 'd3', 'sho' => 'd4', 'shi' => 'd5', 'su' => 'd6', 'se' => 'd7', 'so' => 'd8',
    'ta' => 'e1', 'cha' => 'e2', 'chu' => 'e3', 'cho' => 'e4', 'chi' => 'e5', 'TSU' => 'e6', 'tsu' => 'e7', 'te' => 'e8', 'to' => 'e9',
    'na' => 'f1', 'nya' => 'f2', 'nyu' => 'f3', 'nyo' => 'f4', 'ni' => 'f5', 'nu' => 'f6', 'ne' => 'f7', 'no' => 'f8',
    'ha' => 'g1', 'hya' => 'g2', 'hyu' => 'g3', 'hyo' => 'g4', 'hi' => 'g5', 'fu' => 'g6', 'he' => 'g7', 'ho' => 'g8',
    'ma' => 'h1', 'mya' => 'h2', 'myu' => 'h3', 'myo' => 'h4', 'mi' => 'h5', 'mu' => 'h6', 'me' => 'h7', 'mo' => 'h8',
    'ya' => 'i1', 'yu' => 'i2', 'yo' => 'i3',
    'ra' => 'j1', 'rya' => 'j2', 'ryu' => 'j3', 'ryo' => 'j4', 'ri' => 'j5', 'ru' => 'j6', 're' => 'j7', 'ro' => 'j8',
    'wa' => 'k1', 'wo' => 'k2', 'n' => 'k3',
    'ga' => 'l1', 'gya' => 'l2', 'gyu' => 'l3', 'gyo' => 'l4', 'gi' => 'l5', 'gu' => 'l6', 'ge' => 'l7', 'go' => 'l8',
    'za' => 'm1', 'ja' => 'm2', 'ju' => 'm3', 'jo' => 'm4', 'ji' => 'm5', 'zu' => 'm6', 'ze' => 'm7', 'zo' => 'm8',
    'da' => 'n1', 'di' => 'n2', 'du' => 'n3', 'de' => 'n4', 'do' => 'n5',
    'ba' => 'o1', 'bya' => 'o2', 'byu' => 'o3', 'byo' => 'o4', 'bi' => 'o5', 'bu' => 'o6', 'be' => 'o7', 'bo' => 'o8',
    'pa' => 'p1', 'pya' => 'p2', 'pyu' => 'p3', 'pyo' => 'p4', 'pi' => 'p5', 'pu' => 'p6', 'pe' => 'p7', 'po' => 'p8',
  ];


  // Hàm tách tên Furigana thành mảng từng âm tiết
  static function nameSlice( String $name ) {
    // chuyen cac ky tu viet hoa thanh viet thuong
    $name = strtolower( $name );

    // tach tung ky tu alphabet trong ten
    $arrayChars = str_split( $name );

    // Khai báo mảng lưu kết quả tách tên tiếng Nhật thành từng âm tiết
    $result = [];

    $nameLength = count( $arrayChars );
    
    $i = 0;
    while ( $i < $nameLength ) {
      // Khai báo kana là âm tiết tiếng nhật
      $kana = $arrayChars[$i];

      if ($kana == 'n') {
        if ( ($i + 1 < $nameLength) && in_array($kana . $arrayChars[$i + 1], self::$alphabet) ) {
          $kana .= $arrayChars[++$i];
        }
      } 
      else {
        if ($i + 1 < $nameLength && $kana == $arrayChars[$i + 1]) {
          $kana = 'TSU';
        } 
        else {
          // Nếu kana không thuộc bảng chữ cái thì kana nối thêm ký tự tiếp theo
          while ( !in_array($kana, self::$alphabet) ) {
            $i++;
            // Nếu là nối đến ký tự cuối cùng thì dừng
            if ($i >= $nameLength) break;
            // Nếu chưa phải ký tự cuối cùng thì nối tiếp vào kana
            $kana = $kana . $arrayChars[$i];
          }
        }
      }

      $result[] = $kana;
      $i++;
    }
    return $result;
  }

  // Hàm mã hóa 1 tên tiếng Nhật !!! Hàm quan trọng để mã hóa tên rồi dùng chuỗi mã hóa orderBy trong query DB
  static function nameEncrypt( String $name ) {
    if ( $name === '' ) return '';
    $nameSlice = self::nameSlice($name);

    $nameCode = '';

    foreach ( $nameSlice as $char ) {
      if ( array_key_exists($char, self::$mappingAlphabet) ) {
        $nameCode .= self::$mappingAlphabet[$char];
      } else {
        $nameCode .= "a0$char";
      }
    }

    return $nameCode ;
  }

  // Hàm so sánh 2 âm tiết
  static function compare2Kana( $kana1, $kana2 ) {
    if ( in_array($kana1, self::$alphabet) ) {
      $index1 = array_search($kana1, self::$alphabet);
    } else {
      $index1 = -1;
    }

    if ( in_array($kana2, self::$alphabet) ) {
      $index2 = array_search($kana2, self::$alphabet);
    } else {
      $index2 = -1;
    }
    if ( $index1 == -1 && $index2 == -1 ) {
      return strcmp($kana1, $kana2);
    }
    return $index1 - $index2;
  }

  // Hàm so sánh 2 tên tiếng Nhật
  static function compare2Name( $name1, $name2 ) {
    if ( strtolower($name1) === strtolower($name2) ) return 0;

    $nameSlice1 = self::nameSlice($name1);
    $nameSlice2 = self::nameSlice($name2);

    $loops = count($nameSlice1) < count($nameSlice2) ? count($nameSlice1):count($nameSlice2);

    for ( $i = 0; $i < $loops; $i++ ) {
      if ( self::compare2Kana( $nameSlice1[$i],  $nameSlice2[$i]) === 0 ) {
        continue;
      } else if (self::compare2Kana( $nameSlice1[$i],  $nameSlice2[$i]) < 0) {
        return -1;
      } else if (self::compare2Kana( $nameSlice1[$i],  $nameSlice2[$i]) > 0) {
        return 1;
      }
    }

    return count($nameSlice1) - count($nameSlice2);
  }

  // So sánh 2 ojbect User dựa theo tên Furigana của họ
  static function compareFurigana2User( User $user1, User $user2 ) {
    $name1 = $user1['furigana'] === null ? '':$user1['furigana'];
    $name2 = $user2['furigana'] === null ? '':$user2['furigana'];

    return self::compare2Name($name1, $name2);
  }

  public static function sortNamesList( array $namesList, $mode = 'array' ) {
    usort( $namesList, array("App\Http\Services\SortJapaneseService", "compare2Name") );
    $result = array_map(function($name) {
      $row = new stdClass;
      $row->name = $name;
      $row->slice = implode(", ", self::nameSlice($name));
      $row->encrypt = self::nameEncrypt($name);
      return $row;
    }, $namesList);

    if ($mode == 'collection') {
      return collect($result);
    }

    return $namesList;
  }
}
