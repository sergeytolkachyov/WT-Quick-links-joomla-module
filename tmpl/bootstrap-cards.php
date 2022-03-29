<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_wt_quick_links
 *
 * @copyright   Copyright (C) 2022 Sergey Tolkachyov. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;


/**
 * Module settings:
 * 1. Show module title - yes
 * 2. Module style - html5
 * 3. module tag - section
 * 4. module suffix -  row row-cols-2 row-cols-lg-4
 * @see https://getbootstrap.com/docs/4.6/layout/grid/#row-columns
 * @see https://getbootstrap.com/docs/4.6/components/card/#grid-cards
 *
 *      Variables
 *  $item->link_text
 *  $item->link_image
 *  $item->link_icon_css
 *  $item->link_additional_text
 *  $item->url
 */
?>

<?php foreach ($list as $item) : ?>
	<article class="col mb-3">
		<div class="card shadow-sm">
			<div class="card-body">
				<a href="<?php echo $item->url; ?>" class="btn btn-sm" title="<?php echo $item->link_additional_text;?>">
					<?php

					echo HTMLHelper::image($item->link_image, $item->link_text, [
						'loading' => 'lazy',
						'class'   => 'card-img-top'
					]);

					?>

					<h3 class="h5"><?php echo $item->link_text; ?></h3>
				</a>
			</div>
		</div>
	</article>
<?php endforeach; ?>

