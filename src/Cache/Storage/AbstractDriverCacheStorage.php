<?php
/**
 * Part of Windwalker project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Windwalker\Cache\Storage;

/**
 * Class AbstractDriverCacheStorage
 *
 * @since 1.0
 */
abstract class AbstractDriverCacheStorage extends AbstractCacheStorage
{
	/**
	 * Property driver.
	 *
	 * @var  \Memcached
	 */
	protected $driver = null;

	/**
	 * Constructor.
	 *
	 * @param   object $driver   The cache storage driver.
	 * @param   int    $ttl      The Time To Live (TTL) of an item
	 * @param   mixed  $options  An options array, or an object that implements \ArrayAccess
	 *
	 * @since   1.0
	 */
	public function __construct($driver = null, $ttl = null, $options = array())
	{
		$this->driver = $driver;

		parent::__construct($ttl, $options);
	}

	/**
	 * connect
	 *
	 * @return  static
	 */
	abstract protected function connect();

	/**
	 * getDriver
	 *
	 * @return  object
	 */
	public function getDriver()
	{
		return $this->driver;
	}

	/**
	 * setDriver
	 *
	 * @param   object $driver
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setDriver($driver)
	{
		$this->driver = $driver;

		return $this;
	}
}
 