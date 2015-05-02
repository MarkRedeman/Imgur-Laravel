<?php namespace Redeman\Imgur;

use Illuminate\Support\ServiceProvider;
use Route;

class ImgurServiceProviderLaravel4 extends ServiceProvider {

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('redeman/imgur-laravel', 'imgur', __DIR__);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['router']->filter(
            'imgur',
            'Redeman\Imgur\Filters\AuthenticateImgur'
        );
    }

    /**
     * Get the imgur configuration from the config repository
     *
     * @return array
     */
    public function config()
    {
        return $this->app['config']->get('imgur::imgur');
    }
}
