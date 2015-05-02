<?php namespace Redeman\Imgur\Filters;

use Illuminate\Http\Request;
use Redeman\Imgur\Middleware\AuthenticateImgur as Middleware;

class AuthenticateImgur {

    /**
     * @var Request
     */
    private $request;

    /**
     * @param Client $imgur
     */
    public function __construct(Middleware $middleware, Request $request)
    {
        $this->middleware = $middleware;
        $this->request = $request;
    }

    /**
     * Since this package already supports laravel 5's middleware,
     * we can reuse that and let it handle the request
     * @return mixed
     */
    public function filter()
    {
        return $this->middleware->handle($this->request, function($request) {});
    }
}