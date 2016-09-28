<?php
/**
 * SearchesController::index()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * SearchesController::index()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Searches\Test\Case\Controller\SearchesController
 */
class SearchesControllerIndexTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.searches.search_frame_setting',
		'plugin.searches.search_frames_plugin',
		'plugin.topics.room4topics',
		'plugin.topics.rooms_language4topics',
		'plugin.topics.roles_room4topics',
		'plugin.topics.roles_rooms_user4topics',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'searches';

/**
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'searches';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//ログイン
		TestAuthGeneral::login($this);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		//ログアウト
		TestAuthGeneral::logout($this);

		parent::tearDown();
	}

/**
 * index()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testIndexGet() {
		$frameId = '6';

		//テスト実行
		$this->_testGetAction(
			array('action' => 'index', 'frame_id' => $frameId),
			array('method' => 'assertNotEmpty'), null, 'view'
		);

		//チェック
		$view = preg_replace('/[>][\s]+([^a-z])/u', '>$1', $this->view);
		$view = preg_replace('/[\s]+</u', '<', $view);

		$url = NetCommonsUrl::actionUrl(array(
			'plugin' => $this->plugin,
			'controller' => $this->_controller,
			'action' => 'search',
		));
		$this->assertInput('form', null, $url, $view);
		$this->assertInput('input', 'frame_id', $frameId, $view);
		$this->assertInput('input', 'detailed', null, $view);
		$this->assertInput('input', 'keyword', null, $view);
		$this->assertInput('input', 'handle', null, $view);
		$this->assertInput('select', 'where_type', null, $view);
		$this->assertInput('input', 'period_start', null, $view);
		$this->assertInput('input', 'period_end', null, $view);

		$expected = '<select name="room_id" class="form-control" id="room_id">' .
					'<option value="">' . __d('searches', 'Not room specify') . '</option>' .
					'<option value="1">Public</option>' .
					'<option value="4">Public room</option>' .
					'<option value="10">Community room 1</option>' .
					'<option value="11">Community room 2</option>' .
					'</select>';
		$this->assertTextContains($expected, $view);
	}

}
