<?php
/**
 * SearchFramesPlugin Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('SearchesAppModel', 'Searches.Model');

/**
 * SearchFramesPlugin Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Searches\Model
 */
class SearchFramesPlugin extends SearchesAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 * @see Model::save()
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @throws BadRequestException
 */
	public function beforeValidate($options = array()) {
		$this->validate = Hash::merge($this->validate, array(
			'frame_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'plugin_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
		));
	}

/**
 * SearchFramesPluginのチェック
 *
 * @param array $data リクエストデータ
 * @return bool
 */
	public function validateRequestData($data) {
		$pluginKeys = Hash::extract($data, 'Plugin.{n}.key');

		$check = Hash::get($data, 'SearchFrameSetting' . '.plugin_key', array());
		foreach ($check as $pluginKey) {
			if (! in_array($pluginKey, $pluginKeys, true)) {
				return false;
			}
		}

		return true;
	}

/**
 * プラグインデータ取得
 *
 * @param array $conditions 条件配列
 * @return array
 */
	public function getPlugins($conditions = []) {
		$this->loadModels([
			'Plugin' => 'PluginManager.Plugin',
		]);

		$conditions = Hash::merge(
			array('display_topics' => true, 'language_id' => Current::read('Language.id', '0')),
			$conditions
		);
		$plugin = $this->Plugin->find('list', array(
			'recursive' => -1,
			'fields' => array('key', 'name'),
			'conditions' => $conditions,
			'order' => 'weight'
		));

		return $plugin;
	}

/**
 * SearchFramesPluginの登録
 *
 * SearchFrameSetting::saveSearchFrameSetting()から実行されるため、ここではトランザクションを開始しない
 *
 * @param array $data リクエストデータ
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveSearchFramesPlugin($data) {
		$pluginKeys = Hash::get($data, $this->alias . '.plugin_key', array());

		$saved = $this->find('list', array(
			'recursive' => -1,
			'fields' => array('id', 'plugin_key'),
			'conditions' => ['frame_key' => Current::read('Frame.key')],
		));
		$saved = array_unique(array_values($saved));

		$delete = array_diff($saved, $pluginKeys);
		if (count($delete) > 0) {
			$conditions = array(
				'SearchFramesPlugin' . '.frame_key' => Current::read('Frame.key'),
				'SearchFramesPlugin' . '.plugin_key' => $delete,
			);
			if (! $this->deleteAll($conditions, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		$new = array_diff($pluginKeys, $saved);
		if (count($new) > 0) {
			$saveDate = array();
			foreach ($new as $i => $pluginKey) {
				$saveDate[$i] = array(
					'id' => null,
					'plugin_key' => $pluginKey,
					'frame_key' => Current::read('Frame.key')
				);
			}
			if (! $this->saveMany($saveDate)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		return true;
	}

}
