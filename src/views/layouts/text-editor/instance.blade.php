<script type="module">
    import {
        ClassicEditor,
        Essentials,
        Bold,
        Italic,
        Font,
        Paragraph,
        SourceEditing,
        Indent, IndentBlock,
        GeneralHtmlSupport,
        SimpleUploadAdapter,
        Image,
        ImageCaption,
        ImageResize,
        ImageStyle,
        ImageToolbar,
        ImageUpload,
        LinkImage,
        ImageInsert,
        AutoImage
    } from 'ckeditor5';

    ClassicEditor
        .create( document.querySelector( '{{ $eConfig['editor'] }}' ), {
            plugins: [
                ClassicEditor,
                Essentials,
                Bold,
                Italic,
                Font,
                Paragraph,
                SourceEditing,
                Indent, IndentBlock,
                GeneralHtmlSupport,
                SimpleUploadAdapter,
                Image,
                ImageCaption,
                ImageResize,
                ImageStyle,
                ImageToolbar,
                ImageUpload,
                LinkImage,
                ImageInsert,
                AutoImage
            ],
            toolbar: {
                items: [
                    'undo', 'redo', '|', 'bold', 'italic', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                    'outdent', 'indent', 'sourceEditing', '|', 'insertImage'
                ]
            },
            image: {
                toolbar: [
                    'imageStyle:block',
                    'imageStyle:side',
                    '|',
                    'toggleImageCaption',
                    'imageTextAlternative',
                    '|',
                    'linkImage'
                ],
                insert: {
                    integrations: [ 'upload', 'url' ]
                }
            },
            simpleUpload: {
                // The URL that the images are uploaded to.
                uploadUrl: '{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.upload') }}',

                // Enable the XMLHttpRequest.withCredentials property.
                withCredentials: true,

                // Headers sent along with the XMLHttpRequest to the upload server.
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            },
            htmlSupport: {
                allow: [
                    {
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }
                ],
                disallow: [
                    {
                        name: 'heading'
                    }
                ]
            }
        } )
        .then( newEditor => { editor = newEditor; } )
        .catch(  error => { console.error( error ); } );
</script>
