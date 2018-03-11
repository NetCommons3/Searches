<?php
/**
 * 検索条件
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Search', 'Searches.Model');
App::uses('SearchesController', 'Searches.Controller');
?>

<header class="search-conditions" ng-init="DetailedSearch=<?php echo h(Hash::get($query, 'detailed', 'false')); ?>; hashChange();"
			id="<?php echo sprintf(SearchesController::LINK_ID_FORMAT, Current::read('Frame.id')); ?>">

	<?php echo $this->NetCommonsForm->create(false,
			array('type' => 'get', 'url' => NetCommonsUrl::actionUrlAsArray(array('action' => 'search')))); ?>

		<?php echo $this->NetCommonsForm->hidden('frame_id', array('value' => Current::read('Frame.id'))); ?>
		<?php echo $this->NetCommonsForm->hidden('detailed', array('ng-value' => 'DetailedSearch')); ?>

		<div>
			<div class="form-group search-form-row">
				<?php
					//フリーワード
					echo $this->NetCommonsForm->input('keyword', array(
						'label' => false,
						'div' => false,
						'class' => 'form-control allow-submit',
						'placeholder' => __d('searches', 'Entry keywords'),
						'value' => Hash::get($query, 'keyword'),
					));
				?>
			</div>

			<div class="form-group search-form-row" ng-show="DetailedSearch" ng-cloak>
				<?php
					//ハンドル
					echo $this->NetCommonsForm->input('handle', array(
						'label' => false,
						'div' => false,
						'class' => 'form-control allow-submit',
						'placeholder' => __d('searches', 'Entry handle name'),
						'value' => Hash::get($query, 'handle'),
					));
				?>
			</div>

			<div class="clearfix">
				<div class="form-group form-inline pull-left search-form-row" ng-show="DetailedSearch" ng-cloak>
					<?php
						//検索タイプ
						echo $this->NetCommonsForm->input('where_type', array(
							'label' => false,
							'div' => false,
							'error' => false,
							'options' => array(
								Search::WHERE_TYPE_AND => __d('searches', 'AND search'),
								Search::WHERE_TYPE_OR => __d('searches', 'OR search'),
								Search::WHERE_TYPE_PHRASE => __d('searches', 'Phrse search'),
							),
							'value' => Hash::get($query, 'where_type'),
						));
					?>
				</div>

				<div class="form-group input-group form-inline pull-left search-form-row" ng-show="DetailedSearch" ng-cloak>
					<?php //期間 ?>
					<div class="input-group">
						<?php echo $this->NetCommonsForm->input('period_start', array(
							'type' => 'datetime',
							'placeholder' => 'yyyy-mm-dd',
							'label' => false,
							'div' => false,
							'error' => false,
							'datetimepicker-options' => '{\'format\': \'YYYY-MM-DD\'}',
							'ng-model' => 'PeriodStart',
							'ng-init' => 'PeriodStart=\'' . Hash::get($query, 'period_start') . '\'',
							'ng-value' => 'PeriodStart',
						)); ?>

						<span class="input-group-addon">
							<span class="glyphicon glyphicon-minus"></span>
						</span>

						<?php echo $this->NetCommonsForm->input('period_end', array(
							'type' => 'datetime',
							'placeholder' => 'yyyy-mm-dd',
							'label' => false,
							'div' => false,
							'error' => false,
							'datetimepicker-options' => '{\'format\': \'YYYY-MM-DD\'}',
							'ng-model' => 'PeriodEnd',
							'ng-init' => 'PeriodEnd=\'' . Hash::get($query, 'period_end') . '\'',
							'ng-value' => 'PeriodEnd',
						)); ?>
					</div>
				</div>

				<div class="form-group form-inline pull-left search-form-row" ng-show="DetailedSearch" ng-cloak>
					<?php
						//ルーム
						echo $this->NetCommonsForm->input('room_id', array(
							'label' => false,
							'div' => false,
							'error' => false,
							'options' => ['' => __d('searches', 'Not room specify')] + $rooms,
							'value' => Hash::get($query, 'room_id'),
						));
					?>
				</div>
			</div>
		</div>

		<div class="form-group" ng-show="DetailedSearch" ng-cloak>
			<?php echo $this->NetCommonsForm->label('plugin_key', __d('searches', 'Plugins')); ?>

			<div class="form-inline clearfix">
				<?php
					echo $this->PluginsForm->checkboxPluginsRoom(
						'plugin_key',
						array(
							'div' => array('class' => 'searches-plugin-checkbox-outer'),
							'default' => Hash::get($query, 'plugin_key'),
							'hiddenField' => false
						)
					);
				?>
			</div>
		</div>

		<div class="text-center">
			<?php
				//検索ボタン
				echo $this->Button->search(
					__d('net_commons', 'Search'), array('name' => null)
				);
				//クリアボタン
				if (Current::read('PageContainer.container_type') !== Container::TYPE_MAIN) {
					echo $this->Button->cancel(
						__d('searches', 'Clear'),
						NetCommonsUrl::blockUrl(array('action' => 'index'), false) .
							'#' . sprintf(SearchesController::LINK_ID_FORMAT, Current::read('Frame.id')),
						array('ng-disabled' => $this->params['action'] === 'index')
					);
				} else {
					echo $this->Button->cancel(
						__d('searches', 'Clear'),
						NetCommonsUrl::backToPageUrl() .
							'#' . sprintf(SearchesController::LINK_ID_FORMAT, Current::read('Frame.id')),
						array('ng-disabled' => $this->params['action'] === 'index')
					);
				}
			?>

			<?php //詳細検索ボタン ?>
			<a href="" class="btn btn-default btn-workflow" ng-show="!DetailedSearch" ng-click="DetailedSearch=!DetailedSearch" ng-cloak>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				<?php echo __d('searches', 'Detailed search'); ?>
			</a>
			<a href="" class="btn btn-default btn-workflow" ng-show="DetailedSearch" ng-click="DetailedSearch=!DetailedSearch" ng-cloak>
				<span class="glyphicon glyphicon-menu-up" aria-hidden="true"></span>
				<?php echo __d('searches', 'Close detailed search'); ?>
			</a>
		</div>
	<?php echo $this->Form->end(); ?>
</header>
