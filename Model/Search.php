<?php
/**
 * Search Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('SearchesAppModel', 'Searches.Model');
App::uses('Topic', 'Topics.Model');
App::uses('SiteSettingUtil', 'SiteManager.Utility');
App::uses('SiteSetting', 'SiteManager.Model');
App::uses('NetCommonsTime', 'NetCommons.Utility');

/**
 * Search Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Searches\Model
 */
class Search extends Topic {

/**
 * AND条件
 *
 * @var string
 */
	const WHERE_TYPE_AND = 'and';

/**
 * OR条件
 *
 * @var string
 */
	const WHERE_TYPE_OR = 'or';

/**
 * フレーズ条件
 *
 * @var string
 */
	const WHERE_TYPE_PHRASE = 'phrase';

/**
 * Custom database table name, or null/false if no table association is desired.
 *
 * @var string
 * @link http://book.cakephp.org/2.0/ja/models/model-attributes.html#usetable
 */
	public $useTable = 'topics';

/**
 * 検索結果取得のオプション生成
 *
 * @param int $status ステータス
 * @param array $requests 条件リクエスト
 * @return array
 */
	public function getQueryOptions($status, $requests = array()) {
		$options = parent::getQueryOptions('0', array());

		$options['fields'] = Hash::merge(
			array(
				$this->alias . '.id',
				$this->alias . '.frame_id',
				$this->alias . '.title',
				$this->alias . '.title_icon',
				$this->alias . '.summary',
				$this->alias . '.path',
				$this->alias . '.modified',
			),
			//Categoryフィールド
			array_map(function ($field) {
				return 'Category.' . $field;
			}, Hash::get($this->belongsTo, 'Category.fields', array())),
			//CategoriesLanguageフィールド
			array_map(function ($field) {
				return 'CategoriesLanguage.' . $field;
			}, Hash::get($this->belongsTo, 'CategoriesLanguage.fields', array())),
			//Languageフィールド
			array_map(function ($field) {
				return 'Language.' . $field;
			}, Hash::get($this->belongsTo, 'Language.fields', array())),
			//Roomフィールド
			array_map(function ($field) {
				return 'Room.' . $field;
			}, Hash::get($this->belongsTo, 'Room.fields', array())),
			//RoomsLanguageフィールド
			array_map(function ($field) {
				return 'RoomsLanguage.' . $field;
			}, Hash::get($this->belongsTo, 'RoomsLanguage.fields', array())),
			//Blockフィールド
			array_map(function ($field) {
				return 'Block.' . $field;
			}, Hash::get($this->belongsTo, 'Block.fields', array())),
			//BlocksLanguageフィールド
			array_map(function ($field) {
				return 'BlocksLanguage.' . $field;
			}, Hash::get($this->belongsTo, 'BlocksLanguage.fields', array())),
			//Pluginフィールド
			array_map(function ($field) {
				return 'Plugin.' . $field;
			}, Hash::get($this->belongsTo, 'Plugin.fields', array())),
			//TrackableCreatorフィールド
			Hash::get($this->belongsTo, 'TrackableCreator.fields', array()),
			//TrackableUpdaterフィールド
			Hash::get($this->belongsTo, 'TrackableUpdater.fields', array())
		);

		//期間の指定
		$conditions = $options['conditions'];
		if (Hash::get($requests, 'period_start')) {
			$periodStart = (new NetCommonsTime)->toServerDatetime(Hash::get($requests, 'period_start'));
			$conditions[$this->alias . '.publish_start >='] = $periodStart;
		}
		if (Hash::get($requests, 'period_end')) {
			$date = new DateTime(Hash::get($requests, 'period_end'));
			$periodEnd = (new NetCommonsTime)->toServerDatetime($date->format('Y-m-d'));
			$conditions[$this->alias . '.publish_end <'] = $periodEnd;
		}

		//プラグインの指定
		if (Hash::get($requests, 'plugin_key')) {
			$conditions[$this->alias . '.plugin_key'] = Hash::get($requests, 'plugin_key');
		}
		//ルームの指定
		if (Hash::get($requests, 'room_id')) {
			$conditions[$this->alias . '.room_id'] = Hash::get($requests, 'room_id');
		}
		//ブロックの指定
		if (Hash::get($requests, 'block_id')) {
			$conditions[$this->alias . '.block_id'] = Hash::get($requests, 'block_id');
		}
		//フリーワード
		if (Hash::get($requests, 'keyword')) {
			$conditions[] = $this->getStringCondition(
				$this->alias . '.search_contents',
				Hash::get($requests, 'keyword'),
				Hash::get($requests, 'where_type', self::WHERE_TYPE_AND)
			);
		}
		//ハンドルの指定
		if (Hash::get($requests, 'handle')) {
			$conditions[] = $this->getStringCondition(
				'TrackableCreator.handlename',
				Hash::get($requests, 'handle'),
				Hash::get($requests, 'where_type', self::WHERE_TYPE_AND)
			);
		}

		$options['conditions'] = $conditions;
		return $options;
	}

/**
 * 文字列フィールドの条件取得
 *
 * @param string $field フィールド名
 * @param string $searchValue 検索値
 * @param int $whereType 条件タイプ
 * @return array
 */
	public function getStringCondition($field, $searchValue, $whereType) {
		if ($whereType === self::WHERE_TYPE_PHRASE) {
			$values = array($searchValue);
		} else {
			$values = preg_split('/[\s,　]+/u', $searchValue);
		}

		$conditions = array();
		if (SiteSettingUtil::read('Search.type') === SiteSetting::DATABASE_SEARCH_MATCH_AGAIN) {
			if ($whereType === self::WHERE_TYPE_OR) {
				$values = array_map(function ($val) {
					return '"' . $val . '"';
				}, $values);
			} else {
				$values = array_map(function ($val) {
					return '+"' . $val . '"';
				}, $values);
			}
			$conditions['MATCH (' . $field . ') AGAINST (? IN BOOLEAN MODE)'] = implode(' ', $values);
		} else {
			$conds = array();
			foreach ($values as $val) {
				$conds[] = array($field . ' LIKE' => '%' . $val . '%');
			}
			if ($whereType === self::WHERE_TYPE_OR) {
				$conditions['OR'] = $conds;
			} elseif ($whereType === self::WHERE_TYPE_PHRASE) {
				$conditions = $conds;
			} else {
				$conditions['AND'] = $conds;
			}
		}

		return $conditions;
	}

}
