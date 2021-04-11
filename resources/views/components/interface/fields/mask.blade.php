<?php
/**
 * @var \App\Models\UwtModel $model
 */
$required = $model->hasRule($slug, 'required');
?>
<div class="form-group @error($slug) is-invalid @enderror">
    <label for="{{$slug}}">{{ __($model->getLabel($slug)) }}@if($required)<span class="required-star">*</span>@endif</label>
    <div>
        <div class="input-group">
            @isset($icon)
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="{{$icon}}"></i></span>
            </div>
            @endisset
            <input id="{{$slug}}" type="text" class="form-control"
                   name="{{$slug}}" value="{{ $model->getValue($slug) ? $model->getValue($slug) : old($slug) }}"
                   @if($required) required @endif data-inputmask="'mask': '{{$mask}}'" data-mask="{{$mask}}" im-insert="true" placeholder="{{$mask}}">
            {{--<input type="text" class="form-control" data-inputmask="'mask': ['999-999-9999 [x99999]', '+099 99 99 9999[9]-9999']" data-mask="" im-insert="true">--}}
        </div>
        @if ($hint ?? false)
            <span class="hint">{{$hint}}</span>
        @endif
        @error($slug)
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
</div>
