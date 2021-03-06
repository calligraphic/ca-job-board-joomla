<?php
/**
 * Admin Email Messages Model
 *
 * There is no site-side counterpart to EmailMessages, because it is a send-
 * only feature and the admin views are only for setting configuration to allow
 * non-developer updates to the HTML e-mail templates.
 *
 * This MVC triad allows using an HTML editor to modify the available
 * e-mail templates for tasks. It does not allow adding additional
 * templates to the database. Adding tasks requires adding appropriate
 * methods to the plg_cajobboard_mail plugin, and adding controller code
 * to dispatch events to the plugin task method.
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

use \Calligraphic\Cajobboard\Admin\Model\BaseDataModel;
use \Calligraphic\Cajobboard\Admin\Model\Interfaces\Core;
use \Calligraphic\Cajobboard\Admin\Model\Interfaces\Extended;
use \FOF30\Container\Container;

/**
 * Fields:
 *
 * UCM
 * @property int            $reference_id     Surrogate primary key.
 * @property string         $slug             Alias for SEF URL.
 *
 * FOF "magic" fields
 * @property bool           $featured         Whether this reference is featured or not.
 * @property int            $hits             Number of hits this reference has received.
 * @property int            $created_by       Userid of the creator of this reference.
 * @property string         $createdOn        Date this reference was created.
 * @property int            $modifiedBy       Userid of person that last modified this reference.
 * @property string         $modifiedOn       Date this reference was last modified.
 *
 * SCHEMA: Joomla UCM fields, used by Joomla!s UCM when using the FOF ContentHistory behaviour
 * @property string         $publish_up       Date and time to change the state to published, schema.org alias is datePosted.
 * @property string         $publish_down     Date and time to change the state to unpublished.
 * @property int            $version          Version of this item.
 * @property int            $ordering         Order this record should appear in for sorting.
 * @property string         $params           JSON encoded parameters for this item.
 * @property string         $language         The language code for the article or * for all languages.
 * @property int            $note             A note to save with this answer in the back-end interface.
 *
 * SCHEMA: Thing
 * @property string         $name             Machine name for this e-mail template. Aliased by title property.
 * @property string         $description      Description of this e-mail template.
 *
 * SCHEMA: none (internal use only)
 * @property string         $subject          Text template with shortcodes for the subject field of the e-mail.',
 * @property string         $body             HTML template with shortcodes for the body field of the e-mail.',
 */
class EmailMessageTemplates extends BaseDataModel implements Core, Extended
{
  /* Traits to include in the class */

  // Transformations for model properties (attributes) to an appropriate data type (e.g.
  // Registry objects). Validation checks and setting attribute values from state should
  // be done in Behaviours (which can be enabled and overridden per model).

  use \Calligraphic\Cajobboard\Admin\Model\Mixin\Attributes\Params;    // Attribute getter / setter

	/**
	 * Overrides the constructor to add the Filters behaviour
	 *
	 * @param Container $container
	 * @param array     $config
	 */
	public function __construct(Container $container, array $config = array())
	{
    /* Set up config before parent constructor */

    // Not using convention for table names or primary key field
		$config['tableName'] = '#__cajobboard_email_message_templates';
    $config['idFieldName'] = 'email_message_template_id';

    // Define a contentType to enable the Tags behaviour
    $config['contentType'] = 'com_cajobboard.email_message_templates';

    // Set an alias for the title field for DataModel's check() method's slug field auto-population
		$config['aliasFields'] = array('title' => 'name');

    // Add behaviours to the model. 'Filters' behaviour added by default in addBehaviour() method.
    $config['behaviours'] = array(

      /* Core UCM field behaviours */

      'Access',             // Filter access to items based on viewing access levels
      'Assets',             // Add Joomla! ACL assets support
      'Category',           // Set category in new records
      'Created',            // Update the 'created_by' and 'created_on' fields for new records
      //'ContentHistory',     // Add Joomla! content history support
      'Enabled',            // Filter access to items based on enabled status
      'Language',           // Filter front-end access to items based on language
      'Modified',           // Update the 'modified_by' and 'modified_on' fields for new records
      'Ordering',           // Order items owned by featured status and then descending by date
      //'Own',                // Filter access to items owned by the currently logged in user only
      //'PII',                // Filter access for items that have Personally Identifiable Information.
      'Publish',            // Set the 'publish_on' field for new records
      'Slug',               // Backfill the slug field with the 'title' property or its fieldAlias if empty
      //'Tags'                // Add Joomla! Tags support

      /* Validation checks. Single slash is escaped to a double slash in over-ridden addBehaviour() method in Model/Mixin/Core.php */

      'Check',              // Validation checks for model, over-rideable per model
      'Check/Title',        // Check length and titlecase the 'metadata' JSON field on record save

      /* Model property (attribute) Behaviours for validation and setting value from state */

      'DescriptionIntro',   // Check the length of the 'description__intro' field
    );

    /* Overridden constructor */
    $this->constructor($container, $config);

    /* Set up relations after parent constructor */

     // table field for belongsTo relation is in this model's table

    // many-to-one FK to  #__cajobboard_persons
		$this->belongsTo('Author', 'Persons@com_cajobboard', 'created_by', 'id');
  }

  //  @TODO: Need to send both HTML and text emails in the same message, for Outlook

	/**
	 * Unpublish the newly copied item
	 *
	 * @param EmailMessages $copy
	 */
	protected function onAfterCopy(EmailTemplates $copy)
	{
		// Unpublish the newly copied item
		if ($copy->enabled)
		{
			$this->publish(0);
		}
	}
}
