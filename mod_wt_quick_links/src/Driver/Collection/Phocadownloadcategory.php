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
use Joomla\CMS\Router\Route;
use Joomla\Module\Wtquicklinks\Site\Driver\AbstractDriver;
use Joomla\Registry\Registry;

// No direct access to this file
defined('_JEXEC') or die;

class Phocadownloadcategory extends AbstractDriver
{
    /**
     * Component for current driver
     *
     * @var string
     *
     * @since 2.4.0
     */
    protected string $component = 'com_phocadownload';

    /**
     * Current driver context
     *
     * @var string
     *
     * @since 2.0.0
     */
    protected string $link_type = 'com_phocadownload';

    /**
     * @var string
     * @since 2.4.0
     */
    protected string $link_type_label = 'MOD_WT_QUICK_LINKS_LINK_TYPE_PHOCADOWNLOAD_CATEGORY';

    /**
     * @var string
     * @since 2.4.0
     */
    protected string $id_holder = 'phocadownload';

    /**
     * @var string|null
     * @since 2.4.0
     */
    protected ?string $exclude_type = 'com_phocadownload_categories';

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
        return Route::_('index.php?option=com_phocadownload&view=category&id=' . $this->element->get($this->id_holder));
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
            if ($input->get('option') == 'com_phocadownload'
                && (
                    (
                        $input->get('view') == 'category'
                        && in_array($input->get('id'), (array) $element->get('exclude_phocadownload_categories'))
                    )
                    || (
                        $input->get('view') == 'item'
                        && in_array($input->get('catid'), (array) $element->get('exclude_phocadownload_categories'))
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
        <field addfieldpath="administrator/components/com_phocadownload/models/fields"
               type="phocadownloadcategory"
               name="'.$this->id_holder.'"
               label="MOD_WT_QUICK_LINKS_LINK_TYPE_PHOCADOWNLOAD_CATEGORY"
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
            <field addfieldpath="administrator/components/com_phocadownload/models/fields"
               type="phocadownloadcategory"
               name="exclude_phocadownload_categories"
               label="MOD_WT_QUICK_LINKS_EXCLUDE_PHOCADOWNLOAD_CATEGORIES"
               description="MOD_WT_QUICK_LINKS_EXCLUDE_PHOCADOWNLOAD_CATEGORIES_DESC"
               multiple="true"
               showon="exclude_type:'.$this->exclude_type.'[AND]exclude:1[AND]use_link:1"/>
               ';
    }
}