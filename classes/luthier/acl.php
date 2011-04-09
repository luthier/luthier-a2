<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Luthier ACL using Wouter's A2/ACL libraries.
 *
 * @package     Luthier/A2
 * @category    Base
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
class Luthier_Acl {

	/**
	 * Perform an ACL check using the A2 library
	 */
	public static function allowed($user, $resource, $privilege)
	{
		$a2 = A2::instance(Kohana::config('luthier.auth.instance'));
		return $a2->allowed($resource, $privilege);
	}

}
