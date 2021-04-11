<?php
/**
 * @var \App\Models\UwtModel $model
 */
$action = $model::getController()::getFullRoute($model).'/create'
?>
<div class="form">
    <form method="POST" action="{{$action}}" enctype="{{isset($files) && $files ? 'multipart/form-data' : ''}}" style="width: 100%">
        @csrf
        @foreach($components as $key => $component)
            @include('components.'.key($component), reset($component))
        @endforeach
        <div class="form-group">
            <div class="btn-group">
                <button type="submit" name="save" value="save" class="btn btn-success">{{ __('Сохранить') }}</button>
            </div>
        </div>
    </form>
</div>
@section('pos-end')
    @parent
    {{--<script>--}}
        {{--$(document).ready(function () {--}}
            {{--$('form[action="{{$action}}"]').on('submit', function (e) {--}}
                {{--e.preventDefault();--}}
                {{--let form = $(this);--}}
                {{--let url = form.attr('action');--}}
                {{--console.log(url);--}}

                {{--$.ajax({--}}
                    {{--type: "POST",--}}
                    {{--url: url,--}}
                    {{--data: form.serialize(), // serializes the form's elements.--}}
                    {{--success: function(data)--}}
                    {{--{--}}
                        {{--console.log('success');--}}
                        {{--let errors = data.errors;--}}
                        {{--console.log(data);--}}
                        {{--$.each(errors, function (key, value) {--}}
                            {{--console.log(key);--}}
                        {{--})--}}
                    {{--},--}}
                    {{--error: function (data) {--}}
                        {{--console.log('error');--}}
                        {{--console.log(data);--}}
                    {{--}--}}
                {{--});--}}
            {{--});--}}
        {{--});--}}
    {{--</script>--}}
@endsection


