<?php
/**
 * @package     Wt Quick Links
 * @copyright   Copyright (C) 2022-2023 Sergey Tolkachyov. All rights reserved.
 * @author      Sergey Tolkachyov - https://web-tolk.ru
 * @link 		https://web-tolk.ru
 * @version 	1.4.6
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;
use Joomla\CMS\Version;

FormHelper::loadFieldClass('spacer');

class JFormFieldModuleinfo extends JFormFieldSpacer
{

	protected $type = 'moduleinfo';

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
	return '';
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
		$data           = $this->form->getData();
		$module         = $data->get('module');

		$doc    = Factory::getApplication()->getDocument();
		$inline_css = ".wt-module-info{
				box-shadow: 0 .5rem 1rem rgba(0,0,0,.15); 
				padding:1rem; 
				margin-bottom: 2rem;
				display:flex;
				
			}
			.plugin-info-img{
			    margin-right:auto;
			    max-width: 100%;
			}
			.plugin-info-img svg:hover * {
				cursor:pointer;
			}
			.form-horizontal .controls {
				margin-left: 0px;
			}
			#fieldset-mod_wt_quick_links .control-group {
				flex-direction: column;
			}
			";
		if((new Version)->isCompatible('4.0')){
			// joomla 4
			$wa = $doc->getWebAssetManager();
			$wa->addInlineStyle($inline_css);
		} else {
			// Joomla 3
			$doc->addStyleDeclaration($inline_css);
		}

		$wt_module_info = simplexml_load_file(JPATH_SITE . "/modules/" . $module . "/" . $module . ".xml");
		?>

		<div class="row shadow-sm py-3">
			<div class="plugin-info-img col-2">
				<a href="https://web-tolk.ru" target="_blank">
					<svg width="200" height="50" xmlns="http://www.w3.org/2000/svg">
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
			<div class="col-10 p-2">
				<span class="badge bg-success">v.<?php echo $wt_module_info->version;?></span>
				<?php echo Text::_(strtoupper($module) . "_DESC");?>
			</div>
		</div>
		<?php



	}

}

?>