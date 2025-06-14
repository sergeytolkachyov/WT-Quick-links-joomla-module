<?php
/**
 * @package       WT Quick links
 * @copyright     Copyright (C) 2021-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version       2.4.0.1
 * @license       GNU General Public License version 2 or later
 */

namespace Joomla\Module\Wtquicklinks\Site\Driver\Collection;

use Exception;
use Joomla\CMS\Router\Route;
use Joomla\Component\Jshopping\Site\Helper\Helper;
use Joomla\Module\Wtquicklinks\Site\Driver\AbstractDriver;
use Joomla\Registry\Registry;

// No direct access to this file
defined('_JEXEC') or die;

class Joomshoppingcategory extends AbstractDriver
{
    /**
     * Component for current driver
     *
     * @var string
     *
     * @since 2.4.0
     */
    protected string $component = 'com_jshopping';

	/**
	 * Current driver context
	 *
	 * @var string
	 *
	 * @since 2.0.0
	 */
	protected string $link_type = 'com_jshopping';

    /**
     * @var string
     * @since 2.4.0
     */
    protected string $link_type_label = 'MOD_WT_QUICK_LINKS_LINK_TYPE_JOOMSHOPPING_CATEGORY';

    /**
     * @var string
     * @since 2.4.0
     */
    protected string $id_holder = 'jshoppingcategories';

    /**
     * @var string|null
     * @since 2.4.0
     */
    protected ?string $exclude_type = 'com_jshopping_categories';

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
	 * @since version
	 */
	public function getLink(): string
	{
        $link = '';
		if (!empty($this->element->get($this->id_holder))) {
			if (!file_exists(JPATH_SITE . '/components/com_jshopping/bootstrap.php')) {
				return $link;
			}

			require_once(JPATH_SITE . '/components/com_jshopping/bootstrap.php');
			$jshop_link = 'index.php?option=com_jshopping&controller=category&task=view&category_id=' . $this->element->get(
                    $this->id_holder
				);

			$jshop_itemid = Helper::getDefaultItemid($jshop_link);

            $link = Route::_($jshop_link . '&Itemid=' . $jshop_itemid);
		}
        return $link;
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
            if ($input->get('option') == 'com_jshopping'
                && ($input->get('controller') == 'category' || $input->get('view') == 'category')
                && in_array($input->get('category_id', 0), (array) $element->get('exclude_jshoppingcategories', []))
            )
            {
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
        <field addfieldprefix="Joomla\Module\Wtquicklinks\Site\Fields"
               type="jshoppingcategories"
               name="'.$this->id_holder.'"
               label="MOD_WT_QUICK_LINKS_LINK_TYPE_JOOMSHOPPING_CATEGORY"
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
            <field addfieldprefix="Joomla\Module\Wtquicklinks\Site\Fields"
               type="jshoppingcategories"
               name="exclude_jshoppingcategories"
               label="MOD_WT_QUICK_LINKS_EXCLUDE_JSHOPPINGCATEGORIES"
               description="MOD_WT_QUICK_LINKS_EXCLUDE_JSHOPPINGCATEGORIES_DESC"
               multiple="true"
               showon="exclude_type:'.$this->exclude_type.'[AND]exclude:1[AND]use_link:1"/>
               ';
    }
}