let loadPlugins =
[
    ClassicEditor,
    Essentials,
    Bold,
    Italic, Strikethrough, Subscript, Superscript, Underline, Code,
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
    AutoImage,
    List,
    TextTransformation,
    BlockQuote,
    CodeBlock,
    FindAndReplace,
    Heading, Alignment,
    Highlight, AutoLink, Link, MediaEmbed, SpecialCharacters, SpecialCharactersEssentials, Table, TableToolbar
];

import
{
ClassicEditor,
Essentials,
Bold,
Italic, Strikethrough, Subscript, Superscript, Underline, Code,
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
AutoImage,
List,
TextTransformation,
BlockQuote,
CodeBlock,
FindAndReplace,
Heading, Alignment,
Highlight, AutoLink, Link, MediaEmbed, SpecialCharacters, SpecialCharactersEssentials, Table, TableToolbar
}
from 'ckeditor5';

let ckOptions = {
    plugins: loadPlugins,
    toolbar:
    {
        items:
        [
            'undo', 'redo', '|', 'bold', 'italic', 'underline', 'strikethrough', 'code', 'subscript','superscript', '|',
            'heading', 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', 'blockQuote', 'codeBlock',  '|', 'alignment', 'bulletedList', 'numberedList',
            'outdent', 'indent', 'sourceEditing', '|', 'insertImage', 'findAndReplace', 'link', 'mediaEmbed', 'specialCharacters', 'insertTable'
        ],
        shouldNotGroupWhenFull: true
    },
    image:
    {
        toolbar:
        [
            'imageStyle:block',
            'imageStyle:side',
            '|',
            'toggleImageCaption',
            'imageTextAlternative',
            '|',
            'linkImage'
        ],
        insert:
        {
            integrations: [ 'upload', 'url' ]
        }
    },
    table:
    {
        contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
    },
    simpleUpload:
    {
        // The URL that the images are uploaded to.
        uploadUrl: '{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.upload') }}',

        // Enable the XMLHttpRequest.withCredentials property.
        withCredentials: true,

        // Headers sent along with the XMLHttpRequest to the upload server.
        headers:
        {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        }
    },
    htmlSupport:
    {
        allow:
        [
            {
                name: /.*/,
                attributes: true,
                classes: true,
                styles: true
            }
        ],
        disallow:
        [
            {
                name: 'heading'
            }
        ]
    }
}
