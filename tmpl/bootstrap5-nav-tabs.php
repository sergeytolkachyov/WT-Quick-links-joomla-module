<?php
/**
 * @package     Wt Quick Links
 * @copyright   Copyright (C) 2022-2023 Sergey Tolkachyov. All rights reserved.
 * @author      Sergey Tolkachyov - https://web-tolk.ru
 * @link 		https://web-tolk.ru
 * @version 	2.1.0
 * @license     GNU General Public License version 2 or later
 */
/**
 *      Variables
 *  $item->link_text
 *  $item->link_image
 *  $item->link_icon_css
 *  $item->link_additional_text
 *  $item->media_type
 *  $item->responsive_images
 *  $item->link_video
 *  $item->link_video_poster
 *  $item->is_responsive_videos
 *  $item->responsive_videos
 *  $item->use_link
 *  $item->url
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Version;

defined('_JEXEC') or die;
if ((new Version())->isCompatible('4.0') == true)
{
	// Joomla 4
	Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('bootstrap.tab');
}
?>

<ul class="nav nav-tabs" id="wt-quick-links-<?php echo $module->id;?>-tab">
	<?php
	$i = 1;
	foreach ($list as $item) : ?>

	<li class="nav-item" role="presentation">

				<button class="nav-link <?php echo ($i == 1 ? 'active' : ''); ?>" id="wt-quick-links-<?php echo $module->id.'-'.$i;?>-header" type="button" data-bs-toggle="tab" data-bs-target="#wt-quick-links-<?php echo $module->id.'-'.$i;?>" aria-selected="<?php echo ($i == 1 ? 'true' : 'false'); ?>">
					<?php echo $item->link_text; ?>
				</button>
	</li>
	<?php
	$i++;
	endforeach; ?>
</ul>

<div class="tab-content" id="wt-quick-links-<?php echo $module->id;?>-content">
	<?php
	$i = 1;
	foreach ($list as $item) : ?>

		<div class="tab-pane fade <?php echo ($i == 1 ? 'show active' : ''); ?>" role="tabpanel" id="wt-quick-links-<?php echo $module->id.'-'.$i;?>" aria-labelledby="wt-quick-links-<?php echo $module->id.'-'.$i;?>-header">

			<?php echo $item->link_additional_text; ?>
		</div>
		<?php
		$i++;
	endforeach; ?>
</div>
