@extends("dicms::layouts.admin.index", ['template' => $template])

@section('index_content')
    <livewire:metadata-editor :container="$obj" />
@endsection
