@extends("dicms::layouts.admin")

@section('content')
        <div class="container">
            <h1 class="border-bottom d-flex justify-content-between align-items-center">
                {{__('dicms::menus.edit')}}
            </h1>
            <div class="row border-end border-start border-bottom rounded-bottom p-1 collapse" id="advanced_options">
                <form
                    method="POST"
                    action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.menus.update', ['site' => $site->id, 'menu' => $menu->id]) }}"
                >
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('dicms::menus.name') }}</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            aria-describedby="nameHelp"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ $menu->name }}"
                        />
                        <x-error-display key="name">{{ $errors->first('name') }}</x-error-display>
                        <div id="nameHelp" class="form-text">{{ __('dicms::menus.name.help') }}</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('dicms::menus.description') }}</label>
                        <textarea
                            type="text"
                            name="description"
                            id="description"
                            aria-describedby="descriptionHelp"
                            class="form-control"
                        >{{ $menu->description }}</textarea>
                        <div id="descriptionHelp" class="form-text">{{ __('dicms::menus.description.help') }}</div>
                    </div>

                    <div class="mb-3">
                        <label for="nav_classes" class="form-label">{{ __('dicms::menus.nav_classes') }}</label>
                        <input
                            type="text"
                            name="nav_classes"
                            id="nav_classes"
                            aria-describedby="nav_classesHelp"
                            class="form-control"
                            value="{{ $menu->nav_classes }}"
                        />
                        <div id="nav_classesHelp" class="form-text">{{ __('dicms::menus.nav_classes.help') }}</div>
                    </div>

                    <div class="mb-3">
                        <label for="container_classes" class="form-label">{{ __('dicms::menus.container_classes') }}</label>
                        <input
                            type="text"
                            name="container_classes"
                            id="container_classes"
                            aria-describedby="container_classesHelp"
                            class="form-control"
                            value="{{ $menu->container_classes }}"
                        />
                        <div id="container_classesHelp" class="form-text">{{ __('dicms::menus.container_classes.help') }}</div>
                    </div>

                    <div class="mb-3">
                        <label for="element_classes" class="form-label">{{ __('dicms::menus.element_classes') }}</label>
                        <input
                            type="text"
                            name="element_classes"
                            id="element_classes"
                            aria-describedby="element_classesHelp"
                            class="form-control"
                            value="{{ $menu->element_classes }}"
                        />
                        <div id="element_classesHelp" class="form-text">{{ __('dicms::menus.element_classes.help') }}</div>
                    </div>

                    <div class="mb-3">
                        <label for="link_classes" class="form-label">{{ __('dicms::menus.link_classes') }}</label>
                        <input
                            type="text"
                            name="link_classes"
                            id="link_classes"
                            aria-describedby="link_classesHelp"
                            class="form-control"
                            value="{{ $menu->link_classes }}"
                        />
                        <div id="link_classesHelp" class="form-text">{{ __('dicms::menus.link_classes.help') }}</div>
                    </div>

                    <div class="row py-2">
                        <button type="submit" class="btn btn-primary col">{{ __('dicms::headers.settings.advanced.update') }}</button>
                    </div>
                </form>
            </div>
            <div class="row mt-0 justify-content-center">
                <div class="col col-auto border-end border-bottom border-start rounded-bottom p-2">
                    <button type="button" data-bs-toggle="collapse" data-bs-target="#advanced_options"
                            class="btn btn-primary">{{ __('dicms::headers.settings.advanced') }}</button>
                </div>
            </div>

            <form action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.menus.update.content', ['menu' => $menu->id]) }}" method="POST" onsubmit="saveData()">
                @csrf
                @method('PUT')
                <h2 class="border-bottom pb-2 d-flex justify-content-between align-items-center">
                    <span>{{ __('dicms::menus.builder') }}</span>
                    <button type="button" class="btn btn-primary" onclick="menuBuilder.addMenuItem()">{{ __('dicms::menus.builder.new') }}</button>
                </h2>
                <input type="hidden" name="menu" id="menu" />
                <ul id="menu_builder" class="list-group list-group-horizontal"></ul>
                <hr class="my-5" />
                <div class="row">
                    <button type="submit" class="btn btn-primary col mx-2">{{ __('dicms::menus.edit') }}</button>
                    <a
                        href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.menus.index', ['site' => $site->id]) }}"
                        class="btn btn-secondary col mx-2"
                    >{{ __('dicms::admin.cancel') }}</a>
                </div>
            </form>
        </div>
