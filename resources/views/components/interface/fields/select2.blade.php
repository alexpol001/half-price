<?php
/**
 * @var \App\Models\Field $field
 * @var \App\Models\UwtModel $model
 * @var \App\Models\UwtModel $dataModel
 */
$required = $model->hasRule($slug, 'required');
$is_multiple = isset($is_multiple) ? $is_multiple : false;
$isRender = isset($isRender) ? $isRender : true;
$dataTitle = isset($dataTitle) ? $dataTitle : 'title';
$modal = isset($modal) ? $modal : null;
$label = $model->getLabel($slug);
?>
@if ($isRender)
    <div class="form-group @error($slug) is-invalid @enderror">
        <label for="{{$slug}}">{{$label}}@if($required)<span class="required-star">*</span>@endif</label>
        <div class="input-group">
            <select id="{{$slug}}"
                    name="{{$slug}}{{$is_multiple ? '[]' : ''}}"
                    multiple="{{$is_multiple ? 'multiple' : ''}}"
                    class="form-control select2" style="width: 100%"
                    @if($required) required @endif>
                @if (!$required)
                    <option value=""></option>
                @endif
                @if (!$is_multiple)
                    @if ($session = \Illuminate\Support\Facades\Session::get('product_creating'))
                        @if ($session['status'] == 'success')
                            @php
                                $model->$slug = \App\Models\Product::find($session['id']);
                            @endphp
                        @endif
                    @endif
                    @if(($model->$slug && $selectedItem = $dataModel->find($model->getValue($slug)))
                     || ($selectedItem = $dataModel->find(old($slug)))
                     || (isset($selected) && $selectedItem = $dataModel->query()->first()))
                        <option value="{{$selectedItem->id}}" selected="selected">{{$selectedItem->$dataTitle}}</option>
                    @endif
                @else
                    @if ($selectedItems = $model->$slug)
                        @foreach($selectedItems as $selectedItem)
                            <option value="{{$selectedItem->id}}"
                                    selected="selected">{{$selectedItem->$dataTitle}}</option>
                        @endforeach
                    @endif
                @endif
                @if (!isset($selectedItem) && !isset($selectedItems) && isset($default))
                    @if ($defaultItem = $dataModel->find($default))
                        <option value="{{$defaultItem->id}}" selected="selected">{{$defaultItem->$dataTitle}}</option>
                    @endif
                @endif
            </select>
        </div>
        @if ($hint ?? false)
            <span class="hint">{{$hint}}</span>
        @endif
        @error($slug)
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
@section('pos-end')
    @parent
    <script>
        $(document).ready(function () {
            $({{$slug}}).select2({
                allowClear: "{{!$required}}",
                placeholder: "{{ __($model->getPlaceHolder($slug)) }}",
                searchInputPlaceholder: '{{isset($search) ? $search : 'Поиск...'}}',
                language: {
                    "noResults": function () {
                        return 'Не найдено!@if ($modal) <a id="create-select2-{{$slug}}" href="#" class="button button-secondary" data-toggle="modal" data-target="#modal-select2-{{$slug}}">Создать свой продукт</a>@endif';
                    },
                    'searching': function() {
                        return "Выполняется поиск...";
                    }
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                multiple: "{{$is_multiple}}",
                ajax: {
                    url: "/api{{$dataModel::getRoute()}}@isset($filter)?{!!\App\Helpers\ApiHelper::queryFilterGet($filter)!!}@endisset",
                    data: function (params) {
                        let term = params.term;
                        let search = term ? term : '';
                        return {
                            columns: [
                                {data: '{{$dataTitle}}'},
                                @isset($addColumn)
                                {data: '{{$addColumn}}'}
                                @endisset
                            ],
                            search: {value: search},
                            length: '{{isset($length) ? $length : 10}}',
                            @isset ($exclude)
                            exclude: [
                                {id: "{{$model->$exclude}}"}
                            ],
                            @endisset
                        }
                    },
                    dataType: 'json',
                    processResults: function (response) {
                        var myResults = [];
                        $.each(response.data, function (index, item) {
                            myResults.push({
                                'id': item.id,
                                'text': item['{{$dataTitle}}'] @isset($addColumn) + " (штрихкод: " + item['{{$addColumn}}'] + ")" @endisset
                            });
                        });
                        return {
                            results: myResults
                        };
                    }
                }
            });
        });
        @if ($modal)
        $('body').on('click', '#create-select2-{{$slug}}', function () {
            $("#{{$slug}}").select2('close');
        });
        @endif
    </script>
    @if ($modal)
        @include('components.interface.modal', [
            'id' => "modal-select2-$slug",
            'components' => $modal['components'],
        ])
    @endif
@endsection
@endif
