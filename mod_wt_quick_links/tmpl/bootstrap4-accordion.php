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
?>

<div class="accordion" id="wt-quick-links-<?php echo $module->id; ?>">
	<?php
	$i = 1;
	foreach ($list as $item) : ?>
		<div class="card">
			<div class="card-header" id="wt-quick-links-<?php echo $module->id . '-' . $i; ?>-header">
				<h2 class="mb-0" >
					<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#wt-quick-links-<?php echo $module->id . '-' . $i; ?>" aria-expanded="false" aria-controls="collapseOne" <?php  echo (!empty($item->onclick) ? 'onclick="' . $item->onclick . '"' : ''); ?>>
						<?php echo $item->link_text; ?>
					</button>
				</h2>
			</div>
			<div id="wt-quick-links-<?php echo $module->id . '-' . $i; ?>" class="collapse" aria-labelledby="wt-quick-links-<?php echo $module->id . '-' . $i; ?>-header" data-parent="#wt-quick-links-<?php echo $module->id; ?>">
				<div class="card-body">
                    <?php echo HTMLHelper::_('content.prepare', $item->link_additional_text); ?>
				</div>
			</div>
		</div>
        <?php
        $i++;
	endforeach; ?>
</div>