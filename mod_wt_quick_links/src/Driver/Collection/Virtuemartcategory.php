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
use Joomla\Module\Wtquicklinks\Site\Driver\AbstractDriver;
use Joomla\Registry\Registry;

// No direct access to this file
defined('_JEXEC') or die;

class Virtuemartcategory extends AbstractDriver
{
    /**
     * Component for current driver
     *
     * @var string
     *
     * @since 2.4.0
     */
    protected string $component = 'com_virtuemart';

	/**
	 * Current driver context
	 *
	 * @var string
	 *
	 * @since 2.0.0
	 */
	protected string $link_type = 'com_virtuemart';

    /**
     * @var string
     * @since 2.4.0
     */
    protected string $link_type_label = 'MOD_WT_QUICK_LINKS_LINK_TYPE_VIRTUEMART_CATEGORY';

	protected string $id_holder = 'virtuemartcategories';
    /**
     * @var string|null
     * @since 2.4.0
     */
    protected ?string $exclude_type = 'com_virtuemart_categories';

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
       return Route::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $this->element->get($this->id_holder));
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
            if ($input->get('option') == 'com_virtuemart'
                && (
                    (
                        $input->get('view') == 'category'
                        && in_array(
                            $input->get('virtuemart_category_id'),
                            (array) $element->get('exclude_virtuemartcategories')
                        )
                    )
                    || (
                        $input->get('view') == 'productdetails'
                        && in_array(
                            $input->get('virtuemart_category_id'),
                            (array) $element->get('exclude_virtuemartcategories')
                        )
                    )
                )
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
        <field addfieldpath="administrator/components/com_virtuemart/fields"
               type="vmcategories"
               name="'.$this->id_holder.'"
               label="MOD_WT_QUICK_LINKS_LINK_TYPE_VIRTUEMART_CATEGORY"
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
            <field addfieldpath="administrator/components/com_virtuemart/fields"
               type="vmcategories"
               name="exclude_virtuemartcategories"
               label="MOD_WT_QUICK_LINKS_EXCLUDE_VIRTUEMARTCATEGORIES"
               description="MOD_WT_QUICK_LINKS_EXCLUDE_VIRTUEMARTCATEGORIES_DESC"
               multiple="true"
               showon="exclude_type:'.$this->exclude_type.'[AND]exclude:1[AND]use_link:1"/>
               ';
    }
}