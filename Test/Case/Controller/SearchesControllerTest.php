<?php
/**
 * SearchesController Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('SearchesController', 'Searches.Controller');

/**
 * SearchesController Test Case
 *
 * @package NetCommons\Searches\Test\Case\Controller
 */
class SearchesControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.searches.search',
		'plugin.searches.language',
		'plugin.searches.user',
		'plugin.searches.role',
		'plugin.searches.user_role_setting',
		'plugin.searches.users_language',
		'plugin.searches.roles_room',
		'plugin.searches.room',
		'plugin.searches.space',
		'plugin.searches.rooms_language',
		'plugin.searches.block_role_permission',
		'plugin.searches.room_role_permission',
		'plugin.searches.roles_rooms_user'
	);

}
