<?php
/**
 * SearchFrameSettingFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Summary for SearchFrameSettingFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Searches\Test\Fixture
 */
class SearchFrameSettingFixture extends CakeTestFixture {

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'frame_key' => 'Lorem ipsum dolor sit amet',
			'is_advanced' => 1,
			'created_user' => 1,
			'created' => '2016-05-02 06:35:38',
			'modified_user' => 1,
			'modified' => '2016-05-02 06:35:38'
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		require_once App::pluginPath('Searches') . 'Config' . DS . 'Schema' . DS . 'schema.php';
		$this->fields = (new SearchesSchema())->tables[Inflector::tableize($this->name)];
		parent::init();
	}

}
