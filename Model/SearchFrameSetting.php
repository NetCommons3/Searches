<?php
/**
 * SearchFrameSetting Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('SearchesAppModel', 'Searches.Model');

/**
 * SearchFrameSetting Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Searches\Model
 */
class SearchFrameSetting extends SearchesAppModel {

/**
 * 表示方法(通常検索)
 *
 * @var int
 */
	const SEARCH_TYPE_NORMAL = '0';

/**
 * 表示方法(詳細検索)
 *
 * @var int
 */
	const SEARCH_TYPE_ADVANCED = '1';

/**
 * デフォルトプラグイン
 *
 * @var array
 */
	public static $defaultPlugins = array(
		'announcements',
		'bbses',
		'blogs',
	);

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
			'is_advanced' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
		));

		//SearchFramesPluginのチェック
		if (isset($this->data['SearchFramesPlugin'])) {
			$this->loadModels([
				'SearchFramesPlugin' => 'Searches.SearchFramesPlugin',
			]);
			if (! $this->SearchFramesPlugin->validateRequestData($this->data)) {
				throw new BadRequestException(__d('net_commons', 'Bad Request'));
			}
		}
	}

/**
 * Called after each successful save operation.
 *
 * @param bool $created True if this save created a new record
 * @param array $options Options passed from Model::save().
 * @return void
 * @throws InternalErrorException
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#aftersave
 * @see Model::save()
 * @throws InternalErrorException
 */
	public function afterSave($created, $options = array()) {
		//SearchFramesPluginのチェック
		if (isset($this->data['SearchFramesPlugin'])) {
			$this->loadModels([
				'SearchFramesPlugin' => 'Searches.SearchFramesPlugin',
			]);
			if (! $this->SearchFramesPlugin->saveSearchFramesPlugin($this->data)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		parent::afterSave($created, $options);
	}

/**
 * SearchFrameSettingデータ取得
 *
 * @return array SearchFrameSetting data
 */
	public function getSearchFrameSetting() {
		$conditions = array(
			'frame_key' => Current::read('Frame.key')
		);

		$searchFrameSetting = $this->find('first', array(
			'recursive' => -1,
			'conditions' => $conditions,
		));

		if (! $searchFrameSetting) {
			$searchFrameSetting = $this->create([]);
			$searchFrameSetting['SearchFramesPlugin']['plugin_key'] = self::$defaultPlugins;
		} else {
			//表示するプラグインを取得
			$this->loadModels([
				'SearchFramesPlugin' => 'Searches.SearchFramesPlugin',
			]);
			$result = $this->SearchFramesPlugin->find('list', array(
				'recursive' => -1,
				'fields' => array('id', 'plugin_key'),
				'conditions' => ['frame_key' => Current::read('Frame.key')],
			));
			$searchFrameSetting['SearchFramesPlugin']['plugin_key'] = array_unique(array_values($result));
		}

		return $searchFrameSetting;
	}

/**
 * SearchFrameSettingの登録
 *
 * @param array $data リクエストデータ
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveSearchFrameSetting($data) {
		//トランザクションBegin
		$this->begin();

		//バリデーション
		$this->set($data);
		if (! $this->validates()) {
			return false;
		}

		try {
			//登録処理
			if (! $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

}
