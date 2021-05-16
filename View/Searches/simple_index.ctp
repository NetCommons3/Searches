<?php
/**
 * 検索view
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<header>
	<?php echo $this->NetCommonsForm->create(false,
			array('type' => 'get', 'url' => NetCommonsUrl::actionUrlAsArray(array('action' => 'search')))); ?>
		<?php echo $this->NetCommonsForm->hidden('frame_id', array('value' => Current::read('Frame.id'))); ?>

		<div class="input-group">
			<?php
				//フリーワード入力部品
				if ($isSafari) {
					$class = null;
				} else {
					$class = '{"allow-submit": SimpleSearch}';
				}
				echo $this->NetCommonsForm->input('keyword', array(
					'label' => false,
					'div' => false,
					'placeholder' => __d('searches', 'Free keywords.'),
					'ng-model' => 'SimpleSearch',
					'ng-init' => 'SimpleSearch=\'' . h(Hash::get($query, 'keyword')) . '\'',
					'ng-value' => 'SimpleSearch',
					'ng-class' => $class
				));
			?>
			<span class="input-group-btn">
				<?php
					//検索ボタン
					echo $this->Button->search(
						'<span class="hidden">' . __d('net_commons', 'Search') . '</span>',
						array('escape' => false, 'name' => null)
					);
				?>
			</span>
		</div>
	<?php echo $this->Form->end(); ?>
</header>