<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2008 - 2014 Asikart.com. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Windwalker\Console\Test\Prompter;

use Windwalker\Console\IO\IO;
use Windwalker\Console\Prompter\CallbackPrompter;
use Windwalker\Console\Prompter\NotNullPrompter;
use Windwalker\Console\Prompter\PasswordPrompter;
use Windwalker\Console\Prompter\SelectPrompter;

/**
 * Class SelectPrompterTest
 *
 * @since  {DEPLOY_VERSION}
 */
class SelectPrompterTest extends AbstractPrompterTest
{
	/**
	 * Property options.
	 *
	 * @var  array
	 */
	protected $options = array('red', 'yellow', 'blue');

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->instance = $prompter = new SelectPrompter(null, $this->options, false, $this->io);
	}

	/**
	 * Test prompter ask.
	 *
	 * @return  void
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	public function testAsk()
	{
		// Invalidate input test
		$this->setStream("4\n5\n6\n7\n8");

		$in = $this->instance->setAttempt(5)
			->ask('Please select an option []:', 2);

		$outputCompare = <<<EOF
  [0] - red
  [1] - yellow
  [2] - blue

Please select an option []:
  Not a valid selection

Please select an option []:
  Not a valid selection

Please select an option []:
  Not a valid selection

Please select an option []:
  Not a valid selection

Please select an option []:
  Not a valid selection
EOF;

		$this->assertEquals(
			str_replace(PHP_EOL, "\n", trim($outputCompare)),
			str_replace(PHP_EOL, "\n", trim($this->io->getTestOutput()))
		);

		// Default value
		$this->assertEquals($in, 2, 'Return value should be default (2).');

		$this->setStream("1");

		$in = $this->instance->ask('Please select an option []:', 2);

		$this->assertEquals($in, 1);
	}
}