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
			<a href="<?php echo $result['search']['url']; ?>" target="_blank">
				<?php echo h($result['search']['displayTitle']); ?>
			</a>
		</div>

		<div class="pull-left topic-plugin-name">
			<span class="label label-default">
				<?php echo h($result['plugin']['displayName']); ?>
			</span>
		</div>

		<div class="pull-left topic-datetime">
			<?php echo h($result['search']['displayModified']); ?>
		</div>

		<div class="pull-left topic-room-name">
			<?php echo h($result['roomsLanguage']['displayName']); ?>
		</div>

		<?php if ($result['category']['name']) : ?>
			<div class="pull-left topic-category-name">
				<?php echo h($result['category']['displayName']); ?>
			</div>
		<?php endif; ?>

		<div class="pull-left topic-handle-name">
			<?php echo $result['trackableCreator']['avatar']; ?>
			<a ng-click="showUser($event, <?php echo $result['trackableCreator']['id']; ?>)" ng-controller="Users.controller" href="#">
				<?php echo h($result['trackableCreator']['handlename']); ?>
			</a>
		</div>
	</div>

	<div class="text-muted topic-summary">
		<?php echo $result['search']['displaySummary']; ?>
	</div>
</article>
