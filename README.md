# Laravel Drop-In Content Management System
## DiCMS for short

DiCMS is a content management system (CMS) made for programmers and people that need a simple CMS that gets out of the way. The issue with most CMS is that they try to do everything for you. Every CMS out there comes with its own permissions package, or users package or management for assets, etc.  As a web app programmer most of the apps we build are internal apps. They come with a wonderful admin panel and maybe a users area or such, but the front side is usually left as an empty space with a promise "to be filled..."

So I built a simple CMS that is extensible with the following goals:

 - No Users: The system will not be bothered with users.
 - No Permissions: The system will not come with any permission system.
 - Separation of Code: The CMS should be a package that you import, set up and then concentrate on building your front page.
 - Structure over Form: This is not a full-blown CMS system that lets you do everything under the sun and build your dream web page. If you want that, go use [Word Press](https://wordpress.com/) they have an amazing interface and more plugins that you will ever need. This CMS will be basic and structured for websites.
 - Made for Programmers: This CMS is not for the end-user. It's made for programmers so that they can throw something together in **front** of their brand-new, awesome new app they just built.
 - Security is a Must: Just because we don't deal with permissions or users does not mean that they're not there in the system. They system should be able to be protected with permissions, users and the rest. They should just not get in the way. 

# Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Building your first Site](#building)
- [Securing your CMS](#securing)
- [Backing up your CMS](#backup)
- [Plugin System](#plugins)
- [Roadmap to 1.0](#roadmap)

<a id="features"></a>
## Features

The reason I built this package is because of my other development app, [FormativeLMS](https://github.com/halestar/FormativeLMS), which I'm currently in the process of building. This app is mainly an app that works with authenticated users, with not much happening in the front end. I wanted to create a simple blog that details my struggles and thought processes building this framework and host it in my [domain](https://dev.kalinec.net).

However, what I ran into was that it was really hard to put in a front end in a laravel app. The easiest solution was to make a couple of static html pages, but I wanted to build a blog, so that was out. I started looking into blogging software and CMS software and found that all of them ame with baggage. My #1 gripe being that they all had their own users or permissions system. I wanted to use my own and not deal with a 3rd party. I just wanted 5 pages that I could maybe download a design from somewhere and type up some info. A small blog that I could type my thoughts and that's all.

But that was clearly too much. So I started building my own small thing, and it was like re-inventing the wheel. I didn't want to do this, I wanted something that I could "Drop-In" into my project. So I came out with this list of features:

 - No Users: but allow for the project to have them. 
 - There are 2 parts to a CMS: the internal one and external one, assign routes to each section, but let the app decide where they go
 - We need to secure things, so we use [Policies](https://laravel.com/docs/11.x/authorization#generating-policies) to guard all aspects of the cms, but let the app override them to their own needs.
 - A plugin system to extend it, with the first plugin being a blogger.
 - A content editor that I can use to place HTML elements. I chose the most excellent [GrapesJS](https://grapesjs.com/) for this.
 - A text editor to be able to edit rich-text content. I chose [CKEditor](https://ckeditor.com/) for this, but I'm not yet conviced.
 - Have more than one site, archive them switch between them, share some assets between them.
 - Export and Restore everything very easily. This needs to be possible through files, programmatically and through an artisan command.
 - Least reliance on the host app's environment. Load our own layout and "force" the user into it. This way there's a clear separation of the app, but it can be customizable through view editing. Same goes for the front end. The host app can be built using tailwind, but this CMS uses bootstrap and the front end you're using is not using a framework but a css you got from a russian site that you really, *really* like. 

<a id="installation"></a>
## Installation

The installation assumes that you have a Laravel app that you have already built or are designing. Perhaps it has an authenticated section, perhaps not. Perhaps you're just starting a new app and just run the ui/auth component and have a simple admin account, or have a sprawling list of users and guards. It does not matter, the first thing to do is to import the package into your laravel app by executing:

    composer require halestar/laravel-drop-in-cms

We next publish the vendor files by doing:

    php artisan vendor:publish --tag=dicms

And migrate your tables

    php artisan migrate

If you would like to use your own Policies, but want to base it off existing 
policies, you can export all the DiCMS policies by doing:

    php artisan vendor:publish --tag=dicms-policies

The last thing to do before getting started is to configure the routes. Go to your main routes file (in a normal laravel installation this would be the `routes/web.php` file) and add two entries **at the end of the file**:
```php
    Route::prefix('admin')->group(function()
        { 
            \halestar\LaravelDropInCms\DiCMS::adminRoutes();
        });

    \halestar\LaravelDropInCms\DiCMS::publicRoutes();
```
It is important to understand what these two entries mean and how to configure them correctly. The first entry sets up all the admin routes, that is, all the routes to manage the CMS, to run off the `/admin` url. If you go to `https://yoursite.com/admin` then you should see the front page of your CMS admin, asking you to create your first new site.

If you, instead, wanted to run them from the url `https://yoursite.com/cms` you would make the entry:
```php
Route::prefix('cms')->group(function()
    {
        \halestar\LaravelDropInCms\DiCMS::adminRoutes();
    });
```
If you would like to make sure that only authenticated users could reach this admin site you can change this to:
```php
Route::prefix('admin')->middleware('auth')->group(function()
    {
        \halestar\LaravelDropInCms\DiCMS::adminRoutes();
    });
```
The second entry tells the system where to show the CMS website that you've built. If you don't wrap it around any prefix, then it will show up directly on the root of your website. This is why it is important to put this at the bottom of the routes files, as it will catch all the routes below it.  Alternatively, if you would like your app to show the website on `https://yoursite.com/front` for example, you could set up the entry as:
```php
Route::prefix('front')->group(function()
{
    \halestar\LaravelDropInCms\DiCMS::publicRoutes();
});
```

Once you have these two routes set up, the system is up and running. The first thing you should do is login to the admin section of the CMS and create a new site. There will be instructions below for how to create your first new site.

<a id="building"></a>
## Building your First Site

You now have your CMS system installed, and you can access the admin section of the CMS. Note that if you try to access anything in the front side of the CMS nothing will come up. That is because we don't have an active site yet! The first step is build one. From the admin menu, hit that "Create New Site" button and enter a name for your site and a title that will show up on the window. Click create and let it take you to your new site.

This CMS is not meant to be creative and allow you to do whatever you want. If that is something that you're looking for, go to word press or write your own static pages. They point of this CMS is to make this structured and logical so that you can push out content fast.

Evey site has attached to it certain components that make up the total site. The componts are:

- CSS Files: Either files, links or actual css that you write.
- JS Files: Script files that run javascript code. Also, libraries that you can import through links.
- Headers: A Page Header that appears on the top of every page.
- Footers: A Page Footer that appears on the bottom of every page.
- Pages: A list of pages with URLS attached to them that display content.
- Menus: A menu that displays on top of the site.

A site can have multiple of these, but only one set to as the "Default". The Default takes over if a specific one isn't set on the page.  The idea is to create a Header, Footer, Menu and link some Css sheets and Js script to make a good site outline, then fill in the content by creating pages.

Once you have all the things set up, make sure you activate the site. This will make your site "live" and you can see the results by heading to the front URL.

<a id="securing"></a>
## Securing Your CMS

The key to securing your CMS is two-fold, through routes and through Policy Objects.

### Securing Through Routes

In a way, this has been done when you installed the project. Simply by wrapping the admin routes in an auth section, or applying a permissions middleware will secure all the CMS admin routes. You can go further by creating a custom middleware that will check permissions or users before giving access to the admin routes. This, essentially, gives you a coarse protection.

For most projects this will be enough. However, you can make the protections more fine-grained by the use of Policies.

### Securing Through Policies

Securing through Policies is the most fine-grained approach to permissions that you can have. Essentially every model that the CMS uses has a Policy class attached to it that defines what permissions a user has when manipulating this model. All the models in DiCMS have Policies and all policies can be overridden in the config file. The list of models and policies are found in the `config/dicms.php` config file in the `$policies` array. It will usually look like this:
```php
'policies' =>
[
    \halestar\LaravelDropInCms\Models\Site::class => env('DICMS_SITE_POLICY', \halestar\LaravelDropInCms\Policies\SitePolicy::class),
    \halestar\LaravelDropInCms\Models\Header::class => env('DICMS_HEADER_POLICY', \halestar\LaravelDropInCms\Policies\HeaderPolicy::class),
    \halestar\LaravelDropInCms\Models\Footer::class => env('DICMS_FOOTER_POLICY', \halestar\LaravelDropInCms\Policies\FooterPolicy::class),
    \halestar\LaravelDropInCms\Models\CssSheet::class => env('DICMS_CSS_SHEET_POLICY', \halestar\LaravelDropInCms\Policies\CssSheetPolicy::class),
    \halestar\LaravelDropInCms\Models\JsScript::class => env('DICMS_JS_SCRIPT_POLICY', \halestar\LaravelDropInCms\Policies\JsScriptPolicy::class),
    \halestar\LaravelDropInCms\Models\Page::class => env('DICMS_PAGE_POLICY', \halestar\LaravelDropInCms\Policies\PagePolicy::class),
    \halestar\LaravelDropInCms\Models\Menu::class => env('DICMS_MENU_POLICY', \halestar\LaravelDropInCms\Policies\MenuPolicy::class),
],
```
The left side of the array is the model, such as a Site, or a Page, the right hand side is the Policy that is attached to it.  The default policy is extremely permissive, allowing users to do everything. However, you can change this by creating your own policy and overriding it.

For example, lets look at the `\halestar\LaravelDropInCms\Models\Site` model.This model is the representation of a Site. We see that it has the class `\halestar\LaravelDropInCms\Policies\SitePolicy` attached. Looking at this class the definition is quite simple:
```php
class SitePolicy
{
/**
* Determine whether the user can view any models.
*/
public function viewAny(User $user = null): bool
{
return true;
}

/**
 * Determine whether the user can view the model.
 */
public function view(User $user = null, Site $site = null): bool
{
    return true;
}

/**
* Determine whether the user can create models.
*/
public function create(User $user = null): bool
{
    return true;
}
...
```
You can immediately see that all functions in this Policy (and indeed, all policies) return true, meaning that permission is granted.  So creating site will always be allowed by everyone.  But what if you wanted to change this?  What if you wanted the creation of sites to only be available to users with a specific permission?  Well, we can extend the SitePolicy class and change it into:
```php
class MySitePolicy extends \halestar\LaravelDropInCms\Policies\SitePolicy
{
    public function create(User $user = null): bool
    {
        return $user->can('create sites');
    }
}
```
Then we alter the policy's config:
```php
'policies' =>
    [
        \halestar\LaravelDropInCms\Models\Site::class => env('DICMS_SITE_POLICY', \App\Policies\MySitePolicy::class),
        ...
    ],
```
Alternatively, we can add an env variable to our `.env` file:

```php
DICMS_SITE_POLICY=\App\Policies\MySitePolicy::class
```

Now, only the correct users can create sites.

You can publish all the Policies in the system and then tweak them to your heart's
content by executing:

    php artisan vendor:publish --tag=dicms-policies

<a id="securing"></a>
## Backing up your CMS

There are 3 ways to back up your CMS. Backing up means, as of version 0.4.0 a
string or file representation of your database structure that can then be
given to a restore method.

### Programmatically

The Site can be backed up programmatically by obtaining an instance of the 
`halestar\LaravelDropInCms\Models\SystemBackup` object by instantiating it,
such as:

```php
// This will actually generate a backup instance. So long as 
// this object persist in memory, you have a snapshot of your 
// database at the time this command is executed.
$backup = new SystemBackup();
// to access the backup data, get it by doing:
$backupData = $backup->getBackupData();
// $backupData now has a string containing all the information
// in your database 
```

You can then use that data to restore it by passing it to the `halestar\LaravelDropInCms\Models\SystemBackup` as a 
static function such as:
```php
SystemBackup::restore($backupData);
```
Your site's database is now restored.

### Over the Web

You can back up and restore your website over the web by going to the CMS Admin site of your DiCMS install and selecting the 
Backups menu options. From there you'll get a page that will allow you to download a backup (aptly named backup.json), or 
select a file that you exported previously and restore the site.

### Through Artisan

You can back up and restore the site through artisan commands. Use
```php
php artisan dicms:backup-cms
```
You can add the optional `--file=file_out.json` option to save the backup to a file.  To restore the Site:
```php
php artisan dicms::backup-cms --file=file_out.json 
```
This will load the data from the `file_out.json` file and restore your Site

### Scheduled Backups

Since this project is primary aimed at developers who want to showcase a project, it was made to be easy to 
survive database wipes. 

For example, lets say you have a demo in your app that you host for people to play with. You don't
give them access to the CMS (through Policies), and you want to wipe and re-seed the database 
every night. You can do this in the scheduler calling this function every night:
```php
public function cleanUpDb()
{
    // save the cms data.
    $cmsSave = new SystemBackup();
    $cmsData = $cmsSave->getBackupData();
    // refresh the db, and seed it since it will probably have demo data
    $this->call('migrate:fresh', ['--seed' => true]);
    // restore the CMS data
    $cmsSave->restore($cmsData);
    // Optimize some stuff
    $this->call('optimize:clear');
    $this->call('optimize');
}
```

<a id="plugins"></a>
## Plugin System

DiCms has a plugin system!

Why you may ask? Because I wanted to add a blogging mechanism, but it would have complicated the development of this system. I wanted to keep it simple in what it did. The only solution then, was to make it extensible. As such, plugging support is built in by default and the first plugging, [DiCmsBlogger](https://github.com/halestar/DiCmsBlogger) was created to both add a blogging component to DiCms and to explain how to create plugins.

If you're interested in creating plugins, head over to [DiCmsBlogger](https://github.com/halestar/DiCmsBlogger) GitHub page and a description on how to build plugins will be included there.

<a id="roadmap"></a>
## Roadmap to 1.0

At the time of writing this, this package is not what I consider "released". 
My plan is officially release as a v1.0 once all the features are built. 
This space here is meant to detail what features and upgrades I consider 
essential to release a v1.0

These requirements are subject (and in fact, most likely) to change and 
I will cross them out (probably) as I build things.

The following features need to be implemented in order to release v1.0:

 - Comments need to be added. Heredoc and config comments.
 - The user interface needs to be overhauled. It is very ugly and needs to be updated.
 - ~~The system needs to be able to be backed-up programmatically through an API and through an artisan command~~
 - ~~Make css and js script re-arrangeable~~
 - Archive and de-archive sites
 - Preview needs to be enabled
 - ~~Better asset supports for images and stuff~~
 - ~~Better editor support~~
 - GrapesJs needs to be heavily customized
 - Make sure it shows up nice on mobile
 - Add REST API to all models with Policy hooks
 - ~~Upgrade the plugin system to allow for customization of css/js scripts, headers and footers from the front page.~~
 - Document how to create a simple site, with pictures.
 - Make backups more secure by zipping, creating SHA's etc.
 - Provide a sample backup file that will build a default site.
 - Add alternatives for other kinds of settings mechanisms, such as redis.
 - ~~Add publishable policies for users to easily extend. Possibly through artisan commands.~~
 - ~~Create an asset storage management system for centralized, shared management. Maybe a plugin?~~
 - Duplicate sites.
 - Update README to include better instructions and define the roadmap to include version milestones.

Other things may be added to this list, or taken away.
