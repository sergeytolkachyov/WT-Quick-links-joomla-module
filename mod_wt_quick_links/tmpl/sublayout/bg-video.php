<?php
/**
 * @package       WT Quick links
 * @copyright     Copyright (C) 2022-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version       2.4.0.1
 * @license       GNU General Public License version 2 or later
 */

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;


/**
 * @var object $item
 * @var int    $i foreach loop iterator
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

// dump($item);

?>

<div class="col-12 p-0 position-relative">
    <div class="card border-0 rounded-0">
        <?php if ($item->media_type == 'video') : ?>
            <?php  if ($item->is_responsive_videos == 1) : ?>
                <video id="wt-quick-links-responsive-videos-<?php echo $module->id; ?>-<?php echo $i; ?>"
                       poster=""
                       src=""
                       class="card-img rounded-0"
                       autoplay="autoplay"
                       muted="muted" loop="loop">
                </video>
            <?php else : ?>
                <video <?php echo($item->link_video_poster ? 'poster="' . $item->link_video_poster . '"' : ''); ?>
                        src="<?php
                        echo $item->link_video; ?>" class="card-img rounded-0" autoplay="autoplay"
                        muted="muted" loop="loop">
                </video>
            <?php endif; ?>
        <?php endif; ?>
        <div class="card-img-overlay d-flex align-items-center rounded-0" style="background-color: rgba(0,0,0,0.5);">
            <div class="card-body text-white">
                <?php if($item->use_link == 1 && !empty($item->url)) : ?>
                    <a href="<?php echo $item->url; ?>" class="stretched-link text-decoration-none"
                        <?php echo(!empty($item->onclick) ? 'onclick="' . $item->onclick . '"' : ''); ?>>
                    <?php endif;?>
                    <span class="h1 text-white"><?php  echo $item->link_text; ?></span>
                        <?php if($item->use_link == 1 && !empty($item->url)) : ?>
                    </a>
                <?php endif;?>
                <?php if ($item->link_additional_text) : ?>
                    <?php echo HTMLHelper::_('content.prepare', $item->link_additional_text); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
