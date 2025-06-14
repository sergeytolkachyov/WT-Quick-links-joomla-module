<?php
/**
 * @package       WT Quick links
 * @copyright     Copyright (C) 2021-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version       2.4.0.1
 * @license       GNU General Public License version 2 or later
 */

namespace Joomla\Module\Wtquicklinks\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;
use function defined;

defined('_JEXEC') or die;

/**
 * Dispatcher class for mod_wt_quick_links
 *
 * @since  2.0.0
 */
class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;

    /**
     * Returns the layout data.
     *
     * @return  array
     *
     * @since   2.0.0
     */
    protected function getLayoutData() : array
    {
        $data = parent::getLayoutData();
        $data['list'] = $this->getHelperFactory()->getHelper('WtquicklinksHelper')->getList($data['params'], $this->getApplication());
        $data['moduleclass_sfx'] = (!empty($data['params']->get('moduleclass_sfx'))) ? htmlspecialchars($data['params']->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8') : '';

        $wa = $this->getApplication()->getDocument()->getWebAssetManager();
        $tmpl_css = explode(':', $data['params']->get('layout'));
        $tmpl_css_file = $tmpl_css[1];

        if (file_exists('media/mod_wt_quick_links/css/' . $tmpl_css_file . '.css'))
        {
            $wa->registerAndUseStyle('mod.wtquicklinks.' . $tmpl_css_file, 'media/mod_wt_quick_links/css/' . $tmpl_css_file . '.css');
        }

        return $data;
    }
}