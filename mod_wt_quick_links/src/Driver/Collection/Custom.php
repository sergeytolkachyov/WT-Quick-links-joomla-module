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

use Joomla\Module\Wtquicklinks\Site\Driver\AbstractDriver;
use Joomla\Registry\Registry;

// No direct access to this file
defined('_JEXEC') or die;

class Custom extends AbstractDriver
{
	/**
	 * Current driver context
	 *
	 * @var string
	 *
	 * @since 2.0.0
	 */
	protected string $link_type = 'custom';

    /**
     * @var string
     * @since 2.4.0
     */
    protected string $link_type_label = 'MOD_WT_QUICK_LINKS_LINK_TYPE_CUSTOM_LINK';

	protected string $id_holder = 'custom_link';

	/**
	 * Module params
	 *
	 * @var Registry
	 *
	 * @since 2.0.0
	 */
	public Registry $params;

	/**
	 * @return string
	 *
	 * @since 2.4.0
	 */
	public function getLink(): string
	{
		return $this->element->get($this->id_holder);
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
        <field type="text"
               name="custom_link"
               label="MOD_WT_QUICK_LINKS_LINK_TYPE_CUSTOM_LINK"
               showon="link_type:custom[AND]use_link:1"/>
               ';
    }
}