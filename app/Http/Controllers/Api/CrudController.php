<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\CrudController as Crud;
use App\Models\UwtModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CrudController extends Crud
{
    protected static $prefixRoute = '/api';

    public function index(Request $request)
    {
        if (!$request->get('start')) {
            $request->merge(['start' => 0]);
        }
        if (!$request->get('length')) {
            $request->merge(['length' => 10]);
        }
        $data = $request->all();
//        var_dump($data);
//        die;
        $columns = isset($data['columns']) ? $data['columns'] : [];
        $relations = [];
        foreach ($columns as $column) {
            if (isset($column['name'])) {
                $relations[$column['data']] = explode('.', $column['name']);
                foreach ($relations as $key => $relation) {
                    if (count($relation) <= 1) {
                        unset($relations[$key]);
                    }
                }
            }
        }
        $model = static::$model::query();
        if ($exclude = $request->get('exclude')) {
            foreach ($exclude as $item) {
                if ($excludeModel = static::$model::find($item['id'])) {
                    $model = $model->whereNotIn('id', $excludeModel->exclude());
                }
            }
        }
        $filter = isset($data['filter']) ? $data['filter'] : [];
        foreach ($filter as $key => $conditions) {
            foreach ($conditions as $operator => $condition) {
                foreach ($condition as $value) {
                    $model = $model->where($key, $operator, $value);
                }
            }
        }
        if (static::$model::getInstance()->sortable) {
            $model = $model->orderBy('sort');
        }
        if (count($relations)) {
            foreach ($relations as $relation) {
                if ($relation[0] != 'mutator') {
                    $model = $model->with($relation[0]);
                }
            }
            $data = DataTables::eloquent($model);
            foreach ($relations as $key => $relation) {
                $data = $data->addColumn($key, function ($model) use ($relation) {
                    for ($i = 0; isset($relation[$i]); $i++) {
                        $attr = $relation[$i];
                        if ($attr != 'mutator') {
                            $model = (isset($model->$attr) ? $model->$attr : null);
                        }
                    }
                    return $model;
                });
            }
            return $data->toJSON();
        }
        return DataTables::eloquent($model)->toJson();
        /**
         * get first 2 elements local search field title
         * ?columns[0][data]=title&columns[0][search][value]=тест&start=0&length=2
         *
         * get all elements global search and order (asc|desc) field title
         * ?columns[0][data]=title&columns[0][name]=&columns[0][searchable]=true&search[value]=тест&order[0][column]=0&order[0][dir]=asc
         */

//        return Datatables::collection(static::$model::all())->make(true);
    }

//    public function index2(Request $request)
//    {
//        $model = static::$model;
//        $input = [
//            'draw' => $request->get('draw'),
//            'start' => $request->get('start'),
//            'length' => $request->get('length'),
//            'columns' => $request->get('columns'),
//            'search' => $request->get('search'),
//        ];
//
//        $items = $model::query();
//
//        $relations = [];
////        $searchData = [];
//        if (($columns = $input['columns'])) {
//            foreach ($columns as $column) {
//                $buf = explode('.', $column['data']);
//                if (count($buf) > 1) {
//                    $relations[] = $buf;
//                }
////                if (!isset($column['searchable']) || $column['searchable'] !== false) {
////                    $searchData['$co'
////                }
//            }
//
//            foreach ($relations as $relation) {
//                for ($i = 0; $i < count($relation) - 1; $i++) {
//                    $items = $items->with($relation[$i]);
//                }
//            }
//        }
////        return $relations;
//        if ($input['start'] && $input['length']) {
//            $items = $items->offset($input['start']);
//        }
//        $items = $items->limit($input['length'] ? $input['length'] : 10)->get();
//
//        foreach ($items as $item) {
//            foreach ($relations as $relation) {
//                $last = count($relation) - 1;
//                for ($i = 0; $i <= $last; $i++) {
//                    $attr = $relation[$i];
//                    if (!$item->$attr || !isset($item->$attr)) {
//                        $item->$attr = (($i == $last) ? '' : []);
//                    }
//                }
//            }
//        }
//
//        $response['draw'] = $input['draw'] ? $input['draw'] : 1;
//        $response['recordsTotal'] = $model::query()->count();
//        $response['recordsFiltered'] = $model::query()->count();
//        $response['data'] = $items;
//        $response['input'] = $input;
//        return $response;
//    }

    public function sort(Request $request) {
        $prevModel = static::$model::find($request->get('prev'));
        $nextModel = static::$model::find($request->get('next'));
        if (static::$model::getInstance()->sortable && ($prevModel || $nextModel)) {
            $model = $this->findModel($request->get('id'));
            if (!$prevModel) {
                $sort = $nextModel->sort - 1;
            } else if (!$nextModel) {
                $sort = $prevModel->sort + 1;
            } else {
                $sort = ($nextModel->sort + $prevModel->sort) / 2;
            }
            $model->update([
                'sort' => $sort,
            ]);
        } else {
            return Response::json([
                'status' => 'error',
                'message' => 'Sort access error!'
            ], 500);
        }
        return $prevModel;
    }
}
