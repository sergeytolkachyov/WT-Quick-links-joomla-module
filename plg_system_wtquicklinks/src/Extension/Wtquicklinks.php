<?php
/**
 * @package       WT Quick links
 * @author        Sergey Tolkachyov
 * @copyright     Copyright (C) Sergey Tolkachyov, 2024. All rights reserved.
 * @version       2.4.0
 * @license       GNU General Public License version 3 or later. Only for *.php files!
 * @link          https://web-tolk.ru
 */

namespace Joomla\Plugin\System\Wtquicklinks\Extension;

use DOMDocument;
use DOMXPath;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\Event;
use Joomla\Event\SubscriberInterface;
use Joomla\Module\Wtquicklinks\Site\Driver\DriverFactory;
use Joomla\Registry\Registry;
use SimpleXMLElement;

use function defined;

defined('_JEXEC') or die;

/**
 *
 *
 * @since  2.5
 */
class Wtquicklinks extends CMSPlugin implements SubscriberInterface
{
    /**
     * Load the language file on instantiation.
     *
     * @var    boolean
     * @since  3.1
     */
    protected $autoloadLanguage = true;

    /**
     * Returns an array of events this subscriber will listen to.
     *
     * @return  array
     *
     * @since   4.0.0
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onContentPrepareForm' => 'onContentPrepareForm',
        ];
    }

    /**
     * @param   Event  $event
     *
     *
     * @since 2.4.0
     */
    public function onContentPrepareForm(Event $event): void
    {
        if (!$this->getApplication()->isClient('administrator'))
        {
            return;
        }

        [$form, $data] = array_values($event->getArguments());

        if (!($form instanceof Form))
        {
            return;
        }
        // Work only in com_modules
        if ($form->getName() !== 'com_modules.module')
        {
            return;
        }
        // Work only in wt quick links
        $fieldsets = $form->getFieldsets();
        if (!array_key_exists('mod_wt_quick_links', $fieldsets))
        {
            return;
        }

        $drivers     = DriverFactory::getDrivers((new Registry()));
        $fieldsField = new SimpleXMLElement(
            '<field name="fields"
					   label="MOD_WT_QUICK_LINKS_LINKS"
					   type="subform"
					   layout="joomla.form.field.subform.repeatable-table"
					   multiple="true"
					   parentclass="stack"
					   buttons="add,remove,move"
					   groupByFieldset="true"
				/>'
        );

        // Load subform XML
        $subform = new SimpleXMLElement(
            file_get_contents(JPATH_SITE . '/modules/mod_wt_quick_links/src/Subform/fields.xml')
        );

        // and modify it with DOMDocument
        $dom = new DOMDocument();
        $dom->loadXML($subform->asXML());

        // Find an element for to paste a new XML fields after
        $xpath         = new DOMXPath($dom);
        $items         = $xpath->query("//field[@name='link_type']");
        $referenceNode = $items->item(0);

        if ($referenceNode)
        {
            foreach ($drivers as $driver)
            {
                // Get new XML field from Driver
                $fieldXml = $driver->getLinkTypeXMLField();
                $fieldDom = new DOMDocument();
                $fieldDom->loadXML($fieldXml);

                $importedNode = $dom->importNode($fieldDom->documentElement, true);

                if ($referenceNode->nextSibling)
                {
                    $referenceNode->parentNode->insertBefore($importedNode, $referenceNode->nextSibling);
                } else
                {
                    $referenceNode->parentNode->appendChild($importedNode);
                }
            }
        }


        $excludeItems         = $xpath->query("//field[@name='exclude_type']");
        $excludeReferenceNode = $excludeItems->item(0);

        if ($excludeReferenceNode)
        {
            foreach ($drivers as $driver)
            {
                $fieldXml = $driver->getExcludeTypeXMLField();
                if (empty($fieldXml))
                {
                    continue;
                }
                $fieldDom = new DOMDocument();
                $fieldDom->loadXML($fieldXml);

                $importedNode = $dom->importNode($fieldDom->documentElement, true);

                if ($excludeReferenceNode->nextSibling)
                {
                    $excludeReferenceNode->parentNode->insertBefore($importedNode, $excludeReferenceNode->nextSibling);
                } else
                {
                    $excludeReferenceNode->parentNode->appendChild($importedNode);
                }
            }
        }

        $fieldsField->addAttribute('formsource', $dom->saveXML());

        $form->setField($fieldsField, '', true, 'mod_wt_quick_links');
    }
}
