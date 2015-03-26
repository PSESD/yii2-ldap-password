<?php
/**
 * @link http://psesd.org
 *
 * @copyright Copyright (c) 2015 Puget Sound ESD
 * @license https://raw.githubusercontent.com/PSESD/yii2-ldap-password/master/LICENSE
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
