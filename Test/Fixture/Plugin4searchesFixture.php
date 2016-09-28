<?php
/**
 * 検索用PluginFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('PluginFixture', 'PluginManager.Test/Fixture');

/**
 * 検索用PluginFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Topics\Test\Fixture
 */
class Plugin4searchesFixture extends PluginFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'Plugin';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'plugins';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'language_id' => '2',
			'key' => 'bbses',
			'name' => 'Test Bbses',
			'weight' => '1',
			'type' => '1',
			'default_action' => 'bbses/index',
			'default_setting_action' => '',
			'display_search' => true,
		),
		array(
			'language_id' => '2',
			'key' => 'blogs',
			'name' => 'Test Blogs',
			'weight' => '2',
			'type' => '1',
			'default_action' => 'blogs/index',
			'default_setting_action' => '',
			'display_search' => true,
		),
		array(
			'language_id' => '2',
			'key' => 'announcements',
			'name' => 'Test Announcements',
			'weight' => '3',
			'type' => '1',
			'default_action' => 'announcements/index',
			'default_setting_action' => '',
			'display_search' => true,
		),
		array(
			'language_id' => '2',
			'key' => 'faqs',
			'name' => 'Test Faqs',
			'weight' => '4',
			'type' => '1',
			'default_action' => 'faqs/index',
			'default_setting_action' => '',
			'display_search' => true,
		),
		array(
			'language_id' => '2',
			'key' => 'circular_notices',
			'name' => 'Test Circular Notices',
			'weight' => '5',
			'type' => '1',
			'default_action' => 'circular_notices/index',
			'default_setting_action' => '',
			'display_search' => true,
		),
		array(
			'language_id' => '2',
			'key' => 'calendars',
			'name' => 'Test Calendars',
			'weight' => '6',
			'type' => '1',
			'default_action' => 'calendars/index',
			'default_setting_action' => '',
			'display_search' => true,
		),
	);

}
