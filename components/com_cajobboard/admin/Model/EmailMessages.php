<?php
/**
 * Admin EmailMessages Model
 *
 * This model is where e-mail messages to send are queued to, using the "published" flag to indicate
 * state of whether the e-mail is sent or not. The CLI script should use transactions to pull mail
 * jobs from this model. The locked_on field is used for concurrency control. This model also serves
 * as a log of emails sent.
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

use \FOF30\Container\Container;
use \Calligraphic\Cajobboard\Admin\Model\BaseDataModel;

/**
 * Fields:
 *
 * UCM
 * @property int            $email_message_id     Surrogate primary key.
 * @property string         $slug             Alias for SEF URL.
 *
 * FOF "magic" fields
 * @property int            $asset_id           FK to the #__assets table for access control purposes.
 * @property int            $access             The Joomla! view access level.
 * @property int            $enabled            Send status: -2 for trashed and marked for deletion, -1 for mail delivery failure, 0 for not yet mailed, and 1 for mailed.
 * @property string         $created_on         Timestamp of record creation, auto-filled by save().
 * @property int            $created_by         User ID who created the record, auto-filled by save().
 * @property string         $modified_on        Timestamp of record modification, auto-filled by save(), touch().
 * @property int            $modified_by        User ID who modified the record, auto-filled by save(), touch().
 * @property string         $locked_on          Timestamp of record locking, auto-filled by lock(), unlock().
 * @property int            $locked_by          User ID who locked the record, auto-filled by lock(), unlock().
 *
 * SCHEMA: Joomla UCM fields, used by Joomla!s UCM when using the FOF ContentHistory behaviour
 * @property string         $params           JSON encoded parameters for this item.
 * @property string         $language         The language code for the article or * for all languages.
 * @property int            $cat_id           Category ID for this item.
 * @property string         $note             A note to save with this item for use in the back-end interface.
 *
 * SCHEMA: Thing
 * @property string         $name             A title to use for the email message.
 * @property string         $description      A description of the email message.
 *
 * SCHEMA: CreativeWork
 * @property string         $text             Body field of the e-mail.
 *
 * SCHEMA: Message
 * @property string         $date_sent                      The date/time at which the message was sent via the MTA.
 * @property string         $recipient__additional_name     An additional name for  of the recipient, can be used for a middle name.
 * @property string         $recipient__email               Email address of the recipient.
 * @property string         $recipient__family_name         Family name of the recipient. In the U.S., the last name of an Person.
 * @property string         $recipient__given_name          Given name of the recipient. In the U.S., the first name of a Person.
 * @property string         $recipient__honorific_prefix    An honorific prefix preceding the recipient's name such as Dr. / Mrs. / Mr.
 * @property string         $recipient__honorific_suffix    An honorific suffix preceding the recipient's name such as M.D. / PhD / MSCSW.
 */
class EmailMessages extends BaseDataModel
{
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
		$config['tableName'] = '#__cajobboard_email_messages';
    $config['idFieldName'] = 'email_message_id';

    // Define a contentType to enable the Tags behaviour
    $config['contentType'] = 'com_cajobboard.email_messages';

    // Set an alias for the title field for DataModel's check() method's slug field auto-population
    $config['aliasFields'] = array('title' => 'name');

    // Add behaviours to the model. Filters, Created, and Modified behaviours are added automatically.
    $config['behaviours'] = array(
      'Access',     // Filter access to items based on viewing access levels
      'Assets',     // Add Joomla! ACL assets support
      //'ContentHistory', // Add Joomla! content history support
      //'Own',        // Filter access to items owned by the currently logged in user only
      //'PII',        // Filter access for items that have Personally Identifiable Information. ONLY for ATS screens, use view template PII access control for individual fields
      //'Tags'        // Add Joomla! Tags support
    );

    /* Parent constructor */
    parent::__construct($container, $config);

    /* Set up relations after parent constructor */

    // table field for inverseSideOfHasOne relation is in this model's table

    // one-to-one FK to #__cajobboard_email_message_templates
    $this->inverseSideOfHasOne('EmailMessageTemplate', 'EmailMessageTemplates@com_cajobboard', 'email_message_template', 'email_message_template_id');

    // table field for belongsTo relation is in this model's table

    // many-to-one FK to  #__cajobboard_persons
    $this->belongsTo('Author', 'Persons@com_cajobboard', 'created_by', 'id');

    // many-to-one FK to  #__cajobboard_organizations
    $this->belongsTo('Sender', 'Organizations@com_cajobboard', 'sender', 'organization_id');

    // many-to-one FK to  #__cajobboard_digital_documents
    $this->belongsTo('DigitalDocument', 'DigitalDocuments@com_cajobboard', 'message_attachment__document', 'digital_document_id');
  }

  // @TODO: Handle updating records for email bounces: Helper\EmailIncoming->getBouncedEmails()


  /**
	 * Method to get a collection of emails to send and lock them in the same transaction
	 *
	 * @return    \FOF30\Model\DataModel\DataCollection   A collection of model objects
	 */
	public function getAndLock()
	{
    // Use transactions, otherwise same as base model get(), something like:
    try
    {
      $db->transactionStart();

      $query = $db->getQuery(true);

      $values = array($db->quote('TEST_CONSTANT'), $db->quote('Custom'), $db->quote('/path/to/translation.ini'));

      $query->insert($db->quoteName('#__overrider'));
      $query->columns($db->quoteName(array('constant', 'string', 'file')));
      $query->values(implode(',',$values));

      $db->setQuery($query);
      $result = $db->execute();

      $db->transactionCommit();
    }
    catch (Exception $e)
    {
      // catch any database errors.
      $db->transactionRollback();

      //Log($e);
    }
  }
}
