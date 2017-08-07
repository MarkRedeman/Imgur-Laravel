## Description
A Laravel (4 / 5) Package for using the Imgur api. Internally we use [j0k3r/php-imgur-api-client](https://github.com/j0k3r/php-imgur-api-client).
The package provides a service provider, some configuration and a facade, such that you should be able to get started with writing your app immediately.

For more detailed documentation on how to use `j0k3r/php-imgur-api-client` you should look at their [documentation](https://github.com/j0k3r/php-imgur-api-client).

### Quick example
```php
use Redeman\Imgur\Facades\Imgur;

$images = Imgur::api('gallery')->randomGalleryImages();

foreach ($images as $image)
{
    echo '<li><img src="' . $image->getLink() . '"></li>';
}
```

## Getting started
First you will have to install the package using composer, this can be done by adding the following to your `require` block,
```
"redeman/imgur-laravel": "~1.0",
```
Next you can register the service provider by adding,
```php
'Redeman\Imgur\ImgurServiceProvider',
```
to your providers array (in `config/app.php`).
Imgur uses Oauth 2.0 to authenticate users. Therefore you have get a authentication token from your users if you want them to be able to view and upload images.
In Laravel 5 you can do this by first add a route middleware to the `Redeman\Imgur\Middleware\AuthenticateImgur` middleware in your `App\Http\Kernel`.
```php
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
If you are a Laravel 4 user, then you only need to add the service provider. This provider will register a `imgur` filter which you can use for your routes, see the example below how to do this.
In the following section we show an example of some simple routes.


## Example
`routes.php`:
```php
// Show the user a random image
Route::get('/', ['middleware' => ['imgur'], function() {
    $client = App::make('Imgur\Client');
    $images = $client->api('gallery')->randomGalleryImages();

    return View::make('imgur.images')->with('images', $images);
}]);

// Ask the user to authenticate using Imgur's services
Route::get('imgur/authenticate', ['as' => 'imgur.authenticate',  function() {
    $client = App::make('Imgur\Client');

    return View::make('imgur.authenticate')->with('imgurUrl', $client->getAuthenticationUrl());
}]);
```
If you are a Laravel 4 user, then you should substitute `middleware` with `before`.

`imgur.images.blade.php`:
```blade
<p>Here are some images for you to enjoy:</p>
@foreach ($images as $image)
    <img src="{{ $image->getLink() }}">
@endforeach
```

`imgur.authenticate.blade.php`:
```blade
<h2>You need to register with Imgur in order to visit this page:</h2>
<a href="{{ $imgurUrl }}">Click to authorize</a>
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
php artisan vendor:publish --provider="Redeman\Imgur\ImgurServiceProviderLaravel5"
```

Now you can fill in the appropriate values for `client_id` and `client_secret`. It is encouraged to use a `.env` which contains your client id and secret. Here is how you can do this in laravel 5:
```php
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
- More storage facilities by default
- Add tests for the middleware and filters
