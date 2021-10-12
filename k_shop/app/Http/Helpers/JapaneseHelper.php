<?php

namespace App\Http\Helpers;
use App\Http\Services\SortJapaneseService;

class JapaneseHelper {
  public static function nameSlice(String $name) {
    $name = strtolower( $name );
    $arrayChars = str_split( $name );
    $result = [];
    $nameLength = count( $arrayChars );    
    $i = 0;
    while ( $i < $nameLength ) {
      $kana = $arrayChars[$i];
      if ($kana == 'n') {
        if ( ($i + 1 < $nameLength) && in_array($kana . $arrayChars[$i + 1], SortJapaneseService::$alphabet) ) {
          $kana .= $arrayChars[++$i];
        }
      } 
      else {
        if ($i + 1 < $nameLength && $kana == $arrayChars[$i + 1]) {
          $kana = 'TSU';
        } 
        else {          
          while ( !in_array($kana, SortJapaneseService::$alphabet) ) {
            $i++;
            if ($i >= $nameLength) break;
            $kana = $kana . $arrayChars[$i];
          }
        }
      }
      $result[] = $kana;
      $i++;
    }
    return $result;
  }

  public static function nameEncrypt(String $name) {
    if ( $name === '' ) return '';
    $nameSlice = self::nameSlice($name);
    $nameCode = '';
    foreach ( $nameSlice as $char ) {
      if ( array_key_exists($char, SortJapaneseService::$mappingAlphabet) ) {
        $nameCode .= SortJapaneseService::$mappingAlphabet[$char];
      } else {
        $nameCode .= "a0$char";
      }
    }
    return $nameCode ;
  }
}
