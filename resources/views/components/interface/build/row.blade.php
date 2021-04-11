<div class="row">
    @foreach($components as $key => $component)
        @include('components.'.key($component), reset($component))
    @endforeach
</div>
