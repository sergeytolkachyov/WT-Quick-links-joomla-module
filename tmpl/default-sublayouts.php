<?php
/**
 * @package       WT Quick links
 * @copyright   Copyright (C) 2022-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
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

use Joomla\CMS\Helper\ModuleHelper;

defined('_JEXEC') or die;
?>
<div class="mod_wt_quick_links <?php echo $moduleclass_sfx; ?>">
    <?php
    foreach ($list as $item)
    {
        if (!empty($item->sublayout))
        {
            // This line renders your own custom sublayout for each link item
            require ModuleHelper::getLayoutPath($module->module, 'sublayout/' . $item->sublayout);
        }
    }
    ?>
</div>