<?php
/**
 * Search::getQueryOptions()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsGetTest', 'NetCommons.TestSuite');

/**
 * Search::getQueryOptions()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Searches\Test\Case\Model\Search
 */
class SearchGetQueryOptionsTest extends NetCommonsGetTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.searches.search_frame_setting',
		'plugin.searches.search_frames_plugin',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'searches';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'Search';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'getQueryOptions';

/**
 * getQueryOptions()テストのDataProvider
 *
 * ### 戻り値
 *  - requests リクエストパラメータ
 *
 * @return array データ
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
	public function dataProvider() {
		$result = array();

		$result[0] = array(
			'requests' => array(),
		);
		//期間(From)
		$result[1] = array(
			'requests' => array(
				'plugin_key' => array(
					'announcements', 'bbses', 'blogs'
				),
				'modified' => '2016-08-31',
				'where_type' => 'and',
			),
		);
		//期間(To)
		$result[2] = array(
			'requests' => array(
				'plugin_key' => array(
					'announcements', 'bbses', 'blogs'
				),
				'modified' => '2016-08-31',
				'where_type' => 'and',
			),
		);
		//ルーム
		$result[3] = array(
			'requests' => array(
				'plugin_key' => array(
					'announcements', 'bbses', 'blogs'
				),
				'room_id' => '5',
				'where_type' => 'and',
			),
		);
		//ブロック
		$result[4] = array(
			'requests' => array(
				'plugin_key' => array(
					'announcements', 'bbses', 'blogs'
				),
				'block_id' => '6',
				'where_type' => 'and',
			),
		);
		//フリーワード
		$result[5] = array(
			'requests' => array(
				'plugin_key' => array(
					'announcements', 'bbses', 'blogs'
				),
				'keyword' => 'NetCommons3 NC3',
				'where_type' => 'and',
			),
		);
		$result[6] = array(
			'requests' => array(
				'plugin_key' => array(
					'announcements', 'bbses', 'blogs'
				),
				'keyword' => 'NetCommons3 NC3',
				'where_type' => 'or',
			),
		);
		$result[7] = array(
			'requests' => array(
				'plugin_key' => array(
					'announcements', 'bbses', 'blogs'
				),
				'keyword' => 'NetCommons3 NC3',
				'where_type' => 'phrase',
			),
		);
		//ハンドル
		$result[8] = array(
			'requests' => array(
				'plugin_key' => array(
					'announcements', 'bbses', 'blogs'
				),
				'handle' => 'System Administrator',
				'where_type' => 'and',
			),
		);
		$result[9] = array(
			'requests' => array(
				'plugin_key' => array(
					'announcements', 'bbses', 'blogs'
				),
				'handle' => 'System Administrator',
				'where_type' => 'or',
			),
		);
		$result[10] = array(
			'requests' => array(
				'plugin_key' => array(
					'announcements', 'bbses', 'blogs'
				),
				'handle' => 'System Administrator',
				'where_type' => 'phrase',
			),
		);

		return $result;
	}

/**
 * getQueryOptions()のテスト
 *
 * @param array $requests リクエストパラメータ
 * @dataProvider dataProvider
 * @return void
 */
	public function testGetQueryOptions($requests) {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$status = '0'; //検索では、0固定

		//テスト実施
		$result = $this->$model->$methodName($status, $requests);

		//チェック
		$expected = $this->__getExpected($requests);

		$this->assertEquals($expected, $result);
	}

/**
 * 期待値データ取得
 *
 * @param array $requests リクエストパラメータ
 * @return array
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
	private function __getExpected($requests) {
		$model = $this->_modelName;

		//チェック
		$expected = array(
			'recursive' => 0,
			'conditions' => array(
				0 => array(
					'TopicReadable.topic_id NOT' => null,
					'Search.language_id' => '2',
				),
				1 => array(
					'OR' => array(
						0 => array(
							'Block.public_type' => '1',
						),
						1 => array(
							'Block.public_type' => '2',
							0 => array(
								'OR' => array(
									'Block.publish_start <=' => $this->$model->now,
									'Block.publish_start' => null,
								),
							),
							1 => array(
								'OR' => array(
									'Block.publish_end >=' => $this->$model->now,
									'Block.publish_end' => null,
								),
							),
						),
					),
				),
				2 => array(
					'Search.is_no_member_allow' => true,
					'OR' => array(
						0 => array(
							'Search.room_id' => array(
								0 => '2',
								1 => '5',
								2 => '6',
							),
							'Search.is_active' => true,
							0 => array(
								'Search.public_type' => array(
									0 => '1',
									1 => '2',
								),
								'Search.publish_start <=' => $this->$model->now,
								'OR' => array(
									'Search.publish_end >=' => $this->$model->now,
									'Search.publish_end' => null,
								),
							),
						),
					),
				),
			),
			'order' => array(
				'Search.publish_start' => 'desc', 'Search.id' => 'desc',
			),
			'fields' => array(
				'Search.id', 'Search.frame_id', 'Search.title', 'Search.title_icon',
				'Search.summary', 'Search.path', 'Search.modified',
				'Category.id', 'Category.key', 'CategoriesLanguage.name',
				'Language.id', 'Language.code',
				'Room.id', 'Room.space_id', 'RoomsLanguage.id', 'RoomsLanguage.name',
				'Block.id', 'Block.key', 'BlocksLanguage.name',
				'Plugin.id', 'Plugin.key', 'Plugin.name',
			),
		);

		if (Hash::get($requests, 'period_start')) {
			$expected['conditions']['Search.publish_start >='] = '2016-08-30 15:00:00';
		}
		if (Hash::get($requests, 'period_end')) {
			$expected['conditions']['Search.publish_end <'] = '2016-08-30 15:00:00';
		}
		if (Hash::get($requests, 'plugin_key')) {
			$expected['conditions']['Search.plugin_key'] = array('announcements', 'bbses', 'blogs');
		}
		if (Hash::get($requests, 'room_id')) {
			$expected['conditions']['Search.room_id'] = '5';
		}
		if (Hash::get($requests, 'block_id')) {
			$expected['conditions']['Search.block_id'] = '6';
		}

		if (Hash::get($requests, 'keyword')) {
			if (Hash::get($requests, 'where_type') === 'and') {
				$expected['conditions'][]['AND'] = array(
					0 => array('Search.search_contents LIKE' => '%NetCommons3%'),
					1 => array('Search.search_contents LIKE' => '%NC3%'),
				);
			} elseif (Hash::get($requests, 'where_type') === 'or') {
				$expected['conditions'][]['OR'] = array(
					0 => array('Search.search_contents LIKE' => '%NetCommons3%'),
					1 => array('Search.search_contents LIKE' => '%NC3%'),
				);
			} else {
				$expected['conditions'][][0] = array('Search.search_contents LIKE' => '%NetCommons3 NC3%');
			}
		}

		if (Hash::get($requests, 'handle')) {
			if (Hash::get($requests, 'where_type') === 'and') {
				$expected['conditions'][]['AND'] = array(
					0 => array('TrackableCreator.handlename LIKE' => '%System%'),
					1 => array('TrackableCreator.handlename LIKE' => '%Administrator%'),
				);
			} elseif (Hash::get($requests, 'where_type') === 'or') {
				$expected['conditions'][]['OR'] = array(
					0 => array('TrackableCreator.handlename LIKE' => '%System%'),
					1 => array('TrackableCreator.handlename LIKE' => '%Administrator%'),
				);
			} else {
				$expected['conditions'][][0] = array('TrackableCreator.handlename LIKE' => '%System Administrator%');
			}
		}

		return $expected;
	}

}
