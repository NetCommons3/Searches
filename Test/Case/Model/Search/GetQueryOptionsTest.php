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

		$this->assertEquals($result, $expected);
	}

/**
 * 期待値データ取得
 *
 * @param array $requests リクエストパラメータ
 * @return array
 */
	public function __getExpected($requests) {
		$model = $this->_modelName;

		//チェック
		$expected = array(
			'recursive' => 0,
			'conditions' => array(
				'TopicReadable.topic_id NOT' => null,
				'Search.language_id' => '2',
				0 => array(
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
				1 => array(
					'Search.is_no_member_allow' => true,
					'OR' => array(
						0 => array(
							'Search.room_id' => array(
								0 => '1',
								1 => '4',
								2 => '5',
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
				'Category.id', 'Category.key', 'Category.name',
				'Language.id', 'Language.code',
				'Room.id', 'Room.space_id', 'RoomsLanguage.id', 'RoomsLanguage.name',
				'Block.id', 'Block.key', 'Block.name',
				'Plugin.id', 'Plugin.key', 'Plugin.name',
			),
		);

		return $expected;
	}

}
