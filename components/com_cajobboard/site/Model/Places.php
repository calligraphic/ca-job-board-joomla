<?php
/**
 * Site Places Model
 *
 * @package   Calligraphic Job Board
 * @version   0.1 May 1, 2018
 * @author    Calligraphic, LLC http://www.calligraphic.design
 * @copyright Copyright (C) 2018 Calligraphic, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

namespace Calligraphic\Cajobboard\Site\Model;

// no direct access
defined( '_JEXEC' ) or die;

use FOF30\Container\Container;

/*
 * Fields:
 *
 * UCM
 * @property int            $place_id                       Surrogate primary key.
 * @property string         $slug                           Alias for SEF URL
 * FOF "magic" fields
 * @property int            $asset_id                       FK to the #__assets table for access control purposes.
 * @property int            $access                         The Joomla! view access level.
 * @property int            $enabled                        Publish status: -2 for trashed and marked for deletion, -1 for archived, 0 for unpublished, and 1 for published.
 * @property string         $created_on                     Timestamp of record creation, auto-filled by save().
 * @property int            $created_by                     User ID who created the record, auto-filled by save().
 * @property string         $modified_on                    Timestamp of record modification, auto-filled by save(), touch().
 * @property int            $modified_by                    User ID who modified the record, auto-filled by save(), touch().
 * @property string         $locked_on                      Timestamp of record locking, auto-filled by lock(), unlock().
 * @property int            $locked_by                      User ID who locked the record, auto-filled by lock(), unlock().
 * SCHEMA: Joomla UCM fields, used by Joomla!s UCM when using the FOF ContentHistory behaviour
 * @property string         $publish_up                     Date and time to change the state to published, schema.org alias is datePosted.
 * @property string         $publish_down                   Date and time to change the state to unpublished.
 * @property int            $version                        Version of this item.
 * @property int            $ordering                       Order this record should appear in for sorting.
 * @property object         $metadata                       JSON encoded metadata field for this item.
 * @property string         $metakey                        Meta keywords for this item.
 * @property string         $metadesc                       Meta descriptionfor this item.
 * @property string         $xreference                     A reference to enable linkages to external data sets, used to output a meta tag like FB open graph.
 * @property string         $params                         JSON encoded parameters for the content item.
 * @property string         $language                       The language code for the article or * for all languages.
 * @property int            $cat_id                         Category ID for this content item.
 * @property int            $hits                           Number of hits the content item has received on the site.
 * @property int            $featured                       Whether this content item is featured or not.
 * SCHEMA: Thing
 * @property string         $name                           A name for this place.
 * @property string         $description                    A long description of this place.
 * SCHEMA: PostalAddress
 * @property  string	      $branch_code                    A short textual code that uniquely identifies a place of business.
 * @property  string	      $fax_number                     The E.164 PSTN fax number.
 * @property  bool		      $public_access                  A flag to signal that the Place is open to public visitors. If this property is omitted there is no assumed default boolean value.
 * SCHEMA: Place (geo) -> GeoCoordinates NOTE: https://schema.org/GeoCoordinates has separate latitude, longitude properties instead of using GIS point.
 * @property  int   	      $geo                            Latitude and longitude of place using MySQL GIS spatial data type. FK to #__cajobboard_geo_coordinates(geo_coordinate_id)
 * Place (address) -> PostalAddress
 * @property  string	      $address__address_country       The two-letter ISO 3166-1 alpha-2 country code.
 * @property  string	      $address__address_locality      The locality, e.g. Mountain View.
 * @property  string	      $address__postal_code           The postal code, e.g. 94043.
 * @property  string	      $address__street_address        The street address, e.g. 1600 Amphitheatre Pkwy.
 * @property  int			      $address__address_region        The name of the region, e.g. California', FK to #__cajobboard_util_address_region(address_region)
 * @property  string        $telephone                      The E.164 PSTN telephone number.
 * @property  string	      $openingHoursSpecification      The days and times this location is open.
 * @property  int			      $Logo                           A logo image that represents this place. FK to #__cajobboard_images(image_id)
 * @property  int			      $Photo                          One or more photographs of this place. FK M:M relationship in to #__cajobboard_images(image_id)
 */
class Places extends \Calligraphic\Cajobboard\Admin\Model\Places
{
	/**
	 * @param   Container $container The configuration variables to this model
	 * @param   array     $config    Configuration values for this model
	 *
	 * @throws NoTableColumns
	 */
	public function __construct(Container $container, array $config = array())
	{
    parent::__construct($container, $config);
  }
}
