<?php
/**
 * Admin Issue Report Categories Model for abusive content
 *
 * @package   Calligraphic Job Board
 * @version   0.1 May 1, 2018
 * @author    Calligraphic, LLC http://www.calligraphic.design
 * @copyright Copyright (C) 2018 Calligraphic, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

namespace Calligraphic\Cajobboard\Admin\Model;

// no direct access
defined('_JEXEC') or die;

use FOF30\Container\Container;
use \Calligraphic\Cajobboard\Admin\Model\BaseDataModel;

/**
 * Fields:
 *
 * UCM
 * @property int      $issue_report_category_id   Surrogate primary key.

 * SCHEMA: CreativeWork
 * @property string   $category   The category of abusive content report, e.g. spam, inappropriate language, etc.
 * @property string   $url        Link to schema for type of complaint, e.g. wikipedia page on Profanity
 */
class IssueReportCategories extends BaseDataModel
{
	/**
	 * @param   Container $container The configuration variables to this model
	 * @param   array     $config    Configuration values for this model
	 *
	 * @throws \FOF30\Model\DataModel\Exception\NoTableColumns
	 */
	public function __construct(Container $container, array $config = array())
	{
    // Not using convention for table names or primary key field
		$config['tableName'] = '#__cajobboard_issue_report_categories';
    $config['idFieldName'] = 'issue_report_category_id';

    // Define a contentType to enable the Tags behaviour
    $config['contentType'] = 'com_cajobboard.issue_report_categories';

    // Set an alias for the title field for DataModel's check() method's slug field auto-population
    $config['aliasFields'] = array('title' => 'name');

    parent::__construct($container, $config);
  }


	/**
	 * Perform checks on data for validity
	 *
	 * @return  static  Self, for chaining
	 *
	 * @throws \RuntimeException  When the data bound to this record is invalid
	 */
	public function check()
	{
    $this->assertNotEmpty($this->category, 'COM_CAJOBBOARD_ISSUE_REPORT_CATEGORIES_ERR_CATEGORY');
    $this->assertNotEmpty($this->url, 'COM_CAJOBBOARD_ISSUE_REPORT_CATEGORIES_ERR_URL');

		parent::check();

    return $this;
  }
}
