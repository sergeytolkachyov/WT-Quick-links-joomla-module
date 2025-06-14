<?php
/**
 * @package       WT Quick links
 * @copyright   Copyright (C) 2022-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version 	2.4.0.1
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
 *  $item->onclick
 */

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

$app->getDocument()->getWebAssetManager()->useScript('bootstrap.collapse');
?>
<div class="accordion" id="wt-quick-links-<?php echo $module->id; ?>">
	<?php
	$i = 1;
	foreach ($list as $item) : ?>
		<section class="accordion-item">
			<h2 class="accordion-header" id="wt-quick-links-<?php echo $module->id . '-' . $i; ?>-header">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#wt-quick-links-<?php echo $module->id . '-' . $i; ?>" aria-expanded="false" aria-controls="collapseOne" <?php  echo (!empty($item->onclick) ? 'onclick="' . $item->onclick . '"' : ''); ?>>
					<?php echo $item->link_text; ?>
				</button>
			</h2>
			<div id="wt-quick-links-<?php echo $module->id . '-' . $i; ?>" class="accordion-collapse collapse" aria-labelledby="wt-quick-links-<?php echo $module->id . '-' . $i; ?>-header" data-bs-parent="#wt-quick-links-<?php echo $module->id; ?>">
				<div class="accordion-body">
                    <?php echo HTMLHelper::_('content.prepare', $item->link_additional_text); ?>
				</div>
			</div>
		</section>
        <?php
        $i++;
	endforeach; ?>
</div>