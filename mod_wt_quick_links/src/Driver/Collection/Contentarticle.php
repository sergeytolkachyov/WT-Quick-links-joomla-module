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
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\Module\Wtquicklinks\Site\Driver\AbstractDriver;
use Joomla\Registry\Registry;

// No direct access to this file
defined('_JEXEC') or die;

class Contentarticle extends AbstractDriver
{
    /**
     * Component for current driver
     *
     * @var string
     *
     * @since 2.4.0
     */
    protected string $component = 'com_content';

	/**
	 * Current driver context
	 *
	 * @var string
	 *
	 * @since 2.0.0
	 */
	protected string $link_type = 'com_content_article';

    /**
     * @var string
     * @since 2.4.0
     */
    protected string $link_type_label = 'MOD_WT_QUICK_LINKS_LINK_TYPE_COM_CONTENT_ARTICLE';


	protected string $id_holder = 'com_content_article_id';

    /**
     * @var string|null
     * @since 2.4.0
     */
    protected ?string $exclude_type = 'com_content_article';

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
	 * @throws Exception
	 * @since 2.4.0
	 */
	public function getLink(): string
	{
		$app   = Factory::getApplication();
		$id = $this->element->get($this->id_holder);
		$model = $app->bootComponent('com_content')
			->getMVCFactory()
			->createModel('Article', 'Site', ['ignore_request' => true]);
		// Set application parameters in model
		$model->setState('params', $app->getParams());
		$article = $model->getItem($id);

		return Route::link(
			'site',
			RouteHelper::getArticleRoute(
				$id,
				$article->catid,
				$article->language
			)
		);
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
            if ($input->get('option') == 'com_content'
                && $input->get('view') == 'article'
                && $input->get('id') == $element->get('exclude_content_article')
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
            <field
                    addfieldprefix="Joomla\Component\Content\Administrator\Field"
                    name="'.$this->id_holder.'"
                    type="modal_article"
                    label="MOD_WT_QUICK_LINKS_LINK_TYPE_COM_CONTENT_ARTICLE_SELECT_ARTICLE"
                    description="MOD_WT_QUICK_LINKS_LINK_TYPE_COM_CONTENT_ARTICLE_SELECT_ARTICLE_DESC"
                    select="true"
                    filter="integer"
                    showon="link_type:'.$this->link_type.'[AND]use_link:1"
            />
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
            <field
                    addfieldprefix="Joomla\Component\Content\Administrator\Field"
                    name="exclude_content_article"
                    type="modal_article"
                    label="MOD_WT_QUICK_LINKS_EXCLUDE_COM_CONTENT_ARTICLE_SELECT_ARTICLE"
                    description="MOD_WT_QUICK_LINKS_EXCLUDE_COM_CONTENT_ARTICLE_SELECT_ARTICLE_DESC"
                    select="true"
                    filter="integer"
                    showon="exclude_type:'.$this->exclude_type.'[AND]exclude:1[AND]use_link:1"
            />
           ';
    }
}