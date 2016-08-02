<?php
/**
 * 検索
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css('/searches/css/style.css');
?>

<?php echo $this->element('Searches.Searches/conditions'); ?>

<?php echo $this->element('Searches.Searches/paging'); ?>

<article class="topic-row-outer">
	<?php echo __d('searches', 'Not found.'); ?>
</article>