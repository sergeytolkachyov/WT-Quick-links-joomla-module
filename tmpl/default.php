<?php
/**
 * @package     Wt Quick Links
 * @copyright   Copyright (C) 2022 Sergey Tolkachyov. All rights reserved.
 * @link 		https://web-tolk.ru
 * @version 	1.4.0
 * @license     GNU General Public License version 2 or later
 */
/**
 * Module settings:
 * 1. Module style - html5
 * 2. module tag - nav
 *
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
 */

defined('_JEXEC') or die;
?>
<ul class="mod_wt_quick_links <?php echo $moduleclass_sfx; ?> ">
<?php foreach ($list as $item) : ?>
	<li>
		<a href="<?php echo $item->url; ?>" class="btn btn-sm text-nowrap">
			<?php echo $item->link_text; ?>
		</a>
	</li>
	<?php endforeach; ?>
</ul>