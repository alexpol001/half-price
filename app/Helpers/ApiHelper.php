<?php
namespace App\Helpers;

class ApiHelper {
    public static function createFilter($model, $filter = []) {
        $result = [];
        foreach ($filter as $key => $item) {
            $result[] = "filter[$key]=".$model->$item;
        }
        return implode('&', $result);
    }

    public static function queryFilter($filter = []) {
        $result = [];
        foreach ($filter as $key => $conditions) {
            $result[$key] = [];
            foreach ($conditions as $operator => $sources) {
                $result[$key][$operator] = [];
                $attr = key($sources);
                foreach (reset($sources) as $source) {
                    $result[$key][$operator][] = isset($source->$attr) ? $source->$attr : null;
                }
            }
        }
        return $result;
    }

    public static function queryFilterGet($filter = []) {
        $filter = self::queryFilter($filter);
        $result = [];
        foreach ($filter as $key => $conditions) {
            $key = urlencode($key);
            $filterI = "filter[$key]";
            foreach ($conditions as $operator => $sources) {
                $operator = urlencode($operator);
                $filterJ = $filterI."[$operator]";
                foreach ($sources as $i => $value) {
                    $value = urlencode($value);
                    $result[] = $filterJ."[$i]=".$value;
                }
            }
        }
        return implode('&', $result);
    }
}
