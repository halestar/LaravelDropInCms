<div {{ $attributes }} >
    <div class="input-group">
        <label for="{{ $name }}_tag" class="input-group-text">&lt;</label>
        <select name="{{ $name }}_tag" id="{{ $name }}_tag" class="form-select" onchange="$('#{{ $name }}_tag_name').html($(this).val())">
            @foreach($tags as $tag)
                <option value="{{ $tag }}" @if($tag == $selectedTag) selected @endif >{{ $tag }}</option>
            @endforeach
        </select>
        <input
            type="text"
            name="{{ $name }}_options"
            id="{{ $name }}_options"
            aria-describedby="{{ $name }}_help"
            class="form-control"
            value="{{ $options }}"
        />
        <label for="{{ $name }}_options" class="input-group-text">&gt;</label>
    </div>

    <div class=" ps-4 mt-2">
        {{ $slot }}
    </div>

    <div class="input-group mt-2">
        <label for="{{ $name }}-tag" class="input-group-text">&lt;/<span id="{{ $name }}_tag_name">{{ $selectedTag?? $tags[0] }}</span>&gt;</label>
    </div>
</div>
