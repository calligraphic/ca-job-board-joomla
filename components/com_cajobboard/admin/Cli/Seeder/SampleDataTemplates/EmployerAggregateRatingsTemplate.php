<?php

// Can't inherit from CommonTemplate, doesn't have or need fields: name, description


/**
 * POPO Object Template for Interviews model sample data seeding
 *
 * @package   Calligraphic Job Board
 * @version   September 12, 2019
 * @author    Calligraphic, LLC http://www.calligraphic.design
 * @copyright Copyright (C) 2019 Calligraphic, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace Calligraphic\Cajobboard\Admin\Cli\Seeder\SampleDataTemplates;

use Faker;

// no direct access
defined('_JEXEC') or die;

class EmployerAggregateRatingsTemplate extends CommonTemplate
{
	/**
	 * The employer whose reviews and ratings are being aggregated for, FK to #__cajobboard_organizations
	 *
	 * @property    int
   */
  public $item_reviewed;


  /**
	 * The count of total number of ratings.
	 *
	 * @property    int
   */
  public $rating_count;


	/**
	 * The count of total number of reviews.
	 *
	 * @property    int
   */
  public $review_count;


	/**
	 * The total rating sum for this employer. Get average by dividing with
   * rating_count. Default worstRating 1 and bestRating 5 assumed.
	 *
	 * @property    int
   */
  public $rating_value;


  /**
	 * Setters for QAPage fields
   */

  // $this->hasOne('ItemReviewed', 'Organizations@com_cajobboard', 'item_reviewed', 'organization_id');
  public function item_reviewed ($config, $faker)
  {
    $this->item_reviewed = $config->relationMapper->getFKValue('InverseSideOfHasOne', $config, true, $faker, 'Organizations');
  }


  public function rating_count ($config, $faker)
  {
    $this->rating_count = $faker->numberBetween(1, 5);
  }


  public function review_count ($config, $faker)
  {
    $this->review_count = $faker->numberBetween(1, 5);
  }


  public function rating_value ($config, $faker)
  {
    $this->rating_value = $faker->numberBetween(1, 5);
  }
}
