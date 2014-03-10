<?php namespace Aptitude;

use Closure;
use ArrayAccess;
use Interop\Container\ContainerInterface;

/**
* Service Locator
*/
class Container implements ArrayAccess, ContainerInterface
{
	/**
	 * All registered services.
	 *
	 * @var array
	 */
	private $services = array();

	/**
	 * Services that has been instaniated.
	 *
	 * @var array
	 */
	private $instantiated = array();

	/**
	 * True if a service is shared.
	 *
	 * @var array
	 */
	private $shared = array();

	/**
	 * Check if the service exists.
	 *
	 * @param  string $interface
	 * @return bool
	 */
	public function has($interface)
	{
		$this->offsetExists($interface);
	}

	/**
	 * Get the service by it's name
	 *
	 * @param
	 */
	public function get($interface)
	{
		$this->offsetGet($interface);
	}

	/**
	 * Register a new service in the container.
	 *
	 * @param string $interface
	 * @param object $service
	 * @param bool 	 $share
	 *
	 * @return void
	 */
	public function register($interface, $service, $shared = true)
	{
		// Always make the interface names lowercase.
		$interface = strtolower($interface);

		// If the supplied service is an object add it directly to the
		// instantiated services array.
		// if (is_object($service) && $share)
		// $this->instantiated[$interface] = $service;

		// Set the service in the services array, if it's an object fetch the
		// name of the class otherwise set the closure as the service.
		// $this->services[$interface] = is_object($service) ? get_class($service) : $service;

		// If the supplied service is an allready instantiated object ser
		// it to the instantiated array.
		if (is_object($service) && ! is_callable($service))
			$this->instantiated[$interface] = $service;

		// Register the service in the services array.
		$this->services[$interface] = $service;

		// Set wether the service is shared or not.
		$this->shared[$interface] = $shared;
	}

	/**
	 * Set a new service to the container.
	 *
	 * Set a service in the container, the supplied service must be a
	 * closure or you will have el problemos!
	 *
	 * @param string $interface
	 * @param object $service
	 *
	 * @return void
	 */
    public function offsetSet($interface, $service)
    {
        $this->register($interface, $service);
    }

    /**
     * Check if a service is registered.
     *
     * @param  string $interface
     * @return bool
     */
    public function offsetExists($interface)
    {
        return (isset($this->services[$interface]) || isset($this->instantiated[$interface]));
    }

    /**
     * Remove service.
     * 
     * @param  string $offset
     * @return void
     */
    public function offsetUnset($interface)
    {
        unset($this->services[$interface], $this->instantiated[$interface]);
    }

    /**
     * Get service from the container.
     *
     * @param  string $offset
     * @return object
     */
    public function offsetGet($interface)
    {
        // Retrieve instantiated service if it exists and shared is set
		// to true, then return it.
		if (isset($this->instantiated[$interface]) && $this->shared[$interface]) {
			return $this->instantiated[$interface];
		}

		// Make a new instance from the set closure for this service if it was
		// not instaniated allready or if shared was set to false.
		return $this->make($interface);
    }

    /**
     * Make a new instance of the service.
     *
     * @param  string $interface
     * @return object
     */
    private function make($interface)
    {
    	// If service was not instaniated get it from the registered services.
		$service = $this->services[$interface];

		// Instaniate the service object.
		$object = $service($this);

		// Save the service if it must be shared.
		if ($this->shared[$interface]) {
			$this->instantiated[$interface] = $object;
		}

		return $object;
    }
}