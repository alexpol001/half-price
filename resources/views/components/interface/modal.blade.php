@php
    $modal = null;
@endphp
<div class="modal fade select2-modal" id="{{$id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Создать продукт</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @foreach($components as $key => $component)
                    @include('components.'.key($component), reset($component))
                @endforeach
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@section('pos-end')
    @parent
    <script>
        $(document).ready(function () {
            @if ($session = \Illuminate\Support\Facades\Session::get('product_creating'))
            @if ($session['status'] == 'error')
            $('#{{$id}}').modal();
            @endif
            @endif
        });
    </script>
@endsection
