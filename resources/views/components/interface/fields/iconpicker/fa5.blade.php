<?php
/**
 * @var \App\Models\Field $field
 * @var \App\Models\UwtModel $model
 */
$required = $model->hasRule($slug, 'required');
?>
<div class="form-group @error($slug) is-invalid @enderror">
    <label for="{{$slug}}">{{ __($model->getLabel($slug)) }}@if($required)<span class="required-star">*</span>@endif</label>
    <div class="input-group">
        <button id="{{$slug}}" class="btn btn-secondary"
                name="{{$slug}}"
                data-iconset="fontawesome5"
                data-icon="{{ isset($model->{$slug}) ? $model->{$slug} : old($slug) }}"
                data-search-text="Поиск"
                role="iconpicker">
        </button>
    </div>
    @if ($hint ?? false)
        <span class="hint">{{$hint}}</span>
    @endif
    @error($slug)
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
</div>
@section('pos-head')
    @parent
    <link href="{{asset('plugins/iconpicker/css/bootstrap-iconpicker.css')}}" rel="stylesheet">
@endsection

@section('pos-end')
    @parent
    <script src="{{ asset('plugins/iconpicker/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
@endsection
