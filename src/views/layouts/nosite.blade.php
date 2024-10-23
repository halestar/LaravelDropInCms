<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>DiCms: No Site</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</head>
<body>

    <section class="py-6">
    <div class="container bg-light min-vh-50 py-6 d-flex justify-content-center align-items-center" style="max-width:1920px">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="lc-block mb-4">
                    <div editable="rich">
                        <h1 class="fw-bold display-1">{{ __('dicms::errors.empty_site.new_site') }}</h1>
                    </div>
                </div>
                <div class="lc-block">
                    <div editable="rich">
                        <p class="h2">{{ __('dicms::errors.empty_site.new_site.header') }}</p>
                        <p class="lead">{{ __('dicms::errors.empty_site.new_site.lead') }}</p>
                    </div>
                </div>
                <div class="lc-block">
                    <a class="btn btn-primary" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.home') }}" role="button">{{ __('dicms::errors.empty_site.new_site.link') }}</a>
                </div><!-- /lc-block -->
            </div><!-- /col -->
        </div>
    </div>
</section>

</body>
</html>

