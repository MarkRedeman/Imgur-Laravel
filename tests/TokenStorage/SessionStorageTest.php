<?php namespace Redeman\Imgur\TokenStorage\Test;

use Redeman\Imgur\TokenStorage\SessionStorage;
use PHPUnit_Framework_TestCase;
use Mockery;

/**
 * Quite a silly test that just checks if we correctly use the session interface
 */
class SessionStorageTest extends PHPUnit_Framework_TestCase
{
    private $storage;
    private $session;

    protected function setUp()
    {
        $this->storage = Mockery::mock(
            'Illuminate\Session\Store'
        );

        $this->session = new SessionStorage($this->storage);
    }

    protected function tearDown() {
        Mockery::close();
    }

    /**
     * @test
     */
    public function it_gets_values_from_storage()
    {
        $this->storage->shouldReceive('get')->with('key', null)->andReturn('value');
        $this->assertEquals($this->session->get('key'), 'value');
    }

    /**
     * @test
     */
    public function it_sets_values_from_storage()
    {
        $this->storage->shouldReceive('set')->with('key', 'value');
        $this->session->set('key', 'value');
    }
}