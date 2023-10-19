<?php
/**
 * @package     Wt Quick Links
 * @copyright   Copyright (C) 2021-2023 Sergey Tolkachyov. All rights reserved.
 * @author      Sergey Tolkachyov - https://web-tolk.ru
 * @link 		https://web-tolk.ru
 * @version 	2.1.2
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Extension\Service\Provider\HelperFactory;
use Joomla\CMS\Extension\Service\Provider\Module;
use Joomla\CMS\Extension\Service\Provider\ModuleDispatcherFactory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * The WT JShopping Cart module service provider.
 *
 * @since  1.0.0
 */
return new class implements ServiceProviderInterface {
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	public function register(Container $container)
	{
		// Основной namespace модуля
		$container->registerServiceProvider(new ModuleDispatcherFactory('\\Joomla\\Module\\Wtquicklinks'));
		// Namespace модуля для хелпера
		$container->registerServiceProvider(new HelperFactory('\\Joomla\\Module\\Wtquicklinks\\Site\\Helper'));

		$container->registerServiceProvider(new Module);
	}
};