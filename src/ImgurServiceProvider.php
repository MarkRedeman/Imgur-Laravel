<?php namespace Redeman\Imgur;

use Illuminate\Support\ServiceProvider;
use Redeman\Imgur\ImgurServiceProviderLaravel4;
use Redeman\Imgur\ImgurServiceProviderLaravel5;
use Imgur\Client;

class ImgurServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Actual provider
     *
     * @var \Illuminate\Support\ServiceProvider
     */
    protected $provider;

    /**
     * Construct the Imgur service provider
     */
    public function __construct()
    {
        // Call the parent constructor with all provided arguments
        $arguments = func_get_args();
        call_user_func_array(
            [$this, 'parent::' . __FUNCTION__],
            $arguments
        );

        $this->registerProvider();
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        return $this->provider->boot();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Configure any bindings that are version dependent
        $this->provider->register();
        $config = $this->provider->config();

        $this->app->bindShared('Imgur\Client', function() use ($config) {
            // Setup the client
            $client = new Client;
            $client->setOption('client_id', $config['client_id']);
            $client->setOption('client_secret', $config['client_secret']);

            return $client;
        });

        // Make the token storage configurable
        $this->app->bind(
            'Redeman\Imgur\TokenStorage\Storage',
            $config['token_storage']
        );
    }

    /**
     * Register the ServiceProvider according to Laravel version
     *
     * @return \Redeman\Imgur\Provider\ProviderInterface
     */
    private function registerProvider()
    {
        $app = $this->app;

        // Pick the correct service provider for the current verison of Laravel
        $this->provider = (version_compare($app::VERSION, '5.0', '<'))
            ? new ImgurServiceProviderLaravel4($app)
            : new ImgurServiceProviderLaravel5($app);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('imgur');
    }
}
