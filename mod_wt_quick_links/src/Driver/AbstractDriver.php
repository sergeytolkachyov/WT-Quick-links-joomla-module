<?php
/**
 * @package       WT Quick links
 * @copyright     Copyright (C) 2021-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version       2.4.0.1
 * @license       GNU General Public License version 2 or later
 */

namespace Joomla\Module\Wtquicklinks\Site\Driver;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;
use stdClass;

// No direct access to this file
defined('_JEXEC') or die;

abstract class AbstractDriver
{
    /**
     * Component for current driver
     *
     * @var string
     *
     * @since 2.4.0
     */
    protected string $component = '';

	/**
	 * Current link type. For example, 'com_content_article'
	 *
	 * @var string
     *
	 * @since 2.4.0
	 */
	protected string $link_type = '';

    /**
     * Link type field label
     *
     * @var string
     * @since 2.4.0
     */
    protected string $link_type_label = '';

	/**
	 * The name of the field where the id for the link or its value is stored
	 *
	 * @var string
	 * @since 2.4.0
	 */
	protected string $id_holder = '';

	/**
	 * Module params
	 *
	 * @var Registry
     *
	 * @since 2.4.0
	 */
	public Registry $params;

	/**
	 * @var Registry
	 * @since 2.4.0
	 */
	protected Registry $element;

    /**
     * @var \Joomla\Input\Input
     * @since 2.4.0
     */
    protected \Joomla\Input\Input $input;

    /**
     * @var string
     * @since 2.4.0
     */
    protected ?string $exclude_type = null;

    /**
	 * Driver constructor
	 *
     * @param Registry $params Module params
     *
	 * @since 2.4.0
	 */
	public function __construct(Registry $params)
	{
		$this->params = $params;
        $this->input =  Factory::getApplication()->getInput();
	}

	/**
	 *
	 * @return string Link for element
     *
	 * @since 2.4.0
	 */
	public function getLink(): string
	{
		return '';
	}

    /**
     * Is this element might be excluded?
     *
     * @param int $id Item id
     *
     * @return stdClass Item data
     *
     * @since 2.4.0
     */
    public function isExcluded(array $excludeRules): bool
    {
        $excluded = false;
        foreach ($excludeRules as $rule) {
            $excluded = $rule();
            if($excluded) {
                return $excluded;
            }
        }
        return $excluded;
    }

    /**
     * Get unique link type
     *
     * @return string
     *
     * @since 2.4.0
     */
    public function getLinkType(): string
	{
		return $this->link_type;
	}

    /**
     * Get field name in module params where
     * data for link is stored
     *
     * @return string
     *
     * @since 2.4.0
     */
    public function getIdHolder(): string
	{
		return $this->id_holder;
	}

    /**
     * Set the element from element list
     *
     * @param   Registry  $element
     *
     * @return AbstractDriver $this
     * @since 2.4.0
     */
    public function setElement(Registry $element):AbstractDriver
	{
		$this->element = $element;
        return $this;
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
    public function getExcludeType(): ?string
    {
        return $this->exclude_type;
    }

    /**
     * Return the name of link type for field label
     *
     * @return string
     *
     * @since 2.4.0
     */
    public function getLinkTypeLabel(): string
    {
        return Text::_($this->link_type_label);
    }

    /**
     * Get the filed XML string for driver field
     *
     * @return string
     *
     * @since 2.4.0
     */
    public function getLinkTypeXMLField():string
    {
        return '';
    }

    /**
     * Get the filed XML string for driver exclude field
     *
     * @return string
     *
     * @since 2.4.0
     */
    public function getExcludeTypeXMLField():string
    {
        return '';
    }

    /**
     * Get the component name for driver, if used
     *
     * @return string
     *
     * @since 2.4.0
     */
    public function getComponent(): string
    {
        return $this->component;
    }
}