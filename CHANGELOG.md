# Changelog

All notable changes to `LaravelDropInCMS` will be documented in this file

## 0.7.5.7

* Changes Backups
    * Download Backup now zips the file
    * When restoring, you can use a regular JSON file or a zip file
    * The name of the backup now includes a time stamp
* Updates GrapesJS editor (again)
    * Fixes the top control not working
    * Changes the layout to a custom layout.
    * Makes fullscreen include all the elements instead of just the editor.
* Updates the Changelog

## 0.7.5.6

Changes the way foreign key disabling is done.

## 0.7.5.5

Removes the decoding.

## 0.7.5.4

Fixes the show for pages when it is a plugin page. Preview should not appear.

## 0.7.5.3

Adds the correct unique constrain for pages.

## 0.7.5.2

removes widgets. Seriously thinking about removing widgets alltogether.
Changes Highlightable Posts, related posts and tags, and moved them completely to their own GrapesJs plugin. This allows
for complete customization of all the moving pieces and allowing all the customization to happen through grapes js.
Cleaned up the editor CSS, it no looks better an the full screen plugin works much better too.
Moves highlightable posts to it's own section itn eh blogs.

## 0.7.5.1

Updates the license in composer.

## 0.7.5

This patch re-couples Headers, Footers, Pages, Sheets, and Scripts to Sites. It reworks a lot of the front login and
preview login, as well as some of the plugin login. Blogger will be released with the same patch number with all the
changes.

To note in this patch, to migrate the databse manually from the old .7.3 version:

1. Make a DB BACKUP!
2. Make a DiCMS back up!
3. Run the following commands in the database:

```
ALTER TABLE `dicms_css_sheets`
	ADD COLUMN `site_id` BIGINT UNSIGNED NOT NULL AFTER `id`;
UPDATE `dicms_css_sheets` SET `site_id`=1;
ALTER TABLE `dicms_footers`
	ADD COLUMN `site_id` BIGINT UNSIGNED NOT NULL AFTER `id`;
UPDATE `dicms_footers` SET `site_id`=1;
ALTER TABLE `dicms_headers`
	ADD COLUMN `site_id` BIGINT UNSIGNED NOT NULL AFTER `id`;
UPDATE `dicms_headers` SET `site_id`=1;
ALTER TABLE `dicms_js_scripts`
	ADD COLUMN `site_id` BIGINT UNSIGNED NOT NULL AFTER `id`;
UPDATE `dicms_js_scripts` SET `site_id`=1;
ALTER TABLE `dicms_pages`
	ADD COLUMN `site_id` BIGINT UNSIGNED NOT NULL AFTER `id`;
UPDATE `dicms_pages` SET `site_id`=1;
ALTER TABLE `dicms_js_scripts`
	ADD COLUMN `active` TINYINT UNSIGNED NOT NULL DEFAULT '0' AFTER `link_type`;
UPDATE `dicms_js_scripts` SET `active`=1;
ALTER TABLE `dicms_css_sheets`
	ADD COLUMN `active` TINYINT UNSIGNED NOT NULL DEFAULT '0' AFTER `link_type`;
UPDATE `dicms_css_sheets` SET `active`=1;
ALTER TABLE `dicms_js_scripts`
	ADD COLUMN `order_by` TINYINT UNSIGNED NOT NULL DEFAULT '0' AFTER `active`;
ALTER TABLE `dicms_css_sheets`
	ADD COLUMN `order_by` TINYINT UNSIGNED NOT NULL DEFAULT '0' AFTER `active`;
```

4. Make a DiCMS backup file, this is your post file
5. Update the system
6. DROP the DiCMS tables
7. Re-run all migrations
8. Restore from DiCMS

You should now have all your content back.

## 0.7.1

First bugfix. Issue with dataitem thumbs

## 0.7.0

* API's are complete
    * All routes are defined, but not documented
    * Testing for all API calls
* Updates the license to an MIT license.

## 0.6.2.1

two bug fixes: changes defaultSite to currentSite in the pages to deal with
inactive sites and changes the behavior of the css attributes to not escape
characters.

## 0.6.2

A small feature update that adds an initial HTML entry into Headers, Footers and Pages.

## 0.6.1

A significant update, but not enough to bump it up to next revision. 
The really meaty stuff will be in blogger.  For this update the following 
enhancements were done:

* New Feature: Metadata!
  * This was not in the original requirements, but it ws needed for blogger 
and it made more sense to add it here
  * Site now has default metadata
  * Each individual page can have metadata assigned to it
  * Made shortcuts to assign Twitter/OG metadata.
* Moved to a font awesome kit for more icons.
* Removed the text editor component from here. DiCMS doesn't use it anyways.

## 0.6.0
A lot of work has gone into this update, which includes so much that it is enough
to complete my first milestone! The following new features are all included:

* Introduce and complete widgets
* Finish all plugin interfaces so that the structure doesn't change anymore.
* All Editor improvements are done.
* REST API's need to be started by now, so that they can be included in the plugin interface

Along with the milestone release, the following was also done:

* The first widget, a page counter that will count visitors to the page
* The asset manager now supports a folder-like structure

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
