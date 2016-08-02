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

$camelizeData = $this->Topics->camelizeKeyRecursive($results);
?>

<?php echo $this->element('Searches.Searches/conditions'); ?>

<?php if ($camelizeData) : ?>
	<?php echo $this->element('Searches.Searches/paging'); ?>

	<?php foreach ($camelizeData as $result) : ?>
		<?php echo $this->element('Searches.Searches/result', array('result' => $result)); ?>
	<?php endforeach; ?>

	<?php echo $this->element('NetCommons.paginator'); ?>
<?php endif;