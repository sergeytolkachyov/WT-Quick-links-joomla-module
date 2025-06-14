<?php
/**
 * @package       WT Quick links
 * @copyright   Copyright (C) 2022-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version     2.4.0.1
 * @license     GNU General Public License version 2 or later
 */

/**
 * @var object $item
 * @var int $i foreach loop iterator
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
$link_text = $item->link_text;
if (!empty($item->link_icon_css)) {
    $link_text = '<i class="' . $item->link_icon_css . '"></i> ' . $link_text;
}

if ($item->use_link == 1 && !empty($item->url)) {
    $link_attribs = [
        'class'              => 'btn btn-sm text-nowrap',
//        'id'                 => 'link-'.$module->id.'-'.$i,
//        'data-any-attribute' => '',
    ];
    if (!empty($item->onclick)) {
        $link_attribs['onclick'] = $item->onclick;
    }

    echo HTMLHelper::link($item->url, $link_text, $link_attribs);
} else {
    echo '<span>' . $item->link_text . '</span>';
}

