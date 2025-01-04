<div>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid justify-content-start">
            <button
                type="button"
                class="btn btn-primary btn-sm mx-3"
                wire:click="addEntry()"
            >{{ __('dicms::sites.metadata.add') }}</button>
            <button
                type="button"
                class="btn btn-secondary btn-sm mx-3"
                wire:click="importTwitter()"
            >{{ __('dicms::sites.metadata.import.twitter') }}</button>
            <button
                type="button"
                class="btn btn-secondary btn-sm mx-3"
                wire:click="importOG()"
            >{{ __('dicms::sites.metadata.import.og') }}</button>
            @if($container instanceof \halestar\LaravelDropInCms\Models\Page)
            <button
                type="button"
                class="btn btn-warning btn-sm mx-3"
                wire:click="importFromSite()"
                wire:confirm="{{ __('dicms::sites.metadata.import.confirm') }}"
            >{{ __('dicms::sites.metadata.import') }}</button>
            @endif
            <button
                type="button"
                class="btn btn-secondary btn-sm mx-3"
                wire:click="clearAll()"
                wire:confirm="{{ __('dicms::sites.metadata.clear.confirm') }}"
            >{{ __('dicms::sites.metadata.clear') }}</button>
        </div>
    </nav>
    <div class="row">
        <ul class="list-group">
            @foreach($metadata as $meta)
             <li class="list-group-item" wire:key="{{ $loop->index }}">
                 <div class="row">
                     <div class="col-sm-5">
                         <div class="form-floating">
                             <input
                                 type="text"
                                 class="form-control"
                                 id="name_{{ $loop->index }}"
                                 placeholder="name"
                                 value="{{ $meta->name }}"
                                 wire:change="updateName({{ $loop->index }}, $event.target.value)"
                             >
                             <label for="name_{{ $loop->index }}">{{ __('dicms::admin.name') }}</label>
                         </div>
                     </div>
                     <div class="col-sm-6">
                         <div class="form-floating">
                             <textarea
                                 type="text"
                                 class="form-control"
                                 id="content_{{ $loop->index }}"
                                 placeholder="content"
                                 wire:change="updateContent({{ $loop->index }}, $event.target.value)"
                             >{{ $meta->content }}</textarea>
                             <label for="content_{{ $loop->index }}">{{ __('dicms::admin.content') }}</label>
                         </div>
                     </div>
                     <div class="col-sm-1 align-self-center text-end">
                         <button
                             type="button"
                             class="btn btn-danger btn-sm"
                             wire:click="removeEntry({{ $loop->index }})"
                             wire:confirm="{{ __('dicms::sites.metadata.remove.confirm') }}"
                         ><i class="fa-solid fa-times"></i></button>
                     </div>
                 </div>
             </li>
            @endforeach
        </ul>
    </div>
</div>
