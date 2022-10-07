<?php
/**
 * @package     Wt Quick Links
 * @copyright   Copyright (C) 2022 Sergey Tolkachyov. All rights reserved.
 * @link 		https://web-tolk.ru
 * @version 	1.4.0
 * @license     GNU General Public License version 2 or later
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Version;
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

			<?php
			// Use HTML5 picture tag for responsive images
			if ($item->responsive_images && count((array) $item->responsive_images) > 0) :?>

			<picture>
				<?php

				foreach ($item->responsive_images as $responsive_image):
				if ((new Version())->isCompatible('4.0') == true)
				{
					// For Joomla 4
					$clean_image_path = HTMLHelper::cleanImageURL($responsive_image->image);
					$clean_image_path = $clean_image_path->url;

				}
				else
				{
					// For Joomla 3

					$clean_image_path = $responsive_image->image;
				}
				?>
				<source srcset="<?php echo $clean_image_path; ?>"
						media="<?php echo $responsive_image->media_query; ?>">

				<?php endforeach; ?>
				<?php endif; ?>
				<?php
				if ((new Version())->isCompatible('4.0') == true)
				{
					// Joomla 4

					$clean_image_path                        = HTMLHelper::cleanImageURL($item->link_image);
					$clean_image_path->attributes['class']   = 'card-img-top ' . $item->link_icon_css;
					$clean_image_path->attributes['loading'] = 'lazy';
					echo HTMLHelper::image($clean_image_path->url, $item->link_text, $clean_image_path->attributes);

				}
				else
				{
					// Joomla 3
					echo HTMLHelper::image($item->link_image, $item->link_text, [
						'loading' => 'lazy',
						'class'   => 'card-img-top ' . $item->link_icon_css
					]);
				}


				// Use HTML5 picture tag for responsive images - Close picture tag
				if ($item->responsive_images && count((array) $item->responsive_images) > 0) :?>
			</picture>
			<?php endif; ?>
			<div class="card-body">
					<a href="<?php echo $item->url; ?>" class="btn btn-sm stretched-link">
						<h3 class="h5"><?php echo $item->link_text; ?></h3>
					</a>
					<?php if($item->link_additional_text):?>
						<?php echo $item->link_additional_text;?>
					<?php endif; ?>
				
			</div>
		</div>
	</article>
<?php endforeach; ?>

