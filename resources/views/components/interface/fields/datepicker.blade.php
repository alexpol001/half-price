<?php
/**
 * @var \App\Models\UwtModel $model
 */
$required = $model->hasRule($slug, 'required');
$value = $model->getValue($slug) ? \App\Helpers\CommonHelper::timeStampToDate($model->getValue($slug)) : old($slug);
?>
<div class="form-group @error($slug) is-invalid @enderror">
    <label for="{{$slug}}">{{ __($model->getLabel($slug)) }}@if($required)<span class="required-star">*</span>@endif</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
        </div>
        <input id="{{$slug}}" type="text" class="form-control"
               name="{{$slug}}" value="{{ $value }}"
               @if($required) required @endif placeholder="{{__($model->getPlaceHolder($slug))}}">
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
            $('#{{$slug}}').daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                singleDatePicker: true,
                // showDropdowns: true,
                locale: {
                    format: "DD/MM/YYYY - HH:mm",
                    separator: " - ",
                    applyLabel: "Применить",
                    cancelLabel: "Очистить",
                    fromLabel: "От",
                    toLabel: "До",
                    customRangeLabel: "Свой",
                    daysOfWeek: [
                        "Вс",
                        "Пн",
                        "Вт",
                        "Ср",
                        "Чт",
                        "Пт",
                        "Сб"
                    ],
                    monthNames: [
                        "Январь",
                        "Февраль",
                        "Март",
                        "Апрель",
                        "Май",
                        "Июнь",
                        "Июль",
                        "Август",
                        "Сентябрь",
                        "Октябрь",
                        "Ноябрь",
                        "Декабрь"
                    ],
                    firstDay: 1
                },
                // minYear: 1901,
                // maxYear: parseInt(moment().format('YYYY'),10)
            }, function(start, end, label) {});

            $('#{{$slug}}').on('cancel.daterangepicker', function(ev, picker) {
                $('#{{$slug}}').val('');
            });

            $('#{{$slug}}').val('{{$value}}');
        });
    </script>
@endsection
