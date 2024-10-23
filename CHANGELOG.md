# Changelog

All notable changes to `LaravelDropInCMS` will be documented in this file

## 0.5.0

Large enough update to bump it to the next revision.
The major features of this release are:

* Asset Manager
    * Enabled the filename editor
    * Uploaded files should now be hashed
    * Added url copy link
    * File uploads should now work much better
    * Large view now display properly
    * Added a mini asset manager version that is accessible everywhere in admin and can interact with elements in the page
    * Looks much better.
* New Look!
    * Added icons instead of words, but kept the words as titles/alts
    * Made all the admin pages use a template for better consistency across pages
    * The nav has been redesigned with new menu options and the current site on the top
    * There is now a page when no site exists
    * Installation and set up are now much easier and semi-guided.
* New Options
    * You can now choose what kind of tags you want to use for footers/headers/menus
    * Menu structure is now configurable
    * Site options include favicons and header locations.
* Sites can now be archived
* All things can be duplicated
* Starting fresh now directs you to create a site or restore one.
* Started testing, some basic routes for now.
* Sites and pages can now be previewed.
* Added a lot of new blocks to the web editor
    * Almost every html-element has been added
    * New blocks with options, such as headings or menu items
    * Cleaned up the code so that no more extra wrappers are added.
* Changed Architecture
    * No menus
    * Instead, everything is done through Headers and HTML
    * Pages are now more complete so you can customize it better
    * All HTML-aware elements now extend a base class
    * Pages/Headers/Menus are no longer site-specific
* Cleaned up the web editor to make it work better.
* All JS/CSS text was moved to its own link, instead of pasting it to the page.
* Plugin interfaces have been updated.
    * Moved the whole architecture to use Pages
    * Made the Plugin Interface a single interface
    * Added the ability for plugins to GrapesJs plugins

## 0.4.1

Updates the asset manager to better work with S3-type remote storage locations. 
It does this by relying less on the php functions file_get_contents
and relying instead on Laravel's storage Facade.

## 0.4.0

This update is large enough to be considered its own version, so I went ahead and updated
to 0.4.0 and will release bug fixes under this revision. I will most likely 
release all the bug fixes as I'm working on my other project, [FabLMS](https://dev.kalinec.net),
since I'll be using the CMS to update things in my website.

The major features of this release are:

* The system can now be fully backed up in all ways!
  * Programmatically
  * Over the web
  * Through an Artisan Command
  * See the README for more info.
* Installed Live Wire in the system!
  * Js Scripts and Css Sheets are now handled through a single
  livewire component that allows for adding, removing and sorting.
* Created an Asset Storage Management
  * Another Livewire component!
  * You can upload, delete, view original, filter, and select.
  * Integrated into the text editor and the website editor
  * Auto-scales down images to preset heights.
  * Auto-creates thumbnails to preset sizes.
  * Makes every photo a PNG. Not sure about this one yet.
* Updated the Text Editor
  * Lots of new plugins are now integrated.
  * The new Asset Manager system is integrated.
* Updated the Website Editor
  * Integrated with the new Asset Manager.
  * Added more corporate Logos
  * Fixed annoying single divs that didn't display right.
  * Made other elements look better.
* Plugin Updates!
  * You can now set your own Header and Footer when showing off your plugin
  * You can now set your own Css/Js files when showing off your plugin.
* Clean ups!
  * Database migrations are ordered/name pretty now.
  * SOne config items were taken out, some were actually used now.
  * Some more packages requirements have been added.


## 0.3.2 - 0.3.7

Lots of debugging. Lots of testing. Read the commits for more information

## 0.3.1

Fully removes all traces of content headers, which was causing a major issue.


## 0.3.0

The official documentation of this project with a full release of the Blogger plugin is
included in this update.  It also groups all the published files under
the `dicms` group name.

## 0.1.1 - 0.1.3

All these versions were sacrificed to the god of learning how to set up composer packages and packagist so I can get a laravel package done.

## 0.1.0

Initial release
