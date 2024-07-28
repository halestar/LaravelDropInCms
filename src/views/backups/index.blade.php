@extends("dicms::layouts.admin")

@section('content')
    <div class="container">
        <div class="row">
            <a href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.backups.export') }}" class="btn btn-primary col mx-2">{{ __('dicms::admin.backup.download') }}</a>
        </div>
        <form method="POST" action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.backups.restore') }}"  enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="restore_file" class="form-label">{{ __('dicms::admin.backups.restore.file') }}</label>
                <input class="form-control" aria-describedby="fileHelp" type="file" id="restore_file" name="restore_file">
                <div id="fileHelp" class="form-text">{{ __('dicms::admin.backups.restore.file.help') }}</div>
            </div>
            <div class="row">
                <button type="submit" class="btn btn-danger mx-2 col">{{ __('dicms::admin.backups.restore') }}</button>
            </div>
        </form>

    </div>
@endsection
