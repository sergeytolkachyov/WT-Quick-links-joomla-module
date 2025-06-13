<?php
/**
 * @package       WT Quick links
 * @copyright   Copyright (C) 2022-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version 	2.4.0
 * @license     GNU General Public License version 2 or later
 */
/**
 * This layout is a wrapper for rendering your own
 * sublayouts from tmpl/sublayouts folder
 *
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

// Have we a responsive videos on sublayouts?
$hasResponsiveVideos = false;
$responsive_videos = [];
$i = 0;
foreach ($list as $item)
{
    if ($item->media_type == 'video'
        && $item->is_responsive_videos == 1
        && count((array)$item->responsive_videos) > 0
    ) {
        $responsive_videos[$i] = $item->responsive_videos;
        $hasResponsiveVideos = true;
    }
    $i++;
}
// We have some responsive videos
if($hasResponsiveVideos) {
    $doc = $app->getDocument();
    $wt_quick_links_responsive_videos = $doc->getScriptOptions('wt_quick_links_responsive_videos');
    if (is_array($wt_quick_links_responsive_videos))
    {
        $wt_quick_links_responsive_videos[$module->id] = $responsive_videos;
    }
    else
    {
        $wt_quick_links_responsive_videos = [
            $module->id => $responsive_videos
        ];
    }

    $doc->addScriptOptions('wt_quick_links_responsive_videos', $wt_quick_links_responsive_videos);
    $doc->getWebAssetManager()->useScript('core')
        ->registerAndUseScript('mod_wt_quick_links.responsive_videos', 'mod_wt_quick_links/mod_wt_quick_links_responsive_videos.js', ['relative' => true, 'version' => 'auto']);
}

?>
<div class="mod_wt_quick_links <?php echo $moduleclass_sfx; ?>" <?php echo ($hasResponsiveVideos ? 'data-wt-quick-links-responsive-videos="' . $module->id . '"' : '');?>>
    <?php
    $i=0;
    foreach ($list as $item)
    {
        if (!empty($item->sublayout))
        {
            // This line renders your own custom sublayout for each link item
            require ModuleHelper::getLayoutPath($module->module, 'sublayout/' . $item->sublayout);
        }
        $i++;
    }
    ?>
</div>