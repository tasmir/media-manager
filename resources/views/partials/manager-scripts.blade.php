@if(!config('media-manager.active_tailwindcss'))
<link rel="stylesheet" href="{{ asset(config('media-manager.assets.css')) }}">
@endif
<script src="{{ asset(config('media-manager.assets.js')) }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        MediaManager.init({
            pickerUrl: "{{ route('admin.files.picker') }}",
            storeUrl: "{{ route('admin.files.store') }}",
            csrfToken: "{{ csrf_token() }}",
            baseUrl: "{{ url('/') }}"
        });
    });
</script>
