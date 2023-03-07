{{ config('changelog-manager.markdown_title_tag_and_time') }} [{{ $version }}] - {{ \Carbon\Carbon::now()->format('Y-m-d') }}
@foreach(config('changelog-manager.allowed_types') as $type)
@if($lines[$type] ?? false)
@include('changelog-manager::changelog-manager.changelog_type', ['type' => $type, 'lines' => $lines[$type]])
@endif
@endforeach
