@php
    /**
     * @var \App\Models\UwtModel $model
     * @var \App\Models\UwtModel $model
     * @var $tabs $array
     */
    $id = isset($id) ? $id: null;
    $items = isset($items) ? $items: null;
@endphp
<div class="tabs">
    <ul class="nav nav-pills">
        @foreach($items as $key => $item)
            @if (!isset($item['isRender']) || (isset($item['isRender']) && $item['isRender']))
                <li class="nav-item">
                    <a class="nav-link {{(isset($item['active']) && $item['active']) ? ' active' : ''}}"
                       href="#{{isset($item['id']) ? $item['id']: 'nav_'.$key}}"
                       data-toggle="tab">
                        {!!isset($item['icon']) ? '<i class="'.$item['icon'].'"></i>' : '' !!}
                        {{isset($item['title']) ? $item['title'] : 'Tab '.($key+1)}}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
    <div class="card card-warning card-outline" id="{{$id ? $id: ''}}">
        <!-- .card-body -->
        <div class="card-body">
            <!-- .tab-content -->
            <div class="tab-content">
                @foreach($items as $key => $item)
                    @if (!isset($item['isRender']) || (isset($item['isRender']) && $item['isRender']))
                        <div class="tab-pane{{(isset($item['active']) && $item['active']) ? ' active' : ''}}"
                             id="{{isset($item['id']) ? $item['id']: 'nav_'.$key}}">
                            @isset($item['components'])
                                @foreach($item['components'] as $component)
                                    @include('components.'.key($component), reset($component))
                                @endforeach
                            @endisset
                        </div>
                    @endif
                @endforeach
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- /.card-body -->
    </div>
</div>
