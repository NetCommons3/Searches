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

<article class="search-paging">
	<?php echo sprintf(__d('searches', '%s of %s (Total: %s)'),
				$this->Paginator->counter('{:page}'),
				$this->Paginator->counter('{:pages}'),
				$this->Paginator->counter('{:count}')); ?>
</article>
