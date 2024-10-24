<link rel="stylesheet" href="//unpkg.com/grapesjs/dist/css/grapes.min.css" />
<script src="https://unpkg.com/grapesjs"></script>
<script src="https://unpkg.com/grapesjs-style-bg"></script>
<script src="https://unpkg.com/grapesjs-style-gradient"></script>

<script>
    const plhPlugin = editor => {
        const { Components, Blocks } = editor;

        // Update the main wrapper
        Components.addType('wrapper', {

            model: {
                defaults: {
                    selectable: false,
                    highlightable: false,
                    propagate: ['highlightable', 'selectable']
                },
                // Return always the content of editable content (defined below)
                toHTML(opts) {
                    const editable = this.findType('editable')[0];
                    return editable ? editable.getInnerHTML(opts) : '';
                },
            },
            view: {
                onRender({ el }) {
                    el.style.pointerEvents = 'none';
                }
            }
        });

        // Create the editable component
        Components.addType('editable', {
            model: {
                defaults: {
                    removable: false,
                    draggable: false,
                    copyable: false,
                    propagate: [],
                },
            },
            view: {
                onRender({ el }) {
                    el.style.pointerEvents = 'all';
                }
            }
        });

        // Patch for getCss to return always the content
        // from editable component
        editor.getModel().getCss = () => {
            const wrapper = editor.getWrapper();
            const cmp = wrapper.findType('editable')[0];
            return cmp ? editor.CodeManager.getCode(cmp, 'css') : '';
        };

        // Patch for layers root
        editor.on('run:core:open-layers', () => {
            const wrapper = Components.getWrapper();
            const editable = wrapper.findType('editable')[0];
            editable && editor.Layers.setRoot(editable);
        });

        //some defaults for components
        const container_default =
        {
            draggable: true,
            droppable: true,
            removable: true,
            badgable: true,
            stylable: true,
            highlightable: true,
            copyable: true,
            resizable: true,
            editable: true,
            layerable: true,
            selectable: true,
        };

        /*****************************
         * Add models for the components here
         **/

        //containers


        Components.addType('i-container',
            {
                isComponent: el => el.tagName === 'I',
                model:
                    {
                        defaults:
                            {
                                ...container_default,
                                tagName: 'i',
                                name: '{{ __('dicms::assets.containers.icontainer') }}',
                            }
                    },
                view:
                    {
                        onRender({el})
                        {
                            $(el).css('padding', '1em')
                        }
                    }
            });

        Components.addType('main-container',
            {
                isComponent: el => el.tagName === 'MAIN',
                model:
                    {
                        defaults:
                            {
                                ...container_default,
                                tagName: 'main',
                                name: '{{ __('dicms::assets.containers.main') }}',
                            }
                    },
                view:
                    {
                        onRender({el})
                        {
                            $(el).css('padding', '1em')
                        }
                    }
            });

        Components.addType('header-container',
            {
                isComponent: el => el.tagName === 'HEADER',
                model:
                    {
                        defaults:
                            {
                                ...container_default,
                                tagName: 'header',
                                name: '{{ __('dicms::assets.containers.header') }}',
                            }
                    },
                view:
                    {
                        onRender({el})
                        {
                            $(el).css('padding', '1em')
                        }
                    }
            });

        Components.addType('footer-container',
            {
                isComponent: el => el.tagName === 'FOOTER',
                model:
                    {
                        defaults:
                            {
                                ...container_default,
                                tagName: 'footer',
                                name: '{{ __('dicms::assets.containers.footer') }}',
                            }
                    },
                view:
                    {
                        onRender({el})
                        {
                            $(el).css('padding', '1em')
                        }
                    }
            });

        Components.addType('section-container',
            {
                isComponent: el => el.tagName === 'SECTION',
                model:
                    {
                        defaults:
                            {
                                ...container_default,
                                tagName: 'section',
                                name: '{{ __('dicms::assets.containers.section') }}',
                            }
                    },
                view:
                    {
                        onRender({el})
                        {
                            $(el).css('padding', '1em')
                        }
                    }
            });



        Components.addType('nav-container',
            {
                isComponent: el => el.tagName === 'NAV',
                model:
                    {
                        defaults:
                            {
                                ...container_default,
                                tagName: 'nav',
                                name: '{{ __('dicms::assets.containers.nav') }}',
                            }
                    },
                view:
                    {
                        onRender({el})
                        {
                            $(el).css('padding', '1em')
                        }
                    }
            });

        Components.addType('article-container',
            {
                isComponent: el => el.tagName === 'ARTICLE',
                model:
                    {
                        defaults:
                            {
                                ...container_default,
                                tagName: 'article',
                                name: '{{ __('dicms::assets.containers.article') }}',
                            }
                    },
                view:
                    {
                        onRender({el})
                        {
                            $(el).css('padding', '1em')
                        }
                    }
            });


        Components.addType('heading',
            {
                isComponent: el => ( el.tagName === 'H1' ||
                    el.tagName === 'H2' || el.tagName === 'H3' ||
                    el.tagName === 'H4' || el.tagName === 'H5' ||
                    el.tagName === 'H6'),

                extend: 'text',
                model:
                    {
                        defaults:
                            {
                                ...container_default,
                                tagName: 'h1',
                                name: '{{ __('dicms::assets.text.heading') }}',
                                content: "{{ __('dicms::assets.text.heading') }}",
                                traits:
                                    [
                                        'title',
                                        {
                                            type: 'select',
                                            name: 'hsize',
                                            label: '{{ __('dicms::assets.text.heading.size') }}',
                                            options:
                                                [
                                                    { id: 'h1', label: 'H1' },
                                                    { id: 'h2', label: 'H2' },
                                                    { id: 'h3', label: 'H3' },
                                                    { id: 'h4', label: 'H4' },
                                                    { id: 'h5', label: 'H5' },
                                                    { id: 'h6', label: 'H6' },
                                                ]
                                        }
                                    ],
                                attributes:
                                    {
                                        hsize: 'h1',
                                    }
                            },
                        init()
                        {
                            this.on('change:attributes:hsize', this.changeSize);
                        },

                        changeSize()
                        {
                            this.set('tagName', this.getAttributes().hsize);
                        }
                    },
                view:
                    {
                        onRender({el})
                        {
                            $(el).css('padding', '1em')
                        }
                    }
            });

        Components.addType('span',
            {
                isComponent: el => el.tagName === 'SPAN',
                extend: 'text',
                model:
                    {
                        defaults:
                            {
                                ...container_default,
                                tagName: 'span',
                                name: '{{ __('dicms::assets.text.span') }}',
                                content: "{{ __('dicms::assets.text.span.content') }}",
                            }
                    },
                view:
                    {
                        onRender({el})
                        {
                            $(el).css('padding', '1em')
                        }
                    }
            });

        Components.addType('paragraph',
            {
                isComponent: el => el.tagName === 'P',
                extend: 'text',
                model:
                    {
                        defaults:
                            {
                                ...container_default,
                                tagName: 'p',
                                name: '{{ __('dicms::assets.text.paragraph') }}',
                                content: "{{ __('dicms::assets.text.paragraph.content') }}",
                            }
                    },
                view:
                    {
                        onRender({el})
                        {
                            $(el).css('padding', '1em')
                        }
                    }
            });

        Components.addType('list-group',
            {
                isComponent: el => el.tagName === 'UL',

                model:
                    {
                        defaults:
                            {
                                ...container_default,
                                tagName: 'ul',
                                type: 'wrapper',
                                name: '{{ __('dicms::assets.wrappers.ul') }}',
                                editable: false
                            }
                    },
                view:
                    {
                        onRender({el})
                        {
                            $(el).css('padding', '1em')
                        }
                    }
            });

        Components.addType('ordered-list-group',
            {
                isComponent: el => el.tagName === 'OL',

                model:
                    {
                        defaults:
                            {
                                ...container_default,
                                tagName: 'ol',
                                type: 'wrapper',
                                name: '{{ __('dicms::assets.wrappers.ul') }}',
                                editable: false,
                            }
                    },
                view:
                    {
                        onRender({el})
                        {
                            $(el).css('padding', '1em')
                        }
                    }
            });

        Components.addType('list-group-item',
            {
                isComponent: el => el.tagName === 'LI',
                extend: 'text',
                model:
                    {
                        defaults:
                            {
                                ...container_default,
                                tagName: 'li',
                                draggable: 'ul, ol',
                                name: '{{ __('dicms::assets.wrappers.li') }}',
                                content: "{{ __('dicms::assets.wrappers.li.content') }}",
                            }
                    },
                view:
                    {
                        onRender({el})
                        {
                            $(el).css('padding', '1em')
                        }
                    }
            });

        Components.addType('code-block',
            {
                isComponent: el => el.tagName === 'PRE',
                extend: 'text',
                model:
                    {
                        defaults:
                            {
                                ...container_default,
                                tagName: 'pre',
                                droppable: false,
                                name: '{{ __('dicms::assets.text.code') }}',
                                content: "{{ __('dicms::assets.text.code.content') }}",
                            }
                    },
                view:
                    {
                        onRender({el})
                        {
                            $(el).css('padding', '1em')
                        }
                    }
            });

        //menu item
        Components.addType('menu-item',
            {
                extend: 'link',
                model:
                    {
                        defaults:
                            {
                                ...container_default,
                                name: '{{ __('dicms::assets.menu.item') }}',
                                content: '{{ __('dicms::assets.menu.label') }}',
                                traits:
                                    [
                                        'title',
                                        {
                                            type: 'text',
                                            name: 'menuName',
                                            label: '{{ __('dicms::assets.menu.name') }}'
                                        },
                                        {
                                            type: 'select',
                                            name: 'hrefSel',
                                            label: '{{ __('dicms::assets.menu.destination') }}',
                                            options:
                                                [
                                                    {
                                                        label: '{{ __('dicms::assets.menu.destination.type') }}',
                                                        value: 'href',
                                                    },
                                                    @foreach(\halestar\LaravelDropInCms\Models\Page::where('plugin_page', false)->get() as $page)
                                                    {
                                                        label: '{{ __('dicms::pages.page') }}: {{ $page->name }}',
                                                        value: '/{{ $page->url }}'
                                                    },
                                                    @endforeach
                                                    @foreach(config('dicms.plugins', []) as $plugin)
                                                        @foreach($plugin::getPublicPages() as $page)
                                                        {
                                                            label: '{{ __('dicms::admin.plugin') }}: {{ $page->name }}',
                                                            value: '/{{ $page->url }}'
                                                        },
                                                        @endforeach
                                                    @endforeach
                                                ],
                                        },
                                        {
                                            type: 'text',
                                            name: 'hrefTxt',
                                            label: '{{ __('dicms::admin.url') }}'
                                        },
                                    ],
                            },
                        init()
                        {
                            this.on('change:attributes:menuName', this.changeContent);
                            this.on('change:attributes:hrefSel', this.addLink)
                            this.on('change:attributes:hrefTxt', this.addLink)
                        },
                        changeContent()
                        {
                            this.set('content', this.getAttributes().menuName)
                        },
                        addLink()
                        {
                            let href;
                            if(this.getAttributes().hrefSel === "href")
                                href = this.getAttributes().hrefTxt;
                            else
                                href = this.getAttributes().hrefSel;
                            if(href !== undefined)
                                this.addAttributes(
                                    {
                                        href: href,
                                    });
                        },
                        view:
                            {
                                onRender({el})
                                {
                                    $(el).css('padding', '1em')
                                }
                            }
                    }
            });


        //Containers first
        Blocks.add('MainContainer', {
            select: true,
            category: "{{ __('dicms::assets.containers') }}",
            label: "{{ __('dicms::assets.containers.main') }}",
            media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M368 80l32 0 0 32-32 0 0-32zM352 32c-17.7 0-32 14.3-32 32L128 64c0-17.7-14.3-32-32-32L32 32C14.3 32 0 46.3 0 64l0 64c0 17.7 14.3 32 32 32l0 192c-17.7 0-32 14.3-32 32l0 64c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32l192 0c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32l0-64c0-17.7-14.3-32-32-32l0-192c17.7 0 32-14.3 32-32l0-64c0-17.7-14.3-32-32-32l-64 0zM96 160c17.7 0 32-14.3 32-32l192 0c0 17.7 14.3 32 32 32l0 192c-17.7 0-32 14.3-32 32l-192 0c0-17.7-14.3-32-32-32l0-192zM48 400l32 0 0 32-32 0 0-32zm320 32l0-32 32 0 0 32-32 0zM48 112l0-32 32 0 0 32-32 0z"/></svg>`,
            content:
                {
                    type: "main-container",
                },
        });

        Blocks.add('DivContainer', {
            select: true,
            category: "{{ __('dicms::assets.containers') }}",
            label: "{{ __('dicms::assets.containers.div') }}",
            media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M368 80l32 0 0 32-32 0 0-32zM352 32c-17.7 0-32 14.3-32 32L128 64c0-17.7-14.3-32-32-32L32 32C14.3 32 0 46.3 0 64l0 64c0 17.7 14.3 32 32 32l0 192c-17.7 0-32 14.3-32 32l0 64c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32l192 0c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32l0-64c0-17.7-14.3-32-32-32l0-192c17.7 0 32-14.3 32-32l0-64c0-17.7-14.3-32-32-32l-64 0zM96 160c17.7 0 32-14.3 32-32l192 0c0 17.7 14.3 32 32 32l0 192c-17.7 0-32 14.3-32 32l-192 0c0-17.7-14.3-32-32-32l0-192zM48 400l32 0 0 32-32 0 0-32zm320 32l0-32 32 0 0 32-32 0zM48 112l0-32 32 0 0 32-32 0z"/></svg>`,
            content:
                {
                    type: "default",
                    name: "Div",
                    attributes:
                        {
                            style: 'padding: 1em;'
                        }
                },
        });

        Blocks.add('HeaderContainer', {
            select: true,
            category: "{{ __('dicms::assets.containers') }}",
            label: "{{ __('dicms::assets.containers.header') }}",
            media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M368 80l32 0 0 32-32 0 0-32zM352 32c-17.7 0-32 14.3-32 32L128 64c0-17.7-14.3-32-32-32L32 32C14.3 32 0 46.3 0 64l0 64c0 17.7 14.3 32 32 32l0 192c-17.7 0-32 14.3-32 32l0 64c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32l192 0c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32l0-64c0-17.7-14.3-32-32-32l0-192c17.7 0 32-14.3 32-32l0-64c0-17.7-14.3-32-32-32l-64 0zM96 160c17.7 0 32-14.3 32-32l192 0c0 17.7 14.3 32 32 32l0 192c-17.7 0-32 14.3-32 32l-192 0c0-17.7-14.3-32-32-32l0-192zM48 400l32 0 0 32-32 0 0-32zm320 32l0-32 32 0 0 32-32 0zM48 112l0-32 32 0 0 32-32 0z"/></svg>`,
            content:
                {
                    type: "header-container",
                },
        });

        Blocks.add('FooterContainer', {
            select: true,
            category: "{{ __('dicms::assets.containers') }}",
            label: "{{ __('dicms::assets.containers.footer') }}",
            media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M368 80l32 0 0 32-32 0 0-32zM352 32c-17.7 0-32 14.3-32 32L128 64c0-17.7-14.3-32-32-32L32 32C14.3 32 0 46.3 0 64l0 64c0 17.7 14.3 32 32 32l0 192c-17.7 0-32 14.3-32 32l0 64c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32l192 0c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32l0-64c0-17.7-14.3-32-32-32l0-192c17.7 0 32-14.3 32-32l0-64c0-17.7-14.3-32-32-32l-64 0zM96 160c17.7 0 32-14.3 32-32l192 0c0 17.7 14.3 32 32 32l0 192c-17.7 0-32 14.3-32 32l-192 0c0-17.7-14.3-32-32-32l0-192zM48 400l32 0 0 32-32 0 0-32zm320 32l0-32 32 0 0 32-32 0zM48 112l0-32 32 0 0 32-32 0z"/></svg>`,
            content:
                {
                    type: "footer-container",
                },
        });

        Blocks.add('SectionContainer', {
            select: true,
            category: "{{ __('dicms::assets.containers') }}",
            label: "{{ __('dicms::assets.containers.section') }}",
            media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M368 80l32 0 0 32-32 0 0-32zM352 32c-17.7 0-32 14.3-32 32L128 64c0-17.7-14.3-32-32-32L32 32C14.3 32 0 46.3 0 64l0 64c0 17.7 14.3 32 32 32l0 192c-17.7 0-32 14.3-32 32l0 64c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32l192 0c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32l0-64c0-17.7-14.3-32-32-32l0-192c17.7 0 32-14.3 32-32l0-64c0-17.7-14.3-32-32-32l-64 0zM96 160c17.7 0 32-14.3 32-32l192 0c0 17.7 14.3 32 32 32l0 192c-17.7 0-32 14.3-32 32l-192 0c0-17.7-14.3-32-32-32l0-192zM48 400l32 0 0 32-32 0 0-32zm320 32l0-32 32 0 0 32-32 0zM48 112l0-32 32 0 0 32-32 0z"/></svg>`,
            content:
                {
                    type: "section-container",
                },
        });

        Blocks.add('NavContainer', {
            select: true,
            category: "{{ __('dicms::assets.containers') }}",
            label: "{{ __('dicms::assets.containers.nav') }}",
            media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M368 80l32 0 0 32-32 0 0-32zM352 32c-17.7 0-32 14.3-32 32L128 64c0-17.7-14.3-32-32-32L32 32C14.3 32 0 46.3 0 64l0 64c0 17.7 14.3 32 32 32l0 192c-17.7 0-32 14.3-32 32l0 64c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32l192 0c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32l0-64c0-17.7-14.3-32-32-32l0-192c17.7 0 32-14.3 32-32l0-64c0-17.7-14.3-32-32-32l-64 0zM96 160c17.7 0 32-14.3 32-32l192 0c0 17.7 14.3 32 32 32l0 192c-17.7 0-32 14.3-32 32l-192 0c0-17.7-14.3-32-32-32l0-192zM48 400l32 0 0 32-32 0 0-32zm320 32l0-32 32 0 0 32-32 0zM48 112l0-32 32 0 0 32-32 0z"/></svg>`,
            content:
                {
                    type: "nav-container",
                },
        });

        Blocks.add('ArticleContainer', {
            select: true,
            category: "{{ __('dicms::assets.containers') }}",
            label: "{{ __('dicms::assets.containers.article') }}",
            media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M368 80l32 0 0 32-32 0 0-32zM352 32c-17.7 0-32 14.3-32 32L128 64c0-17.7-14.3-32-32-32L32 32C14.3 32 0 46.3 0 64l0 64c0 17.7 14.3 32 32 32l0 192c-17.7 0-32 14.3-32 32l0 64c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32l192 0c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32l0-64c0-17.7-14.3-32-32-32l0-192c17.7 0 32-14.3 32-32l0-64c0-17.7-14.3-32-32-32l-64 0zM96 160c17.7 0 32-14.3 32-32l192 0c0 17.7 14.3 32 32 32l0 192c-17.7 0-32 14.3-32 32l-192 0c0-17.7-14.3-32-32-32l0-192zM48 400l32 0 0 32-32 0 0-32zm320 32l0-32 32 0 0 32-32 0zM48 112l0-32 32 0 0 32-32 0z"/></svg>`,
            content:
                {
                    type: "article-container",
                },
        });

        Blocks.add('IContainer', {
            select: true,
            category: "{{ __('dicms::assets.containers') }}",
            label: "{{ __('dicms::assets.containers.icontainer') }}",
            media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M32 32C14.3 32 0 46.3 0 64l0 96c0 17.7 14.3 32 32 32s32-14.3 32-32l0-64 64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L32 32zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 96c0 17.7 14.3 32 32 32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0 0-64zM320 32c-17.7 0-32 14.3-32 32s14.3 32 32 32l64 0 0 64c0 17.7 14.3 32 32 32s32-14.3 32-32l0-96c0-17.7-14.3-32-32-32l-96 0zM448 352c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 64-64 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l96 0c17.7 0 32-14.3 32-32l0-96z"/></svg>`,
            content:
                {
                    type: "i-container",
                },
        });


        //Lists

        Blocks.add('UnOrderList', {
            select: true,
            category: "{{ __('dicms::assets.wrappers') }}",
            label: "{{ __('dicms::assets.wrappers.ul') }}",
            media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M64 144a48 48 0 1 0 0-96 48 48 0 1 0 0 96zM192 64c-17.7 0-32 14.3-32 32s14.3 32 32 32l288 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L192 64zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l288 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-288 0zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l288 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-288 0zM64 464a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm48-208a48 48 0 1 0 -96 0 48 48 0 1 0 96 0z"/></svg>`,
            content:
                {
                    type: "list-group",
                },
        });

        Blocks.add('OrderedList', {
            select: true,
            category: "{{ __('dicms::assets.wrappers') }}",
            label: "{{ __('dicms::assets.wrappers.ol') }}",
            media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M24 56c0-13.3 10.7-24 24-24l32 0c13.3 0 24 10.7 24 24l0 120 16 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-80 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l16 0 0-96-8 0C34.7 80 24 69.3 24 56zM86.7 341.2c-6.5-7.4-18.3-6.9-24 1.2L51.5 357.9c-7.7 10.8-22.7 13.3-33.5 5.6s-13.3-22.7-5.6-33.5l11.1-15.6c23.7-33.2 72.3-35.6 99.2-4.9c21.3 24.4 20.8 60.9-1.1 84.7L86.8 432l33.2 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-88 0c-9.5 0-18.2-5.6-22-14.4s-2.1-18.9 4.3-25.9l72-78c5.3-5.8 5.4-14.6 .3-20.5zM224 64l256 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-256 0c-17.7 0-32-14.3-32-32s14.3-32 32-32zm0 160l256 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-256 0c-17.7 0-32-14.3-32-32s14.3-32 32-32zm0 160l256 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-256 0c-17.7 0-32-14.3-32-32s14.3-32 32-32z"/></svg>`,
            content:
                {
                    type: "ordered-list-group",
                },
        });

        Blocks.add('ListItem', {
            select: true,
            category: "{{ __('dicms::assets.wrappers') }}",
            label: "{{ __('dicms::assets.wrappers.li') }}",
            media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M152.1 38.2c9.9 8.9 10.7 24 1.8 33.9l-72 80c-4.4 4.9-10.6 7.8-17.2 7.9s-12.9-2.4-17.6-7L7 113C-2.3 103.6-2.3 88.4 7 79s24.6-9.4 33.9 0l22.1 22.1 55.1-61.2c8.9-9.9 24-10.7 33.9-1.8zm0 160c9.9 8.9 10.7 24 1.8 33.9l-72 80c-4.4 4.9-10.6 7.8-17.2 7.9s-12.9-2.4-17.6-7L7 273c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l22.1 22.1 55.1-61.2c8.9-9.9 24-10.7 33.9-1.8zM224 96c0-17.7 14.3-32 32-32l224 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-224 0c-17.7 0-32-14.3-32-32zm0 160c0-17.7 14.3-32 32-32l224 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-224 0c-17.7 0-32-14.3-32-32zM160 416c0-17.7 14.3-32 32-32l288 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-288 0c-17.7 0-32-14.3-32-32zM48 368a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"/></svg>`,
            content:
                {
                    type: "list-group-item",
                },
        });

        //Text Utilities


        Blocks.add('Heading', {
            select: true,
            category: "{{ __('dicms::assets.text') }}",
            label: "{{ __('dicms::assets.text.heading') }}",
            media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 64C0 46.3 14.3 32 32 32l48 0 48 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-16 0 0 112 224 0 0-112-16 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l48 0 48 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-16 0 0 144 0 176 16 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-48 0-48 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l16 0 0-144-224 0 0 144 16 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-48 0-48 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l16 0 0-176L48 96 32 96C14.3 96 0 81.7 0 64z"/></svg>`,
            content:
                {
                    type: "heading",
                },
        });

        Blocks.add('Span', {
            select: true,
            category: "{{ __('dicms::assets.text') }}",
            label: "{{ __('dicms::assets.text.span') }}",
            media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M32 32C14.3 32 0 46.3 0 64l0 96c0 17.7 14.3 32 32 32s32-14.3 32-32l0-64 64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L32 32zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 96c0 17.7 14.3 32 32 32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0 0-64zM320 32c-17.7 0-32 14.3-32 32s14.3 32 32 32l64 0 0 64c0 17.7 14.3 32 32 32s32-14.3 32-32l0-96c0-17.7-14.3-32-32-32l-96 0zM448 352c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 64-64 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l96 0c17.7 0 32-14.3 32-32l0-96z"/></svg>`,
            content:
                {
                    type: "span",
                },
        });

        Blocks.add('Paragraph', {
            select: true,
            category: "{{ __('dicms::assets.text') }}",
            label: "{{ __('dicms::assets.text.paragraph') }}",
            media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M192 32l64 0 160 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-32 0 0 352c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-352-32 0 0 352c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-96-32 0c-88.4 0-160-71.6-160-160s71.6-160 160-160z"/></svg>`,
            content:
                {
                    type: "paragraph",
                },
        });

        Blocks.add('link-block', {
            label: "{{ __('dicms::assets.text.link') }}",
            category: "{{ __('dicms::assets.text') }}",
            media: `<svg viewBox="0 0 24 24">
      <path fill="currentColor" d="M3.9,12C3.9,10.29 5.29,8.9 7,8.9H11V7H7A5,5 0 0,0 2,12A5,5 0 0,0 7,17H11V15.1H7C5.29,15.1 3.9,13.71 3.9,12M8,13H16V11H8V13M17,7H13V8.9H17C18.71,8.9 20.1,10.29 20.1,12C20.1,13.71 18.71,15.1 17,15.1H13V17H17A5,5 0 0,0 22,12A5,5 0 0,0 17,7Z"></path>
    </svg>`,
            content: {
                type: 'link',
                editable: false,
                droppable: true,
                style: {
                    display: 'inline-block',
                    padding: '5px',
                    'min-height': '50px',
                    'min-width': '50px'
                }
            },
        });

        Blocks.add('CodeBlock', {
            select: true,
            category: "{{ __('dicms::assets.text') }}",
            label: "{{ __('dicms::assets.text.code') }}",
            media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M392.8 1.2c-17-4.9-34.7 5-39.6 22l-128 448c-4.9 17 5 34.7 22 39.6s34.7-5 39.6-22l128-448c4.9-17-5-34.7-22-39.6zm80.6 120.1c-12.5 12.5-12.5 32.8 0 45.3L562.7 256l-89.4 89.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l112-112c12.5-12.5 12.5-32.8 0-45.3l-112-112c-12.5-12.5-32.8-12.5-45.3 0zm-306.7 0c-12.5-12.5-32.8-12.5-45.3 0l-112 112c-12.5 12.5-12.5 32.8 0 45.3l112 112c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256l89.4-89.4c12.5-12.5 12.5-32.8 0-45.3z"/></svg>`,
            content:
                {
                    type: "code-block",
                },
        });


        Blocks.add('quote', {
            label: "{{ __('dicms::assets.text.quote') }}",
            category: "{{ __('dicms::assets.text') }}",
            media: `<svg viewBox="0 0 24 24">
        <path fill="currentColor" d="M14,17H17L19,13V7H13V13H16M6,17H9L11,13V7H5V13H8L6,17Z" />
    </svg>`,
            content: `<blockquote class="quote">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ipsum dolor sit
      </blockquote>`
        });

        Blocks.add('text', {
            select: true,
            activate: true,
            label: "{{ __('dicms::assets.text.text') }}",
            category: "{{ __('dicms::assets.text') }}",
            media: `<svg viewBox="0 0 24 24">
        <path fill="currentColor" d="M18.5,4L19.66,8.35L18.7,8.61C18.25,7.74 17.79,6.87 17.26,6.43C16.73,6 16.11,6 15.5,6H13V16.5C13,17 13,17.5 13.33,17.75C13.67,18 14.33,18 15,18V19H9V18C9.67,18 10.33,18 10.67,17.75C11,17.5 11,17 11,16.5V6H8.5C7.89,6 7.27,6 6.74,6.43C6.21,6.87 5.75,7.74 5.3,8.61L4.34,8.35L5.5,4H18.5Z" />
      </svg>`,
            content: {
                type: 'text',
                content: '{{ __('dicms::assets.text.text.content') }}',
                style: { padding: '10px' },
            }
        });


        Blocks.add('hr-separator', {
            label: "{{ __('dicms::assets.decorations.hr') }}",
            category: "{{ __('dicms::assets.decorations') }}",
            media: '<hr style="height: 5px; background-color: #fff; " />',
            content: '<hr />'
        });

        Blocks.add('image', {
            select: true,
            category: "{{ __('dicms::assets.decorations') }}",
            activate: true,
            label: "{{ __('dicms::assets.decorations.image') }}",
            media: `<svg viewBox="0 0 24 24">
        <path fill="currentColor" d="M21,3H3C2,3 1,4 1,5V19A2,2 0 0,0 3,21H21C22,21 23,20 23,19V5C23,4 22,3 21,3M5,17L8.5,12.5L11,15.5L14.5,11L19,17H5Z" />
      </svg>`,
            content: {
                style: { color: 'black' },
                type: 'image',
            }
        });

        Blocks.add('video', {
            select: true,
            category: "{{ __('dicms::assets.decorations') }}",
            label: "{{ __('dicms::assets.decorations.video') }}",
            media: `<svg viewBox="0 0 24 24">
        <path fill="currentColor" d="M10,15L15.19,12L10,9V15M21.56,7.17C21.69,7.64 21.78,8.27 21.84,9.07C21.91,9.87 21.94,10.56 21.94,11.16L22,12C22,14.19 21.84,15.8 21.56,16.83C21.31,17.73 20.73,18.31 19.83,18.56C19.36,18.69 18.5,18.78 17.18,18.84C15.88,18.91 14.69,18.94 13.59,18.94L12,19C7.81,19 5.2,18.84 4.17,18.56C3.27,18.31 2.69,17.73 2.44,16.83C2.31,16.36 2.22,15.73 2.16,14.93C2.09,14.13 2.06,13.44 2.06,12.84L2,12C2,9.81 2.16,8.2 2.44,7.17C2.69,6.27 3.27,5.69 4.17,5.44C4.64,5.31 5.5,5.22 6.82,5.16C8.12,5.09 9.31,5.06 10.41,5.06L12,5C16.19,5 18.8,5.16 19.83,5.44C20.73,5.69 21.31,6.27 21.56,7.17Z" />
      </svg>`,
            content: {
                type: 'video',
                src: 'img/video2.webm',
                style: {
                    height: '350px',
                    width: '615px'
                }
            }
        });

        Blocks.add('map', {
            select: true,
            category: "{{ __('dicms::assets.decorations') }}",
            label: "{{ __('dicms::assets.decorations.map') }}",
            media: `<svg viewBox="0 0 24 24">
        <path fill="currentColor" d="M20.5,3L20.34,3.03L15,5.1L9,3L3.36,4.9C3.15,4.97 3,5.15 3,5.38V20.5A0.5,0.5 0 0,0 3.5,21L3.66,20.97L9,18.9L15,21L20.64,19.1C20.85,19.03 21,18.85 21,18.62V3.5A0.5,0.5 0 0,0 20.5,3M10,5.47L14,6.87V18.53L10,17.13V5.47M5,6.46L8,5.45V17.15L5,18.31V6.46M19,17.54L16,18.55V6.86L19,5.7V17.54Z" />
      </svg>`,
            content: {
                type: 'map',
                style: { height: '350px' }
            }
        });

        //Secial
        Blocks.add('MenuItem', {
            select: true,
            category: "{{ __('dicms::assets.special') }}",
            label: "{{ __('dicms::assets.menu.item') }}",
            media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z"/></svg>`,
            content:
                {
                    type: "menu-item",
                },
        });

    };
</script>
