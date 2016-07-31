<?php
/**
 * 表示方法変更view
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<article class="block-setting-body">
	<?php echo $this->BlockTabs->main(BlockTabsHelper::MAIN_TAB_FRAME_SETTING); ?>

	<div class="tab-content">
		<?php echo $this->BlockForm->displayEditForm(array(
				'model' => 'SearchFrameSetting',
				'callback' => 'Searches.SearchFrameSettings/edit_form',
				'cancelUrl' => NetCommonsUrl::backToPageUrl(true),
			)); ?>
	</div>
</article>
