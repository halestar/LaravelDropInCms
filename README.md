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

## Installation

The installation assumes that you have a Laravel app that you have already built or are designing. Perhaps it has an authenticated section, perhaps not. Perhaps you're just starting a new app and just run the ui/auth component and have a simple admin account, or have a sprawling list of users and guards. It does not matter, the first thing to do is to import the package into your laravel app by executing:

    composer require halestar/laravel-drop-in-cms

We next publish the vendor files by doing:

    php artisan vendor:publish --provider=halestar\LaravelDropInCms\Providers\CmsServiceProvider

Which will publish the migration files and the config files. Before you run the migration, open the config file and look at the initial config. The only thing you may want to change at this time is the `table_prefix` option, if you would like to customize the table names for the CMS tables. Once you're happy, run:

    php artisan migrate

to migrate the tables.  The last thing to do before getting started is to configure the routes. Go to your main routes file (in a normal laravel installation this would be the `routes/web.php` file) and add two entries **at the end of the file**:

    Route::prefix('admin')->group(function()
        { 
            \halestar\LaravelDropInCms\DiCMS::adminRoutes();
        });

    \halestar\LaravelDropInCms\DiCMS::publicRoutes();

It is important to understand what these two entries mean and how to configure them correctly. The first entry sets up all the admin routes, that is, all the routes to manage the CMS, to run off the `/admin` url. If you go to `https://yoursite.com/admin` then you should see the front page of your CMS admin, asking you to create your first new site.

If you, instead, wanted to run them from the url `https://yoursite.com/cms` you would make the entry:

    Route::prefix('cms')->group(function()
        {
            \halestar\LaravelDropInCms\DiCMS::adminRoutes();
        });

If you would like to make sure that only authenticated users could reach this admin site you can change this to:

    Route::prefix('admin')->middleware('auth')->group(function()
        {
            \halestar\LaravelDropInCms\DiCMS::adminRoutes();
        });

The second entry tells the system where to show the CMS website that you've built. If you don't wrap it around any prefix, then it will show up directly on the root of your website. This is why it is important to put this at the bottom of the routes files, as it will catch all the routes below it.  Alternatively, if you would like your app to show the website on `https://yoursite.com/front` for example, you could set up the entry as:

    Route::prefix('front')->group(function()
    {
        \halestar\LaravelDropInCms\DiCMS::publicRoutes();
    });

Once you have these two routes set up, the system is up and running. The first thing you should do is login to the admin section of the CMS and create a new site. There will be instructions below for how to create your first new site.

