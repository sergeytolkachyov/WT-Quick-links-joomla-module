<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_archive
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
/**
 *  $item->link_text
 *  $item->link_image
 *  $item->link_icon_css
 *  $item->link_additional_text
 *  $item->url
 */

defined('_JEXEC') or die;
?>
<nav>
	<ul class="mod_wt_quick_links <?php echo $moduleclass_sfx; ?> ">
	<?php foreach ($list as $item) : ?>
        <li>
            <a href="<?php echo $item->url; ?>" class="btn btn-sm text-nowrap">
                <?php echo $item->link_text; ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>