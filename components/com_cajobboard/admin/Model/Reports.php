<?php
/**
 * Admin Reports Model (PDF reports generated from various sources, like Analytics)
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

use \FOF30\Container\Container;
use \Calligraphic\Cajobboard\Admin\Model\BaseDataModel;
use \Calligraphic\Cajobboard\Admin\Helper\Enum\DaysOfWeekEnum;

/**
 * Fields:
 *
 * UCM
 * @property int            $report_id         Surrogate primary key.
 * @property string         $slug             Alias for SEF URL.
 *
 * FOF "magic" fields
 * @property bool           $featured         Whether this report is featured or not.
 * @property int            $hits             Number of hits this report has received.
 * @property int            $created_by       Userid of the creator of this report.
 * @property string         $createdOn        Date this report was created.
 * @property int            $modifiedBy       Userid of person that last modified this report.
 * @property string         $modifiedOn       Date this report was last modified.
 *
 * SCHEMA: Joomla UCM fields, used by Joomla!s UCM when using the FOF ContentHistory behaviour
 * @property string         $publish_up       Date and time to change the state to published, schema.org alias is datePosted.
 * @property string         $publish_down     Date and time to change the state to unpublished.
 * @property int            $version          Version of this item.
 * @property int            $ordering         Order this record should appear in for sorting.
 * @property object         $metadata         JSON encoded metadata field for this item.
 * @property string         $metakey          Meta keywords for this item.
 * @property string         $metadesc         Meta description for this item.
 * @property string         $xreference       A reference to enable linkages to external data sets, used to output a meta tag like FB open graph.
 * @property string         $params           JSON encoded parameters for this item.
 * @property string         $language         The language code for the article or * for all languages.
 * @property int            $cat_id           Category ID for this item.
 * @property int            $hits             Number of hits the item has received on the site.
 * @property int            $featured         Whether this item is featured or not.
 * @property string         $note             A note to save with this item for use in the back-end interface.
 *
 * SCHEMA: Thing
 * @property string         $name             A title to use for the report.
 * @property string         $description      A description of the report.
 *
 * Thing(additionalType) -> Schedule
 * @property string         $repeat_frequency  How often this report should be generated. Use ISO 8601 duration format, e.g. PM1 for monthly, PW1 for weekly, PD1 for daily, PT0S for never-recurring.
 * @property int            $by_day            Which day(s) of the week this report should be generated on. Auto-filled to current day for one-time reports. Uses DaysOfWeekEnum helper.
 * @property int            $repeat_count      The number of times this report should be generated. Set to any non-positive integer value or null for recurring.
 *
 * SCHEMA: Message
 * @property string         $date_sent         The date the report was last sen.
 * @property string         message_attachment The URL of the Analytics view that should be used to generate the PDF file.
 */
class Reports extends BaseDataModel
{
  use \FOF30\Model\Mixin\Assertions;

	/**
	 * @param   Container $container The configuration variables to this model
	 * @param   array     $config    Configuration values for this model
	 *
	 * @throws \FOF30\Model\DataModel\Exception\NoTableColumns
	 */
	public function __construct(Container $container, array $config = array())
	{
    /* Set up config before parent constructor */

    // Not using convention for table names or primary key field
		$config['tableName'] = '#__cajobboard_reports';
    $config['idFieldName'] = 'report_id';

    // Define a contentType to enable the Tags behaviour
    $config['contentType'] = 'com_cajobboard.reports';

    // Set an alias for the title field for DataModel's check() method's slug field auto-population
    $config['aliasFields'] = array('title' => 'name');

    // Add behaviours to the model. Filters, Created, and Modified behaviours are added automatically.
    $config['behaviours'] = array(
      'Access',     // Filter access to items based on viewing access levels
      'Assets',     // Add Joomla! ACL assets support
      'Category',   // Set category in new records
      'Check',      // Validation checks for model, over-rideable per model
      //'ContentHistory', // Add Joomla! content history support
      'Enabled',    // Filter access to items based on enabled status
      'Language',   // Filter front-end access to items based on language
      'Metadata',   // Set the 'metadata' JSON field on record save
      'Ordering',   // Order items owned by featured status and then descending by date
      //'Own',        // Filter access to items owned by the currently logged in user only
      //'PII',        // Filter access for items that have Personally Identifiable Information
      'Publish',    // Set the publish_on field for new records
      'Slug',       // Backfill the slug field with the 'title' property or its fieldAlias if empty
      //'Tags'        // Add Joomla! Tags support
    );

    /* Parent constructor */
    parent::__construct($container, $config);

    /* Set up relations after parent constructor */

    // many-to-one FK to  #__cajobboard_persons
    $this->belongsTo('ToRecipient', 'Persons@com_cajobboard', 'to_recipient', 'id');
  }


  /**
	 * @throws    \RuntimeException when the assertion fails
	 *
	 * @return    $this   For chaining.
	 */
	public function check()
	{
    $this->assertNotEmpty($this->name, 'COM_CAJOBBOARD_REPORTS_TITLE_ERR');

		parent::check();

    return $this;
  }
}
