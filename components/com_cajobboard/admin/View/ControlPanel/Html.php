<?php
/**
 * Admin Control Panel HTML View
 *
 * @package   Calligraphic Job Board
 * @version   0.1 May 1, 2018
 * @author    Calligraphic, LLC http://www.calligraphic.design
 * @copyright Copyright (C) 2018 Calligraphic, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

namespace Calligraphic\Cajobboard\Admin\View\ControlPanel;

use FOF30\Container\Container;
use FOF30\View\View;
use JComponentHelper;
use JFactory;

// no direct access
defined('_JEXEC') or die;

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

class Html extends View
{
	/**
	 * The component-level parameters stored in #__extensions by com_config
	 *
	 * @var  \JRegistry
	 */
  protected $componentParams;

	/**
	 * Overridden. Load view-specific language file.
	 *
	 * @param   Container $container
	 * @param   array     $config
	 */
	public function __construct(Container $container, array $config = array())
	{
    parent::__construct($container, $config);

    // Get component parameters
    $this->componentParams = \JComponentHelper::getParams('com_cajobboard');

    // Using view-specific language files for maintainability
    $lang = JFactory::getLanguage();
    $lang->load('control_panel', JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_cajobboard', $lang->getTag(), true);

    // Load javascript file for Job Posting views
    $this->addJavascriptFile('media://com_cajobboard/js/controlPanel.js');
  }
}