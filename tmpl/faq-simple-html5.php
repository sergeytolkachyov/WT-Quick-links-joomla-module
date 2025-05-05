<?php
/**
 * @package       WT Quick links
 * @copyright   Copyright (C) 2022-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version     2.3.0
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
?>
<div class="d-flex flex-column <?php echo $moduleclass_sfx; ?>" itemscope itemtype="https://schema.org/FAQPage">
    <?php foreach ($list as $item) : ?>
        <div itemprop="mainEntity" itemscope itemtype="https://schema.org/Question">
            <details class="p-2 bg-light border border-light mb-2 shadow-sm">
                <summary class="py-2" itemprop="name" <?php  echo (!empty($item->onclick) ? 'onclick="' . $item->onclick . '"' : ''); ?>>
                    <h2 class="d-inline h5"><?php echo $item->link_text; ?></h2>
                </summary>
                <?php if ($item->link_additional_text) : ?>
                    <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <div itemprop="text">
                            <?php echo HTMLHelper::_('content.prepare', $item->link_additional_text); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </details>
        </div>
    <?php endforeach; ?>
</div>