<?php
/**
 * Searches Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('SearchesAppController', 'Searches.Controller');

/**
 * Searches Controller
 *
 * @property PaginatorComponent $Paginator
 * @property PluginsFormComponent $PluginsForm
 * @property MobileDetectComponent $MobileDetect
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Searches\Controller
 */
class SearchesController extends SearchesAppController {

/**
 * サイト内リンクのID
 *
 * @var int
 */
	const LINK_ID_FORMAT = 'search-box-%s';

/**
 * 使用するComponent
 *
 * @var array
 */
	public $components = array(
		'Paginator',
		'PluginManager.PluginsForm' => array('findOptions' => array(
			'conditions' => array(
				'Plugin.display_search' => true,
			),
		)),
		'MobileDetect.MobileDetect',
	);

/**
 * 使用するModel
 *
 * @var array
 */
	public $uses = array(
		'PluginManager.Plugin',
		'Rooms.Room',
		'Searches.Search',
		'Searches.SearchFrameSetting',
	);

/**
 * 使用するHelpers
 *
 * @var array
 */
	public $helpers = array(
		'Topics.Topics',
	);

/**
 * beforeFilter
 *
 * @return void
 **/
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('search');

		$searchFrameSetting = $this->SearchFrameSetting->getSearchFrameSetting();
		$this->set('searchFrameSetting', $searchFrameSetting);

		$query = array(
			'plugin_key' => $searchFrameSetting['SearchFramesPlugin']['plugin_key'],
		);
		foreach ($this->request->query as $key => $value) {
			if ($value) {
				$query[$key] = $value;
			}
		}
		if (Hash::get($query, 'detailed', 'false') === 'false') {
			$query['detailed'] = 'false';
		} else {
			$query['detailed'] = 'true';
		}
		$this->set('query', Hash::remove($query, 'frame_id'));

		//参加ルーム
		$result = $this->Room->find('all', $this->Room->getReadableRoomsConditions(array(
			'Room.space_id !=' => Space::PRIVATE_SPACE_ID
		)));
		$rooms = [];
		$currentLangId = Current::read('Language.id');
		foreach ($result as $room) {
			$roomId = $room['Room']['id'];
			foreach ($room['RoomsLanguage'] as $roomLanguage) {
				if ($currentLangId == $roomLanguage['language_id']) {
					$rooms[$roomId] = $roomLanguage['name'];
					break;
				}
			}
		}
		$this->set('rooms', $rooms);

		//検索プラグイン
		$plugins = $this->Plugin->cacheFindQuery('all', Hash::merge(array(
			'recursive' => -1,
			'conditions' => array(
				$this->Plugin->alias . '.type' => Plugin::PLUGIN_TYPE_FOR_FRAME,
				$this->Plugin->alias . '.language_id' => Current::read('Language.id'),
			),
			'order' => array(
				$this->Plugin->alias . '.weight' => 'asc',
				$this->Plugin->alias . '.id' => 'asc',
			)
		), $this->PluginsForm->findOptions));

		$this->set('pluginsRoom', $plugins);
		//$this->PluginsForm->setPluginsRoomForCheckbox($this, $this->PluginsForm->findOptions);
	}

/**
 * index
 *
 * @return void
 */
	public function index() {
		//左右上下かどうかの判断
		$isNotCenter = Hash::get($this->request->params, 'requested') &&
				Current::read('PageContainer.container_type') !== Container::TYPE_MAIN;
		if ($isNotCenter) {
			$this->view = 'simple_index';
		} else {
			$this->view = 'index';
		}

		$this->set('results', array());
	}

/**
 * 検索
 *
 * @return void
 */
	public function search() {
		$this->Paginator->settings = array(
			'Search' => $this->Search->getQueryOptions('0', $this->viewVars['query'])
		);
		$results = $this->Paginator->paginate('Search');

		$paging = $this->request['paging'];
		$paging = Hash::remove($paging, 'Search.order');
		$paging = Hash::remove($paging, 'Search.options');
		$paging = Hash::remove($paging, 'Search.paramType');

		$this->set('results', $results);
		$this->set('paging', $paging['Search']);

		if ($this->viewVars['paging']['count'] > 0) {
			$this->view = 'index';
		} else {
			$this->view = 'not_found';
		}
	}

}
