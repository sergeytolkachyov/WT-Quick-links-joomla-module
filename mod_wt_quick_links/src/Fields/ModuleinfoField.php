<?php
/**
 * @package       WT Quick links
 * @copyright   Copyright (C) 2021-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version 	2.4.0.1
 * @license     GNU General Public License version 2 or later
 */
namespace Joomla\Module\Wtquicklinks\Site\Fields;
defined('_JEXEC') or die;

use Joomla\CMS\Form\Field\SpacerField;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

class ModuleinfoField extends SpacerField
{

	protected $type = 'Moduleinfo';

	/**
	 * Method to get the field input markup for a spacer.
	 * The spacer does not have accept input.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.7.0
	 */
	protected function getInput()
	{
		return ' ';
	}

	/**
	 * Method to get the field title.
	 *
	 * @return  string  The field title.
	 *
	 * @since   1.7.0
	 */
	protected function getTitle()
	{
		return $this->getLabel();
	}

	/**
	 * @return  string  The field label markup.
	 *
	 * @since   1.7.0
	 */
	protected function getLabel()
	{
		$data   = $this->form->getData();
		$module = $data->get('module');
		$doc    = Factory::getApplication()->getDocument();
		$doc->addStyleDeclaration("
			.plugin-info-img-svg:hover * {
				cursor:pointer;
			}
		");

		$wt_module_info = simplexml_load_file(JPATH_SITE . "/modules/" . $module . "/" . $module . ".xml");

        return '
        </div>
		<div class="row g-0 w-100 p-3 shadow">
		    <div class="col-12 col-md-3">
			    <a href="https://web-tolk.ru" target="_blank">
					<svg class="plugin-info-img-svg" width="200" height="50" xmlns="http://www.w3.org/2000/svg">
						<g>
							<title>Go to https://web-tolk.ru</title>
							<text font-weight="bold" xml:space="preserve" text-anchor="start"
								  font-family="Helvetica, Arial, sans-serif" font-size="32" id="svg_3" y="36.085949"
								  x="8.152073" stroke-opacity="null" stroke-width="0" stroke="#000"
								  fill="#0fa2e6">Web</text>
							<text font-weight="bold" xml:space="preserve" text-anchor="start"
								  font-family="Helvetica, Arial, sans-serif" font-size="32" id="svg_4" y="36.081862"
								  x="74.239105" stroke-opacity="null" stroke-width="0" stroke="#000"
								  fill="#384148">Tolk</text>
						</g>
					</svg>
				</a>
		  </div>
		  <div class="col-12 col-md-9">
			<span class="badge bg-success text-white">v.' . $wt_module_info->version . '</span>
			' . Text::_(strtoupper($module) . '_DESC') . '
		  </div>
		</div>
		<div>
        ';
	}
}