<?php
/**
 * @package     WT Quick Links
 * @copyright   Copyright (C) 2022-2025 Sergey Tolkachyov. All rights reserved.
 * @author      Sergey Tolkachyov - https://web-tolk.ru
 * @link 		https://web-tolk.ru
 * @version 	2.2.1
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
 *  $item->use_link
 *  $item->url
 *  $item->onclick
 */

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;
?>
<li>
    <?php if($item->use_link == 1&& !empty($item->url)) : ?>
        <a href="<?php echo $item->url; ?>" class="btn btn-sm text-nowrap" <?php  echo (!empty($item->onclick) ? 'onclick="' . $item->onclick . '"' : ''); ?>>
    <?php endif; ?>
    <?php echo $item->link_text; ?>
    <?php if($item->use_link == 1&& !empty($item->url)) : ?>
        </a>
    <?php endif; ?>
    <?php
    if (!empty($item->link_additional_text))
    {
        echo HTMLHelper::_('content.prepare', $item->link_additional_text);
    }
    ?>
</li>
