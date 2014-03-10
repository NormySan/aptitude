<?php namespace Aptitude;

abstract class ServiceProvider
{
	/**
	 * The application instance.
	 *
	 * @var \Aptitude\Application
	 */
	protected $app;

	/**
	 * Instantiates a new ServiceProvide instance.
	 *
	 * @var \Aptitude\Application $app
	 * @return void
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 * Registers the service provider.
	 *
	 * @return void
	 */
	abstract public function register();
}