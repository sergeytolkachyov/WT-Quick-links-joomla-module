<?php
/**
 * @package       WT Quick links
 * @copyright     Copyright (C) 2021-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version       2.4.0
 * @license       GNU General Public License version 2 or later
 */

namespace Joomla\Module\Wtquicklinks\Site\Driver\Collection;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\Module\Wtquicklinks\Site\Driver\AbstractDriver;
use Joomla\Registry\Registry;

// No direct access to this file
defined('_JEXEC') or die;

class Menuitem extends AbstractDriver
{

    /**
     * Component for current driver
     *
     * @var string
     *
     * @since 2.4.0
     */
    protected string $component = 'com_menus';

	/**
	 * Current driver context
	 *
	 * @var string
	 *
	 * @since 2.0.0
	 */
	protected string $link_type = 'menuitem';

    /**
     * @var string
     * @since 2.4.0
     */
    protected string $link_type_label = 'MOD_WT_QUICK_LINKS_LINK_TYPE_MENU_ITEM';

	protected string $id_holder = 'menuitem';

    /**
     * @var string|null
     * @since 2.4.0
     */
    protected ?string $exclude_type = 'menuitem';

	/**
	 * Module params
	 *
	 * @var Registry
	 *
	 * @since 2.0.0
	 */
	public Registry $params;

	/**
	 * @param   Registry  $element
	 *
	 * @return string
	 *
	 * @throws Exception
	 * @since 2.4.0
	 */
	public function getLink(): string
	{
		$app = Factory::getApplication();
		$menu_item   = $app->getMenu('site')->getItem($this->element->get($this->id_holder));
		return Route::_($menu_item->link . '&Itemid=' . $menu_item->id);
	}

    /**
     * Return the callable function that checks exclude
     * rule for list element
     *
     *
     * @return callable
     *
     * @since 2.4.0
     */
    public function getExcludeRule(): callable
    {
        $input = $this->input;
        return function (Registry $element) use ($input) {
            $itemId    = $input->get('Itemid');
            if (in_array($itemId, (array) $element->get('exclude_menu_items'))) {
                return true;
            }

            return false;
        };
    }

    /**
     * Link type XML field.
     * Return XML string for module settings Joomla Form in admin panel.
     *
     * @return string
     *
     * @since 2.4.0
     */
    public function getLinkTypeXMLField():string
    {
        return '
        <field type="menuitem"
               name="'.$this->id_holder.'"
               label="MOD_WT_QUICK_LINKS_LINK_TYPE_MENU_ITEM"
               showon="link_type:'.$this->link_type.'[AND]use_link:1"/>
               ';
    }

    /**
     * Exclude link type XML field.
     * Return XML string for module settings Joomla Form in admin panel.
     *
     * @return string
     *
     * @since 2.4.0
     */
    public function getExcludeTypeXMLField():string
    {
        return '
            <field type="menuitem"
                   name="exclude_menu_items"
                   label="MOD_WT_QUICK_LINKS_EXCLUDE_MENU_ITEMS"
                   multiple="true"
                   description="MOD_WT_QUICK_LINKS_EXCLUDE_MENU_ITEMS_DESC"
                   showon="exclude_type:'.$this->exclude_type.'[AND]exclude:1[AND]use_link:1"
            />
               ';
    }
}