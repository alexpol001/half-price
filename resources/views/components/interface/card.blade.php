<div class="card">
    <div class="card-body">
        @foreach($components as $component)
            @include('components.'.key($component), reset($component))
        @endforeach
    </div>
</div>
