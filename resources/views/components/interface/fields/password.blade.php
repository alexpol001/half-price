<?php
/**
 * @var \App\Models\Field $field
 * @var \App\Models\UwtModel $model
 */
$required = $required ?? $model->hasRule($slug, 'required');
?>
<div class="form-group @error($slug) is-invalid @enderror">
    <label for="{{$slug}}">{{ __($model->getLabel($slug)) }}@if($required)<span class="required-star">*</span>@endif</label>
    <div class="input-group">
        <input id="{{$slug}}" type="password" class="form-control"
               name="{{$slug}}" value=""
               @if($required) required @endif placeholder="{{__($model->getPlaceHolder($slug))}}">
    </div>
    @if ($hint ?? false)
        <span class="hint">{{$hint}}</span>
    @endif
    @error($slug)
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
</div>
