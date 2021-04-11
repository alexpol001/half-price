<?php
/**
 * @var \App\Models\UwtModel $model
 */
$required = $model->hasRule($slug, 'required');
?>
<div class="form-group @error($slug) is-invalid @enderror">
    <label for="{{$slug}}">{{ __($model->getLabel($slug)) }}@if($required)<span class="required-star">*</span>@endif
    </label>
    <div class="input-group">
        <textarea id="{{$slug}}" type="text" class="form-control"
                  name="{{$slug}}"
                  @if($required) required
                  @endif placeholder="{{__($model->getPlaceHolder($slug))}}">{{$model->getValue($slug) ? $model->getValue($slug) : old($slug)}}</textarea>
    </div>
    @if ($hint ?? false)
        <span class="hint">{{$hint}}</span>
    @endif
    @error($slug)
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
</div>
