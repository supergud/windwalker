<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2015 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Windwalker\Application;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Windwalker\IO\Input;
use Windwalker\Registry\Registry;

/**
 * The Abstract Application Class.
 *
 * @property-read  Registry  $config
 * @property-read  Input     $input
 *
 * @since 2.0
 */
abstract class AbstractApplication implements LoggerAwareInterface
{
	/**
	 * The application configuration object.
	 *
	 * @var    Registry
	 * @since  2.0
	 */
	protected $config;

	/**
	 * The application input object.
	 *
	 * @var    Input
	 * @since  2.0
	 */
	protected $input = null;

	/**
	 * A logger object.
	 *
	 * @var    LoggerInterface
	 * @since  2.0
	 */
	protected $logger;

	/**
	 * Class constructor of Application.
	 *
	 * @param   Input     $input   An optional argument to provide an Input object.
	 * @param   Registry  $config  An optional argument to provide a Registry object to be config.
	 *
	 * @since   2.0
	 */
	public function __construct(Input $input = null, Registry $config = null)
	{
		$this->input = $input instanceof Input ? $input : new Input;
		$this->config = $config instanceof Registry ? $config : new Registry;

		$this->initialise();
	}

	/**
	 * Method to close the application.
	 *
	 * @param   integer|string  $message  The exit code (optional; default is 0).
	 *
	 * @return  void
	 *
	 * @since   2.0
	 */
	public function close($message = 0)
	{
		exit($message);
	}

	/**
	 * Method to run the application routines. Most likely you will want to instantiate a controller
	 * and execute it, or perform some sort of task directly.
	 *
	 * @return  void
	 *
	 * @since   2.0
	 */
	abstract protected function doExecute();

	/**
	 * Execute the application.
	 *
	 * @return  mixed
	 *
	 * @since   2.0
	 */
	public function execute()
	{
		$this->prepareExecute();

		// @event onBeforeExecute

		// Perform application routines.
		$this->doExecute();

		// @event onAfterExecute

		return $this->postExecute();
	}

	/**
	 * Prepare execute hook.
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
	}

	/**
	 * Post execute hook.
	 *
	 * @return  mixed
	 */
	protected function postExecute()
	{
	}

	/**
	 * Returns a property of the object or the default value if the property is not set.
	 *
	 * @param   string  $key      The name of the property.
	 * @param   mixed   $default  The default value (optional) if none is set.
	 *
	 * @return  mixed   The value of the configuration.
	 *
	 * @since   2.0
	 */
	public function get($key, $default = null)
	{
		return $this->config->get($key, $default);
	}

	/**
	 * Get the logger.
	 *
	 * @return  LoggerInterface
	 *
	 * @since   2.0
	 */
	public function getLogger()
	{
		// If a logger hasn't been set, use NullLogger
		if (! ($this->logger instanceof LoggerInterface))
		{
			$this->logger = new NullLogger;
		}

		return $this->logger;
	}

	/**
	 * Custom initialisation method.
	 *
	 * Called at the end of the AbstractApplication::__construct() method.
	 * This is for developers to inject initialisation code for their application classes.
	 *
	 * @return  void
	 *
	 * @since   2.0
	 */
	protected function initialise()
	{
	}

	/**
	 * Modifies a property of the object, creating it if it does not already exist.
	 *
	 * @param   string  $key    The name of the property.
	 * @param   mixed   $value  The value of the property to set (optional).
	 *
	 * @return  mixed   Previous value of the property
	 *
	 * @since   2.0
	 */
	public function set($key, $value = null)
	{
		$previous = $this->config->get($key);

		$this->config->set($key, $value);

		return $previous;
	}

	/**
	 * Sets the configuration for the application.
	 *
	 * @param   Registry  $config  A registry object holding the configuration.
	 *
	 * @return  AbstractApplication  Returns itself to support chaining.
	 *
	 * @since   2.0
	 */
	public function setConfiguration(Registry $config)
	{
		$this->config = $config;

		return $this;
	}

	/**
	 * Set the logger.
	 *
	 * @param   LoggerInterface  $logger  The logger.
	 *
	 * @return  AbstractApplication  Returns itself to support chaining.
	 *
	 * @since   2.0
	 */
	public function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;

		return $this;
	}

	/**
	 * is utilized for reading data from inaccessible members.
	 *
	 * @param   $name  string
	 *
	 * @return  mixed
	 */
	public function __get($name)
	{
		$allowNames = array(
			'config',
			'input'
		);

		if (in_array($name, $allowNames))
		{
			return $this->$name;
		}

		throw new \UnexpectedValueException('Property: ' . $name . ' not found in ' . get_called_class());
	}
}
