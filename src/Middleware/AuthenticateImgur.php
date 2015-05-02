<?php namespace Redeman\Imgur\Middleware;

use Closure;
use Imgur\Client;
use Redeman\Imgur\TokenStorage\Storage;

class AuthenticateImgur {

    /**
     * The Imgur client
     * @var Imgur\Client
     */
    private $imgur;

    /**
     * Token storage
     * @var Storage
     */
    private $store;

    /**
     * @param Imgur\Client $imgur
     */
    public function __construct(Client $imgur, Storage $store)
    {
        $this->imgur = $imgur;
        $this->store = $store;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Either authenticate the user if it's token is known,
        // request access to its token, or redirect the user
        // such that we can ask him to authorize our application
        if ($token = $this->store->get('imgur-token'))
        {
            $this->authenticateUser($token);
        }
        elseif ($code = $request->get('code'))
        {
            $this->requestAccess($code);
        }
        else
        {
            return redirect()->route('imgur.authenticate');
        }

        return $next($request);
    }

    /**
     * Authenticate the user with its token
     * @param  array $token
     * @return void
     */
    private function authenticateUser($token)
    {
        $this->imgur->setAccessToken($token);
        // Refresh the token if neccessary
        if($this->imgur->checkAccessTokenExpired())
        {
            $this->imgur->refreshToken();
        }
    }

    /**
     * Get the user's token by requesting access
     * @param  string $code the code returned by imgur
     * @return void
     */
    private function requestAccess($code)
    {
        $this->imgur->requestAccessToken($code);

        // save the new token
        $token = $this->imgur->getAccessToken();
        $this->store->set('imgur-token', $token);

        // authenticate the user with the new token
        $this->authenticateUser($token);
    }
}
