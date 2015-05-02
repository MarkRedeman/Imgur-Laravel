<?php namespace Redeman\Imgur;

use Illuminate\Support\ServiceProvider;

class ImgurServiceProviderLaravel5 extends ServiceProvider {

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            $this->defaultConfig() => config_path('imgur.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Setup configuration
        $config = $this->defaultConfig();
        $this->mergeConfigFrom($config, 'imgur');
    }

    /**
     * Get the Imgur configuration from the config repository
     *
     * @return array
     */
    public function config()
    {
        return $this->app['config']->get('imgur');
    }

    /**
     * Returns the default configuration path
     *
     * @return string
     */
    private function defaultConfig()
    {
        return __DIR__ . '/config/imgur.php';
    }
}
