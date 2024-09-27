# Changelog

All notable changes to `LaravelDropInCMS` will be documented in this file

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