@endsection
@push('scripts')
    <script>
        let MenuItem = (function()
        {
            MenuItem.pages =
                [
                    @foreach(\halestar\LaravelDropInCms\Models\Page::orderBy('name')->get() as $page)
                    {
                        label: '{{ $page->name }}',
                        value: {{ $page->id }}
                    },
                    @endforeach
                ];
            MenuItem.plugins =
                [
                    @foreach(config('dicms.plugins', []) as $plugin)
                        @foreach($plugin::getPublicPages() as $page)
                            {
                                label: '{{ $page->name }}',
                                value: '{{ $page->url }}'
                            }
                      @endforeach
                    @endforeach
                ];

            MenuItem.types =
                {
                    link: 'LINK',
                    page: 'PAGE',
                    plugin: 'PLUGIN'
                }

            function MenuItem(id, mb, data = null)
            {
                if(data)
                {
                    this.type = data.type;
                    this.name = data.name;
                    this.url = data.url;
                    this.page_id = data.page_id;
                    this.plugin_url = data.plugin_url;
                }
                else
                {
                    this.type = MenuItem.types.link;
                    this.name = '';
                    this.url = '';
                    this.page_id = '';
                    this.plugin_url = '';
                }
                this.id = id;
                this.menuBar = mb;
                this.htmlObj = null;
                this.htmlObj = this.html();
            };

            MenuItem.prototype.html = function()
            {
                if(this.htmlObj)
                    return this.htmlObj;

                let that = this;

                let menuContainer = $('<li class="list-group-item" menu_id="' + this.id + '"></li>');

                //Delete Control
                let controlContainer = $('<div class="control-container mb-2 d-flex justify-content-end align-content-center"></div>')
                //let moveControl = $('<i class="text-dark fa fa-up-down-left-right"></i>');
                let deleteControl = $('<i class="text-danger fa fa-times"></i>');
                deleteControl.on('click', this.id, $.proxy(this.menuBar.removeMenuItem, this.menuBar));
                controlContainer.append(deleteControl);

                menuContainer.append(controlContainer);

                //Name Input
                let nameContainer = $('<div class="form-floating mb-1"><label for="menu_item_name_' + this.id +
                        '">{{ __('dicms::menus.builder.item.name') }}</label></div>');
                let nameInput = $('<input type="text" class="form-control form-control-sm" value="' + this.name +
                        '" id="menu_item_name_'+ this.id + '" />');
                nameInput.on('change', function(){ that.name = $(this).val(); });
                nameContainer.prepend(nameInput);

                menuContainer.append(nameContainer)


                //URL Input
                let urlContainer = $('<div class="input-group"></div>');
                let urlPicker = $('<input type="radio" id="menu_item_type_' + this.id + '_url" name="menu_item_type_' +
                    this.id + '" value="'+
                    MenuItem.types.link + '" ' + (this.type === MenuItem.types.link? 'checked': '') + ' />')
                let urlInput = $('<input type="text" class="form-control" value="' + this.url +
                    '" id="menu_item_url_'+ this.id + '" />');

                urlPicker.on('click', function(){ that.type = MenuItem.types.link; });
                urlInput.on('change', function(){ that.url = $(this).val(); });


                urlContainer.append($('<div class="input-group-text"></div>').append(urlPicker))
                    .append($('<label class="input-group-text" for="menu_item_type_' + this.id + '_url">{{ __('dicms::menus.builder.labels.url') }}:</label>'))
                    .append(urlInput);

                menuContainer.append(urlContainer);

                //Internal Page Input
                let pageContainer = $('<div class="input-group"></div>');
                let pagePicker = $('<input type="radio" id="menu_item_type_' + this.id + '_page" name="menu_item_type_' +
                    this.id + '" value="'+
                    MenuItem.types.page + '" ' + (this.type === MenuItem.types.page? 'checked': '') + ' />')
                pagePicker.on('click', function(){ that.type = MenuItem.types.page; });

                let pageDropdown = '<select id="menu_item_page_' + this.id +
                    '" class="form-select"><option value="0">{{ __('dicms::menus.builder.item.page') }}</option>';
                for(let i = 0; i < MenuItem.pages.length; i++)
                    pageDropdown += '<option value="' + MenuItem.pages[i].value + '" '+ ((this.page_id == MenuItem.pages[i].value)? 'selected': '')
                        + ' >' + MenuItem.pages[i].label + '</option>';
                pageDropdown += '</select>';
                let pageId = $(pageDropdown);
                pageId.on('change', function(){ that.page_id = $(this).val(); });

                pageContainer.append($('<div class="input-group-text"></div>').append(pagePicker))
                    .append(pageId);

                menuContainer.append(pageContainer);

                //Plugin Input
                let pluginContainer = $('<div class="input-group"></div>');
                let pluginPicker = $('<input type="radio" id="menu_item_type_' + this.id + '_plugin" name="menu_item_type_' +
                    this.id + '" value="'+
                    MenuItem.types.plugin + '" ' + (this.type === MenuItem.types.plugin? 'checked': '') + ' />')
                pluginPicker.on('click', function(){ that.type = MenuItem.types.plugin; });

                let pluginDropdown = '<select id="menu_item_plugin_' + this.id +
                    '" class="form-select"><option value="0">{{ __('dicms::menus.builder.item.plugin') }}</option>';
                for(let i = 0; i < MenuItem.plugins.length; i++)
                    pluginDropdown += '<option value="' + MenuItem.plugins[i].value + '" '+ ((this.plugin_url == MenuItem.plugins[i].value)? 'selected': '')
                        + ' >{{ __('dicms::menus.builder.labels.plugin') }}: ' + MenuItem.plugins[i].label + '</option>';
                pluginDropdown += '</select>';
                let pluginId = $(pluginDropdown);
                pluginId.on('change', function(){ that.plugin_url = $(this).val(); });
                pluginContainer.append($('<div class="input-group-text"></div>').append(pluginPicker))
                    .append(pluginId);


                menuContainer.append(pluginContainer);

                return menuContainer;
            };

            MenuItem.prototype.data = function()
            {
                let data = {
                    name: this.name,
                    url: this.url,
                    page_id: this.page_id,
                    type: this.type,
                    plugin_url: this.plugin_url
                }
                return data;
            }

            return MenuItem;
        })();

        let MenuBuilder = (function()
        {
            function MenuBuilder(container, data = null)
            {
                this.container = $('#' + container);
                this.menuBar = [];
                this.idCounter = 1;
                if(data)
                {
                    for(let i = 0; i < data.length; i++)
                        this.addMenuItem(data[i]);
                }
            }

            MenuBuilder.prototype.addMenuItem = function(data = null)
            {
                let menuItem = new MenuItem(this.idCounter, this, data);
                this.menuBar.push(menuItem);
                this.container.append(menuItem.htmlObj);
                this.idCounter++;
            }

            MenuBuilder.prototype.removeMenuItem = function(event)
            {
                //find the item
                let idx = this.menuBar.findIndex((element) => element.id === event.data);
                let removed = this.menuBar.splice(idx, 1);
                removed[0].htmlObj.remove();
            }

            MenuBuilder.prototype.data = function()
            {
                let data = [];
                for(let i = 0; i < this.menuBar.length; i++)
                    data.push(this.menuBar[i].data());
                return data;
            }

            return MenuBuilder;
        })();

        var menuBuilder = new MenuBuilder('menu_builder', {!! json_encode($menu->menu, true)?? "[]" !!});

        function saveData()
        {
            $('#menu').val(JSON.stringify(menuBuilder.data()));
        }
    </script>
@endpush
