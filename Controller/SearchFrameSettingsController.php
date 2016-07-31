<?php
/**
 * SearchFrameSettings Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('SearchesAppController', 'Searches.Controller');

/**
 * SearchFrameSettings Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Searches\Controller
 */
class SearchFrameSettingsController extends SearchesAppController {

/**
 * layout
 *
 * @var array
 */
	public $layout = 'NetCommons.setting';

/**
 * 使用するComponent
 *
 * @var array
 */
	public $components = array(
		'NetCommons.Permission' => array(
			'allow' => array(
				'edit' => 'page_editable',
			),
		),
		'PluginManager.PluginsForm' => array('findOptions' => array(
			'conditions' => array(
				'Plugin.display_topics' => true,
			),
		)),
	);

/**
 * 使用するModels
 *
 * @var array
 */
	public $uses = array(
		'PluginManager.Plugin',
		'Searches.SearchFrameSetting',
	);

/**
 * 使用するHelpers
 *
 * @var array
 */
	public $helpers = array(
		'Blocks.BlockForm',
		'Blocks.BlockTabs' => array(
			'mainTabs' => array(
				'frame_settings' => array('url' => array('controller' => 'search_frame_settings')),
			),
		),
		'NetCommons.DisplayNumber',
	);

/**
 * edit
 *
 * @return void
 */
	public function edit() {
		$this->PluginsForm->setPluginsRoomForCheckbox($this, $this->PluginsForm->findOptions);

		$options = Hash::extract(
			$this->viewVars['pluginsRoom'], '{n}.Plugin.key'
		);

		if ($this->request->is('put') || $this->request->is('post')) {
			//登録処理
			$data = $this->data;

			$data['SearchFramesPlugin']['plugin_key'] = Hash::get($data, 'SearchFramesPlugin.plugin_key');
			if (! $data['SearchFramesPlugin']['plugin_key']) {
				$data['SearchFramesPlugin']['plugin_key'] = array();
			}

			if ($this->SearchFrameSetting->saveSearchFrameSetting($data)) {
				$this->redirect(NetCommonsUrl::backToPageUrl(true));
				return;
			}
			$this->NetCommons->handleValidationError($this->SearchFrameSetting->validationErrors);

		} else {
			//新着設定を取得
			$this->request->data = $this->SearchFrameSetting->getSearchFrameSetting();
			$this->request->data['Frame'] = Current::read('Frame');
		}
	}
}
