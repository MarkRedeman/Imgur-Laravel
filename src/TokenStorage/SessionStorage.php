<?php namespace Redeman\Imgur\TokenStorage;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Stores the user's token in a session
 */
class SessionStorage implements Storage {

    /**
     * Sesison storage
     * @var SessionInterface
     */
    private $session;

    /**
     * Use a session storage to store our tokens
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
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
        $this->session->set($name, $value);
    }
}
