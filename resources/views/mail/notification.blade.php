@component('mail::message')
{{-- Greeting --}}
@lang('Привет')

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}
@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
$color = 'primary';
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

@lang('С уважением'),<br> {{ \App\Models\Site\Setting::query()->first()->title }}
@endcomponent
