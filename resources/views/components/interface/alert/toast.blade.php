<script>
    $(document).ready(function () {
        Toast.fire({
            type: "{{$type}}",
            title: "{{$message}}"
        })
    });
</script>
