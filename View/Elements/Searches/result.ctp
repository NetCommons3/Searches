<?php
/**
 * 新着表示itemエレメント
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<article class="topic-row-outer">
	<div class="clearfix">
		<div class="pull-left topic-title">
			<a href="<?php echo $result['Search']['url']; ?>" target="_blank">
				<?php echo h($result['Search']['display_title']); ?>
			</a>
		</div>

		<div class="pull-left topic-plugin-name">
			<span class="label label-default">
				<?php echo h($result['Plugin']['display_name']); ?>
			</span>
		</div>

		<div class="pull-left topic-datetime">
			<?php echo h($result['Search']['display_modified']); ?>
		</div>

		<div class="pull-left topic-room-name">
			<?php echo h($result['RoomsLanguage']['display_name']); ?>
		</div>

		<?php if ($result['CategoriesLanguage']['name']) : ?>
			<div class="pull-left topic-category-name">
				<?php echo h($result['CategoriesLanguage']['display_name']); ?>
			</div>
		<?php endif; ?>

		<div class="pull-left topic-handle-name">
			<?php echo $result['TrackableCreator']['avatar']; ?>
			<a ng-click="showUser($event, <?php echo $result['TrackableCreator']['id']; ?>)" ng-controller="Users.controller" href="#">
				<?php echo h($result['TrackableCreator']['handlename']); ?>
			</a>
		</div>
	</div>

	<div class="text-muted topic-summary">
		<?php echo $result['Search']['display_summary']; ?>
	</div>
</article>
