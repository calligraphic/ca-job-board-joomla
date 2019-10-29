<?php
/**
 * Admin Data Feed Templates Model
 * 
 * Provides templates with embedded shortcodes to substitute out for HTML and plain-text emails
 *
 * @package   Calligraphic Job Board
 * @version   September 28, 2019
 * @author    Calligraphic, LLC http://www.calligraphic.design
 * @copyright Copyright (C) 2019 Calligraphic, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

namespace Calligraphic\Cajobboard\Admin\Model;

// no direct access
defined('_JEXEC') or die;

use \Calligraphic\Cajobboard\Admin\Model\Helper\TableFields;
use \FOF30\Container\Container;
use \FOF30\Model\DataModel;
use \FOF30\Model\DataModel\Exception\NoAssetKey;

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
 * @property string         $description__intro   Short description of the item.
 *
 * SCHEMA: none (internal use only)
 * @property string         $xml_template     XML template with shortcodes to generate from the data feed.
 */
class DataFeedTemplates extends DataModel
{
  /* Traits to include in the class */

  use \Calligraphic\Cajobboard\Admin\Model\Mixin\Asset;                // Joomla! role-based access control handling
  use \Calligraphic\Cajobboard\Admin\Model\Mixin\Constructor;          // Refactored base-class constructor, called from __construct method
  use \Calligraphic\Cajobboard\Admin\Model\Mixin\Core;                 // Utility methods
  use \Calligraphic\Cajobboard\Admin\Model\Mixin\JsonData;             // Methods for transforming between JSON-encoded strings and Registry objects
  use \Calligraphic\Cajobboard\Admin\Model\Mixin\Patches;              // Over-ridden FOF30 DataModel methods (some with PRs)
  use \Calligraphic\Cajobboard\Admin\Model\Mixin\TableFields;          // Use an array of table fields instead of database reads on each table
  use \Calligraphic\Cajobboard\Admin\Model\Mixin\Validation;           // Provides over-ridden 'check' method

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

    // @TODO: Add this to call the content history methods during create, save and delete operations. CHECK SYNTAX
    // JObserverMapper::addObserverClassToClass('JTableObserverContenthistory', 'Answers', array('typeAlias' => 'com_cajobboard.answers'));

    // Not using convention for table names or primary key field
		$config['tableName'] = '#__cajobboard_data_feed_templates';
    $config['idFieldName'] = 'data_feed_template_id';

    // Define a contentType to enable the Tags behaviour
    $config['contentType'] = 'com_cajobboard.data_feed_templates';

    // Set an alias for the title field for DataModel's check() method's slug field auto-population
		$config['aliasFields'] = array('title' => 'name');

    // Add behaviours to the model. Filters, Created, and Modified behaviours are added automatically.
    $config['behaviours'] = array(
			//'ContentHistory', // Add Joomla! content history support
			'Filters',		// Filter behaviour
      'Access',     // Filter access to items based on viewing access levels
      'Assets',     // Add Joomla! ACL assets support
      'Category',   // Set category in new records
      'Enabled',    // Filter access to items based on enabled status
      'Language',   // Filter front-end access to items based on language
      'Publish',    // Set the publish_on field for new records
      'Slug',       // Backfill the slug field with the 'title' property or its fieldAlias if empty

      /* Validation checks. Single slash is escaped to a double slash in over-ridden addBehaviour() method in Model/Mixin/Patches.php */

      'Check',            // Validation checks for model, over-rideable per model
      'Check/Title',      // Check length and titlecase the 'metadata' JSON field on record save

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

  // @TODO: Parser needs to handle XML CDATA tag around the shortcode, e.g. [CDATA[company]]
}
