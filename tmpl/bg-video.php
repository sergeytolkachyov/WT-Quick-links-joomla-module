<?php
/**
 * @package       WT Quick links
 * @copyright   Copyright (C) 2022-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version    2.3.0
 * @license     GNU General Public License version 2 or later
 */

use Joomla\CMS\HTML\HTMLHelper;

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
 * @see https://developer.mozilla.org/en-US/docs/Web/API/Window/matchMedia
 * @see https://github.com/nolimits4web/swiper/blob/master/src/core/breakpoints/getBreakpoint.js
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
//echo '<pre>';
//print_r($list);
//echo '</pre>';

// Add responsive videos in JS object for frontend
$responsive_videos = [];
$i = 0;
foreach ($list as $item)
{
    if ($item->media_type == 'video'
        && $item->is_responsive_videos == 1
        && count((array)$item->responsive_videos) > 0
    ) {
        $responsive_videos[$i] = $item->responsive_videos;
    }
    $i++;
}

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
?>
<div class="row" data-wt-quick-links-responsive-videos="<?php echo $module->id; ?>">
    <?php
    $i = 0;
    foreach ($list as $item) : ?>
        <article class="col-12 p-0 position-relative">
            <div class="card border-0" style="border-radius: 0rem;">
                <?php if ($item->media_type == 'video') : ?>
                    <?php if ($item->is_responsive_videos == 1) : ?>
                        <video id="wt-quick-links-responsive-videos-<?php echo $module->id; ?>-<?php echo $i; ?>"
                               poster=""
                               src=""
                               class="card-img"
                               autoplay="autoplay"
                               muted="muted" loop="loop" style="border-radius: 0rem;">
                        </video>
                    <?php else : ?>
                        <video <?php echo($item->link_video_poster ? 'poster="' . $item->link_video_poster . '"' : ''); ?>
                                src="<?php echo $item->link_video; ?>" class="card-img" autoplay="autoplay"
                                muted="muted" loop="loop" style="border-radius: 0rem;">
                        </video>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="card-img-overlay d-flex align-items-center"
                     style="background-color: rgba(0,0,0,0.5); border-radius: 0rem;">
                    <div class="card-body text-white">
                        <a href="<?php echo $item->url; ?>" class="stretched-link text-decoration-none" <?php  echo (!empty($item->onclick) ? 'onclick="'.$item->onclick.'"' : ''); ?>>
                            <h1 class="text-white"><?php echo $item->link_text; ?></h1>
                        </a>
                        <?php if ($item->link_additional_text) : ?>
                            <?php echo HTMLHelper::_('content.prepare', $item->link_additional_text); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </article>
        <?php
        $i++;
    endforeach; ?>
</div>