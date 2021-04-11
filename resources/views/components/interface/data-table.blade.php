<?php
/**
 * @var \App\Models\UwtModel $model
 * @var bool $is_inner_card
 */

$is_inner_card = isset($is_inner_card) ? $is_inner_card : false;
$id = isset($id) ? 'test' : md5(get_class($dataModel));
$deletable = isset($deletable) ? $deletable : false;
$columns = isset($columns) ? $columns : [];
$sortable = isset($sortable) ? $sortable : false;
$relation = isset($relation) ? $relation : null;
?>
<div class="data-table" id="{{$id}}"
     data-route="{{$dataModel::getController()::getPrefixRoute()}}{{$dataModel::getRoute()}}">
    <div class="card {{!$is_inner_card ? '' : 'my-0 card-outline'}}">
        <div class="card-header">
            <h3 class="card-title">{!! isset($icon) ? '<i class="'.$icon.'"></i> ' : '' !!}{{$title}}</h3>
            <div class="card-tools">
                <a href="{{$dataModel::getController()::getPrefixRoute()}}{{$dataModel::getRoute()}}/create{{$relation ? $relation : ''}}"
                   class="btn btn-success"><i class="fas fa-plus"></i></a>
                @if ($deletable)
                    <a href="#"
                       class="btn btn-danger multi-delete">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                @endif
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @if (count($columns))
                <table class="table table-bordered table-striped dataTable">
                    <thead>
                    <tr role="row">
                        @if ($deletable)
                            <th class="text-center">
                                <div class="icheck icheck-success">
                                    <input type="checkbox" value="" id="{{$id}}check0">
                                    <label for="{{$id}}check0"></label>
                                </div>
                            </th>
                        @endif
                        @foreach($columns as $column)
                            <th>{{$dataModel->getLabel($column['data'])}}</th>
                        @endforeach
                        <th class="text-center">
                            Редактировать
                        </th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        @if ($deletable)
                            <th class="text-center">
                                <div class="icheck icheck-success">
                                    <input type="checkbox" value="" id="{{$id}}check">
                                    <label for="{{$id}}check"></label>
                                </div>
                            </th>
                        @endif
                        @foreach($columns as $column)
                            <th>{{$dataModel->getLabel($column['data'])}}</th>
                        @endforeach
                        <th class="text-center">
                            Редактировать
                        </th>
                    </tr>
                    </tfoot>
                </table>
            @section('pos-end')
                @parent
                <script>
                    $(document).ready(function () {
                        let tableContainer = $("#{{$id}}");
                        let table = tableContainer.find('.dataTable');
                        table.DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "/api{{$dataModel::getRoute()}}@isset($filter)?{!! \App\Helpers\ApiHelper::queryFilterGet($filter) !!}@endisset",
                            bAutoWidth: false,
                            order: [],
                            aLengthMenu: [
                                [10, 25, 50, 100],
                                [10, 25, 50, 100]
                            ],
                            columns:
                                [
                                        @if ($deletable)
                                    {
                                        data: 'id',
                                        render: function (data, type, row) {
                                            return '<div class="icheck icheck-success"><input type="checkbox" value="' + data + '" id="{{$id}}check' + data + '"><label for="{{$id}}check' + data + '"></label></div>'
                                        },
                                        className: 'text-center',
                                        searchable: false,
                                        orderable: false,
                                    },
                                        @endif
                                        @foreach ($columns as $column)
                                    {
                                        data: "{{$column['data']}}",
                                        @isset($column['name'])
                                        name: "{{$column['name']}}",
                                        @endisset
                                                @isset($column['searchable'])
                                        searchable: Boolean("{{$column['searchable']}}"),
                                        @endisset
                                                @isset($column['orderable'])
                                        orderable: Boolean("{{$column['orderable']}}"),
                                        @endisset
                                    },
                                        @endforeach
                                    {
                                        data: 'id',
                                        render: function (data, type, row) {
                                            return '<div class="btn-group">' +
                                                '<a class="btn btn-info" href="' + tableContainer.data('route') + '/update/' + data + '"><i class="fas fa-edit"></i></a>' +
                                                '@if ($deletable) <a class="btn btn-danger delete" href="' + data + '"><i class="fas fa-trash"></i></a> @endif' +
                                                '</div><div class="row-id" style="display: none;">' + data + '</div>'
                                        },
                                        className: 'text-center',
                                        searchable: false,
                                        orderable: false,
                                    },
                                ],
                            language: {
                                loadingRecords: '&nbsp;',
                                processing: '<div class="spinner"></div>',
                                lengthMenu: "_MENU_ элементов на странице",
                                zeroRecords: "Не найдено!",
                                info: "Страница _PAGE_ из _PAGES_",
                                infoEmpty: "Нет доступных записей",
                                infoFiltered: "",
                                search: 'Поиск:',
                                paginate: {
                                    first: "Начало",
                                    last: "Конец",
                                    previous: 'Назад',
                                    next: 'Далее',
                                },
                            }
                        });
                        @if ($sortable)
                        table.find('tbody').sortable({
                            update: function (event, ui) {
                                let sort = ui.item;
                                let prev = sort.prev();
                                let next = sort.next();
                                let data = {
                                    id: sort.find('.row-id').html(),
                                    prev: prev.find('.row-id').html(),
                                    next: next.find('.row-id').html(),
                                };
                                $.ajax({
                                    type: 'POST',
                                    url: '/api{{$dataModel::getRoute()}}/sort',
                                    data: data,
                                    success: function (data) {
                                        console.log(data);
                                    },
                                    error: function (error) {
                                        console.error(error.responseJSON.message);
                                    }
                                });
                            }
                        }).disableSelection();
                        @endif
                    });
                </script>
            @endsection
            @endif
        </div>
        <!-- /.card-body -->
    </div>
</div>
