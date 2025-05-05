<?php
/**
 * @package       WT Quick links
 * @copyright   Copyright (C) 2022-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version     2.3.0
 * @license     GNU General Public License version 2 or later
 */

/**
 *
 *  Variables
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
/**
 * @var object $item Link item object.
 */
// Uncomment to see all link data
// dump($item);
?>

<?php

if (!empty($item->link_additional_text)) {
    echo HTMLHelper::_('content.prepare', $item->link_additional_text);
}


