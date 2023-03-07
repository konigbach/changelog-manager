@if(count($lines) > 0)
{{ config('changelog-manager.markdown_title_type') }} {{ \Illuminate\Support\Str::title($type) }}
@foreach($lines as $line)
    - "{{ $line }}"
@endforeach
@endif
