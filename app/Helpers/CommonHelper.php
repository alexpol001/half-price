<?php
namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class CommonHelper {
    public static function plural_form($number, $after) {
        $cases = array(2,0,1,1,1,2);
        return $number.' '.$after[($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)]];
    }

    public static function translate($text)
    {
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
            'Ь' => '', 'Ы' => 'Y', 'Ъ' => '',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
            '«' => '-', '»' => '-', ' ' => '-'
        );
        return strtr($text, $converter);
    }

    public static function rmRec($path) {
        if (is_file($path)) return unlink($path);
        if (is_dir($path)) {
            foreach(scandir($path) as $p) if (($p!='.') && ($p!='..'))
                static::rmRec($path.DIRECTORY_SEPARATOR.$p);
            return rmdir($path);
        }
        return false;
    }

    public static function dateToTimeStamp($date) {
        if ($date) {
            $date = explode(' - ', $date);
            $time = isset($date[1]) ? $date[1] : null;
            $time = $time ? explode(':', $time) : null;
            $date = explode('/',$date[0]);
            if (count($time) == 2) {
                $date = Carbon::create($date[2], $date[1], $date[0], $time[0], $time[1], 0, 3);
            } else {
                $date = Carbon::createFromDate($date[2], $date[1], $date[0], 3);
            }
            return $date->timestamp;
        }
        return null;
    }

    public static function timeStampToDate($timeStamp, $format = 'd/m/Y - H:i') {
        if ($timeStamp) {
            $date = Carbon::createFromTimestamp($timeStamp);
            return $date->format($format);
        }
        return null;
    }

    public static function cropString($string, $length) {

        return $string ? Str::limit($string, $length) : null;
    }
}
