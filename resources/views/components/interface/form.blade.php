<?php
/**
 * @var \App\Models\UwtModel $model
 * @var \App\Models\UwtModel $parent
 */

$is_inner_card = isset($is_inner_card) ? $is_inner_card : false;
//$closeRoute = isset($parent) ? $parent['model']::getRoute().(isset($parent['id']) ? '/update/'.$parent['id'] : '') : $model::getRoute();
$closeRoute = isset($closeRoute) ? $closeRoute : \App\Http\Controllers\Admin\CrudController::getFullRoute($model);
?>
<div class="form">
    <form method="POST" enctype="{{isset($files) && $files ? 'multipart/form-data' : ''}}" style="width: 100%">
        <div class="form-group">
            <div class="btn-group">
                <button type="submit" name="save" value="save" class="btn btn-success">{{ __('Сохранить') }}</button>
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                        aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                    <button type="submit" name="save" value="close" class="dropdown-item"
                            href="#">{{ __('Сохранить и закрыть') }}</button>
                    <button type="submit" name="save" value="create" class="dropdown-item"
                            href="#">{{ __('Сохранить и создать') }}</button>
                    {{--<button type="submit" name="save" value="copy" class="dropdown-item"--}}
                    {{--href="#">{{ __('Сохранить и копировать') }}</button>--}}
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{$closeRoute}}">Выход</a>
                </div>
            </div>
            <a href="{{$closeRoute}}" class="btn btn-danger close-form">
                <i class="fas fa-times"></i>
            </a>
        </div>
        @csrf
        <input type="hidden" value="{{$closeRoute}}" name="close_route">
        @foreach($components as $key => $component)
            @include('components.'.key($component), reset($component))
        @endforeach
    </form>
</div>
@if(count($errors) > 0)
@section('pos-end')
    @include('components.interface.alert.toast', [
        'type' => 'error',
        'message' => 'Сохранение элемента не удалось! Пожалуйста, проверьте правильность заполнения формы.'
    ])
@endsection
@endif


