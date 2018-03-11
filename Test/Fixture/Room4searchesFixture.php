<?php
/**
 * Searches用のRoomFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Room4topicsFixture', 'Topics.Test/Fixture');

/**
 * Searches用のRoomFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Searches\Test\Fixture
 */
class Room4searchesFixture extends Room4topicsFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'Room';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'rooms';

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		// 'space_id' => '1' のルームをテストデータに追加
		$this->records[] = array(
			'space_id' => '1',
			'page_id_top' => '0',
			'parent_id' => '4',
			'lft' => '25',
			'rght' => '26',
			'active' => '1',
			'default_role_key' => 'general_user',
			'need_approval' => '0',
			'default_participation' => '0',
			'page_layout_permitted' => null,
			'theme' => null,
		);

		parent::init();
	}
}
