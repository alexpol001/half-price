<?php
/**
 * @var \App\Models\UwtModel $model
 */
$required = $model->hasRule($slug, 'required');
$value = $model->getValue($slug) ? $model->getValue($slug) : old($slug);
?>
<div class="form-group @error($slug) is-invalid @enderror">
    {{--<input type="checkbox" value="0" checked="" name="{{$slug}}" style="display: none">--}}
    <div class="icheck-success d-inline">
        <input type="checkbox" value="1" {{ old($slug) || $value ? 'checked' : '' }} name="{{$slug}}" id="{{$slug}}">
        <label for="{{$slug}}">
        </label>
    </div>
    <label for="{{$slug}}">{!! __($model->getLabel($slug))  !!} @if($required)<span class="required-star">*</span>@endif</label>
    @if ($hint ?? false)
        <span class="hint">{{$hint}}</span>
    @endif
    @error($slug)
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
</div>
