<?php namespace Redeman\Imgur\TokenStorage;

use Illuminate\Session\Store;

/**
 * Stores the user's token in a session
 */
class SessionStorage implements Storage {

    /**
     * Sesison storage
     * @var Store
     */
    private $session;

    /**
     * Use a session storage to store our tokens
     * @param Store $session
     */
    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Returns an attribute.
     *
     * @param string $name    The attribute name
     * @param mixed  $default The default value if not found.
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        return $this->session->get($name, $default);
    }

    /**
     * Sets an attribute.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function set($name, $value)
    {
        $this->session->put($name, $value);
    }
}
