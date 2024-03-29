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
		'plugin.pages.page4pages',
		'plugin.searches.search_frame_setting',
		'plugin.searches.search_frames_plugin',
		'plugin.searches.plugin4searches',
		//'plugin.topics.room4topics',
		'plugin.searches.room4searches',
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

		$expected = '<select name="target_room_id" class="form-control" id="target_room_id">' .
					'<option value="">' . __d('searches', 'Not room specify') . '</option>' .
					'<option value="2">Public</option>' .
					'<option value="5">Public room</option>' .
					'<option value="11">Community room 1</option>' .
					'<option value="12">Community room 2</option>' .
					'</select>';
		$this->assertTextContains($expected, $view);

		$expected = 'type="checkbox" name="plugin_key[]" checked="checked" value="bbses"';
		$this->assertTextContains($expected, $view);

		$expected = 'type="checkbox" name="plugin_key[]" checked="checked" value="blogs"';
		$this->assertTextContains($expected, $view);

		$expected = 'type="checkbox" name="plugin_key[]" checked="checked" value="announcements"';
		$this->assertTextContains($expected, $view);

		$expected = 'type="checkbox" name="plugin_key[]" value="faqs"';
		$this->assertTextContains($expected, $view);

		$expected = 'type="checkbox" name="plugin_key[]" value="circular_notices"';
		$this->assertTextContains($expected, $view);

		$expected = 'type="checkbox" name="plugin_key[]" value="calendars"';
		$this->assertTextContains($expected, $view);
	}

}
