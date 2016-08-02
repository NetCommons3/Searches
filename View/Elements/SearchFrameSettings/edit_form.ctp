<?php
/**
 * 表示方法変更element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->NetCommonsForm->hidden('Frame.id'); ?>
<?php echo $this->NetCommonsForm->hidden('SearchFrameSetting.id'); ?>
<?php echo $this->NetCommonsForm->hidden('SearchFrameSetting.frame_key'); ?>

<div class="panel panel-default">
	<div class="panel-heading">
		<?php echo __d('searches', 'Select plugin to default search'); ?>
	</div>

	<div class="panel-body">
		<div class="form-inline clearfix">
			<?php
				echo $this->PluginsForm->checkboxPluginsRoom(
					'SearchFramesPlugin.plugin_key',
					array(
						'div' => array('class' => 'plugin-checkbox-outer'),
						'default' => Hash::get($this->request->data, 'SearchFramesPlugin.plugin_key'),
					)
				);
			?>
		</div>
	</div>
</div>
