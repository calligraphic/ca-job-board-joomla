<?php
/**
 * Video Objects Site HTML View
 *
 * @package   Calligraphic Job Board
 * @version   September 29, 2019
 * @author    Calligraphic, LLC http://www.calligraphic.design
 * @copyright Copyright (C) 2019 Calligraphic
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace Calligraphic\Cajobboard\Site\View\VideoObjects;

use \FOF30\Container\Container;
use \Calligraphic\Cajobboard\Site\View\Common\BaseHtml;

// no direct access
defined('_JEXEC') or die;

class Html extends BaseHtml
{
	/**
	 * Overridden. Executes before rendering the page for the Browse task.
	 */
	protected function getBrowseViewEagerRelations()
	{
    return array(
			'Author',
			'Category'
		);
  }
}
