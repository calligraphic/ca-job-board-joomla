<?php
/**
 * Admin Organizational Types Model
 *
 * Example types are Corporation, Sole Proprietorship, Educational Institution
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
defined( '_JEXEC' ) or die;

use FOF30\Container\Container;
use \Calligraphic\Cajobboard\Admin\Model\BaseDataModel;

/*
 * Fields:
 *
 * @property int      $organization_type_id       Surrogate primary key

 * SCHEMA: Organizaton (OrganizationType) -> ItemList
 * @property string   $item_list_element          The type of organization, e.g. Employer, Recruiter, etc.
 * @property int      $item_list_order_type       The order this item should appear in the list.
 *
 * SCHEMA: Thing
 * @property string   $description                A description of the type of organization.
 * @property string   $url                        Link to schema for organization type, e.g. wikipedia page on Employer.
 */
class OrganizationTypes extends BaseDataModel
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
    // override default table names and primary key id
		$config['tableName'] = '#__cajobboard_organization_types';
    $config['idFieldName'] = 'organization_type_id';

    // Define a contentType to enable the Tags behaviour
    $config['contentType'] = 'com_cajobboard.organizational_types';

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
    // @TODO: Finish validation checks
    $this->assertNotEmpty($this->item_list_element, 'COM_CAJOBBOARD_ORGANIZATION_TYPE_ERR_TYPE_OF_ORGANIZATION');
    $this->assertNotEmpty($this->description, 'COM_CAJOBBOARD_ORGANIZATION_TYPE_ERR_DESCRIPTION');
    $this->assertNotEmpty($this->url, 'COM_CAJOBBOARD_ORGANIZATION_TYPE_ERR_ URL');

		parent::check();

    return $this;
  }
}
