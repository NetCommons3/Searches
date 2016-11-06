<?php
/**
 * Add plugin migration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * Add plugin migration
 *
 * @package NetCommons\AccessCounters\Config\Migration
 */
class PluginRecords extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'plugin_records';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(),
		'down' => array(),
	);

/**
 * plugin data
 *
 * @var array $migration
 */
	public $records = array(
		'Plugin' => array(
			//日本語
			array(
				'language_id' => '2',
				'key' => 'searches',
				'namespace' => 'netcommons/searches',
				'name' => '検索ボックス',
				'type' => 1,
				'default_action' => 'searches/view',
				'default_setting_action' => 'search_frame_settings/index',
			),
			//英語
			array(
				'language_id' => '1',
				'key' => 'searches',
				'namespace' => 'netcommons/searches',
				'name' => 'Search box',
				'type' => 1,
				'default_action' => 'searches/view',
				'default_setting_action' => 'search_frame_settings/index',
			),
		),
		'PluginsRole' => array(
			array(
				'role_key' => 'room_administrator',
				'plugin_key' => 'searches',
			),
		),
		'PluginsRoom' => array(
			//パブリックスペース
			array('room_id' => '2', 'plugin_key' => 'searches', ),
			//プライベートスペース
			array('room_id' => '3', 'plugin_key' => 'searches', ),
			//グループスペース
			array('room_id' => '4', 'plugin_key' => 'searches', ),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		$this->loadModels([
			'Plugin' => 'PluginManager.Plugin',
		]);

		if ($direction === 'down') {
			$this->Plugin->uninstallPlugin($this->records['Plugin'][0]['key']);
			return true;
		}

		foreach ($this->records as $model => $records) {
			if (!$this->updateRecords($model, $records)) {
				return false;
			}
		}
		return true;
	}
}
