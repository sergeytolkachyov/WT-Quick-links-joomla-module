<?php
/**
 * @package     Wt Quick Links
 * @copyright   Copyright (C) 2022 Sergey Tolkachyov. All rights reserved.
 * @link 		https://web-tolk.ru
 * @version 	1.3.0
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
/**
 *  $item->link_text
 *  $item->link_image
 *  $item->link_icon_css
 *  $item->link_additional_text
 *  $item->url
 */
?>
<nav>
	<ul class="nav mod_wt_quick_links my-2 py-1 flex-nowrap overflow-auto flex-lg-wrap <?php echo $moduleclass_sfx; ?> ">
	<?php foreach ($list as $item) : ?>
        <li>
            <a href="<?php echo $item->url; ?>" class="btn btn-sm text-nowrap">
                <?php echo $item->link_text; ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>