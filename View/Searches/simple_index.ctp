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
				echo $this->NetCommonsForm->input('keyword', array(
					'id' => 'simple-keyword-' . Current::read('Frame.id'),
					'label' => false,
					'div' => false,
					'class' => 'form-control allow-submit',
					'placeholder' => __d('searches', 'Free keywords.'),
					'value' => Hash::get($query, 'keyword'),
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