## Description
A Laravel 5 Package for using the Imgur api. Internally we use [adyg/php-imgur-api-client](https://github.com/Adyg/php-imgur-api-client).
The package provides a service provider, some configuration and a facade, such that you should be able to get started with writing your app immediately.

Note: this package isn't officially stable yet. There are still some minor issues that need to be solved such as Laravel 4 support.


## Getting started
First you will have to install the package using composer, this can be done by adding the following to your `require` block,
```
    "redeman/imgur-laravel": "dev-master"
```
Next you can register the service provider by adding,
```
'Redeman\Imgur\ImgurServiceProvider',
```
to your providers array (in `config/app.php`).
Imgur uses Oauth 2.0 to authenticate users. Therefore you have get a authentication token from your users if you want them to be able to view and upload images.
In Laravel 5 you can do this by first add a route middleware to the `Redeman\Imgur\Middleware\AuthenticateImgur` middleware in your `App\Http\Kernel`.
```
    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // your other route middleware
        'imgur' => 'Redeman\Imgur\Middleware\AuthenticateImgur',
    ];
```
Next you should add the `imgur` middleware to any route were a user should be authenticated by Imgur.
The `AuthenticateImgur` middleware will store and retrieve the user's access token.
If the user is not authenticated by the Imgur (meaning your application doesn't know the user's access token), then the user will be redirected to a route with the name `imgur.authenticate`.
In the following section we show an example of some simple routes.


## Example
`routes.php`:
```

// Show the user a random image
Route::get('/', ['middleware' => ['imgur'], function() {
    $client = App::make('Imgur\Client');
    $images = $client->api('gallery')->randomGalleryImages();

    return View::make('imgur.images')->with('images', $images);
}]);

// Ask the user to authenticate using Imgur's services
Route::get('imgur/authenticate', ['as' => 'imgur.authenticate',  function() {
    $client = App::make('Imgur\Client');

    return View::make('imgur.authenticate')->with('url', $client->getAuthenticationUrl());
}]);
```

`imgur.images.blade.php`:
```
<p>Here are some images for you to enjoy:</p>
@foreach ($images as $image)
    <img src="{{ $images[0]->getLink() }}">
@endforeach
```

`imgur.authenticate.blade.php`:
```
<h2>You need to register with Imgur in order to visit this page:</h2>
<a href="{{ $url }}">Click to authorize</a>
```

Now you're all set! The next section describes how you can configure the package to use your client id, secret and a custom `TokenStorage`.

## Configuration
First publish the configuration file:

#### Laravel 4:
```
php artisan config:publish redeman/imgur-laravel
```

#### Laravel 5:
```
php artisan vendor:publish --provider=redeman/imgur-laravel
```

Now you can fill in the appropriate values for `client_id` and `client_secret`. It is encouraged to use a `.env` which contains your client id and secret. Here is how you can do this in laravel 5:
```
    /*
     * Public client id
     */
    'client_id' => env('CLIENT_ID'),

    /**
     * Client secret
     */
    'client_secret' => env('CLIENT_SECRET'),
```

Read the [Laravel documentation](http://laravel.com/docs/5.0/configuration#environment-configuration) to learn more about environment configuration.

### Using a custom `TokenStorage`
The `AuthenticateImgur` middleware uses a `Redeman\Imgur\TokenStorage\Storage` interface to store a user's access token. By default we provide a `SessionStorage` which will save the data in the user's session.
However you could also use a database or some other storage facility. To do this you will have to change the `token_storage` value in your `config/imgur.php` file to the name of the class that you want to use.

## Todos:
- Laravel 4 support
- More storage facilities by default