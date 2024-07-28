<?php

return
[
    'sites.name' => 'You must enter a name for this site. It must be unique',
    'headers.name' => 'You must enter a name for this header. It must be unique',
    'content_headers.name' => 'You must enter a name for this page header. It must be unique',
    'footers.name' => 'You must enter a name for this page footer. It must be unique',
    'sheets.name' => 'You must enter a name for this css sheet.',
    'scripts.name' => 'You must enter a name for this js script.',
    'active.warning' => 'This site is currently active. Any changes you make here will be immediately seen by any clients viewing your site. It is recommended you make changes in another site, then make that site live.',
    'active.danger.deactivate' => 'This is the active site on the system. If you deactivate this site, there will be be no sites for your CMS to serve and will return 404 errors.',
    'active.warning.activate' => 'Activating this site will make the current active site (if any) deactivated and will make this current site the one that clients will see.',
    'active.warning.archive' => 'Archiving a site will make it so that you can no longer see it.',
    'published.warning' => 'This page is currently published. Any changes you make here will be immediately seen by any clients viewing your site.  It it recommended you unpublish this page until your changes are done.',
    'published.danger.unpublish' => 'This page is active in your site. If you unpublish this page, anyone trying to access this age will be served a 404 error.',
    'published.warning.publish' => 'Publishing this page will make it active and available for your clients to access..',
    'pages.name' => 'You must enter a name for this page. It must be unique',
    'pages.slug' => 'A page slug is required. The combination of a slug and a path, makes a URL, the URL must be unique.',
    'pages.url' => 'The URL is a duplicate. Please change the slug or path to make it unique.',
    'menus.name' => 'You must enter a name for this menu. It must be unique',
];
