<?php

namespace App\Http\Controllers;

use App\Models\UwtModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

abstract class CrudController extends Controller
{
    protected static $prefixRoute = '';

    /**
     * @var UwtModel $model
     */
    protected static $model;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->initModel();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->renderPage('index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @param $path
     * @param null $relation
     * @param null $id
     * @return \Illuminate\Http\Response
     */
    public function create($path, $relation = null, $id = null)
    {
        $model = static::$model::getInstance();

        return $this->renderPage('create', [
            'model' => $model,
            'relation' => $relation,
            'id' => $id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $path
     * @param null $relation
     * @param null $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, $path, $relation = null, $id = null)
    {
        /** @var UwtModel $model */
        $model = new static::$model();
        if ($relation && $id) {
            $request->merge([$relation.'_id' => $id]);
        }
        $requestData = $this->doValidate($request, $model);
        $model = $model->store($requestData);
        return $this->checkSubmitType($requestData, $model, $model);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($path, $id)
    {
        return $this->renderPage('index', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($path, $id)
    {
        return $this->renderPage('update', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $path, $id)
    {
        $model = $this->findModel($id);
        $requestData = $this->doValidate($request, $model);
        return $this->checkSubmitType($requestData, $model, $model->update($requestData));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete($id)
    {
        $this->findModel($id)->delete();
        return Response::json(['status' => 'success']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function singleDelete(Request $request)
    {
        $data = $request->all();
        if (isset($data['id']) && $id = $data['id']) {
            $this->delete($id);
        }
        return Response::json(['status' => 'success']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function multiDelete(Request $request)
    {
        $data = $request->all();
        $ids = isset($data['ids']) ? $data['ids'] : [];
        if (count($ids)) {
            foreach ($ids as $id) {
                $this->delete($id);
            }
        }
        return Response::json(['status' => 'success']);
    }

    protected function checkSubmitType($requestData, $model, $success = false)
    {
//        var_dump($success);
//        die;
        if (isset($requestData['save'])) {
            $redirectRoute = null;
            switch ($requestData['save']) {
                case 'close':
                    $redirectRoute = isset($requestData['close_route']) ? $requestData['close_route'] : static::getFullRoute($model);
                    break;
                case 'create':
                    $redirectRoute = static::getFullRoute($model) . '/create';
                    break;
                default:
                    $redirectRoute = static::getFullRoute($model) . ($success ? '/update/' . $model->id : '/create');
            }
            return $this->successSave($redirectRoute, $success);
        }
        return abort(404);
    }

    protected function successSave($redirectRoute, $success)
    {
        return redirect($redirectRoute)->with('toast', ['type' => $success ? 'success' : 'error', 'message' => $success ? 'Элемент успешно сохранен!' : 'Сохранение элемента не удалось!']);
    }

    /**
     * @param UwtModel|string $model
     * @return string
     */
    public static function getFullRoute($model)
    {
        return static::$prefixRoute . $model::getRoute();
    }

    protected function initModel()
    {
        $currentRoute = Route::getFacadeRoot()->current();
        if (isset($currentRoute->parameters['path'])) {
            $path = $currentRoute->parameters['path'];
            $modelName = array_map(function ($data) {
                return implode(array_map('ucfirst', explode('-', $data)));
            }, explode('/', $path));
            $modelName = implode('\\', $modelName);
            static::$model = 'App\\Models\\' . $modelName;
            if (class_exists(static::$model)) {static::$model::setController(static::class);
                return;
            }
            static::$model = null;
            abort(404);
        }
    }

    /**
     * @param Request $request
     * @param UwtModel $model
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function doValidate(Request $request, UwtModel $model)
    {
        foreach ($model->generateAttributes() as $key => $field) {
            if (!$request->get($key)) {
                $data = [];
                if (isset($field['params'])) {
                    foreach ($field['params'] as $param) {
                        $data[$param] = $request->get($param);
                    }
                }
                if (isset($field['isOnlyCreate']) && $field['isOnlyCreate']) {
                    if (!$model->id) {
                        $request->merge([$key => $field['function']($data)]);
                    }
                } else {
                    $request->merge([$key => $field['function']($data)]);
                }
            }
        }
        parent::validate($request, $model->rules(), $model->errorMessages(), $model->getLabels());
        return $request->all();
    }

    public function renderPage($pageName, $params = [])
    {
        $model = isset($params['model']) ? $params['model'] : (static::$model ? static::$model::getInstance() : null);
        if ($model) {
            $page = $model->getPage($pageName, $params);
            if ($page == 'singleton') {
                if ($singleton = $model::query()->first()) {
                    return redirect(static::getFullRoute($model).'/update/'.$singleton->id);
                } else {
                    return redirect(static::getFullRoute($model).'/create');
                }
            }
            return view(static::$prefixRoute . '/index', array_merge($params, $page));
        }
        return abort(404);
    }

    public function findModel($id)
    {
        if ($model = static::$model::find($id)) {
            return $model;
        }
        return abort(404);
    }

    public static function getPrefixRoute()
    {
        return static::$prefixRoute;
    }

    public static function getModel() {
        return static::$model;
    }
}
