<?php
/**
 * @link http://psesd.org
 *
 * @copyright Copyright (c) 2015 Puget Sound ESD
 * @license http://psesd.org/license/
 */

namespace psesd\ldapPassword\setup;


/**
 * Setup Perform the web setup for the application.
 */
class Setup extends \canis\setup\Setup
{
	public function getSetupTaskConfig()
	{
		$tasks = [];
		$tasks[] = [
			'class' => tasks\Environment::className()
		];
		return $tasks;
	}
}
