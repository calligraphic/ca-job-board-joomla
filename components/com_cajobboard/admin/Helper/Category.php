<?php
/**
 * Helper class for managing categories in the Job Board
 *
 * @package   Calligraphic Job Board
 * @version   0.1 May 1, 2018
 * @author    Calligraphic, LLC http://www.calligraphic.design, Copyright (C) 2005 - 2019 Open Source Matters, Inc.
 * @copyright Copyright (C) 2018 Calligraphic, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace Calligraphic\Cajobboard\Admin\Helper;

// no direct access
defined('_JEXEC') or die;

use \Calligraphic\Cajobboard\Admin\Model\Exception\NoDefaultCategory;
use \Joomla\CMS\Factory;
use \Joomla\Utilities\ArrayHelper;
use \TheSeer\Tokenizer\Exception;

abstract class Category
{
  /**
   * Cached array of the category item objects.
   *
   * @var    array
   */
  protected static $categories = array();


  /**
   * Cached array mapping category id's to names
   *
   * @var    array
   */
  protected static $categoryMapIdToName = array();


  /**
   * Returns an array of categories for the given extension.
   *
   * @param   Array   $config     An array of configuration options. Options:
   *                              filter.published => array(0, 1) // default is to return unpublished and published categories
   *                              -2 for trashed and marked for deletion, -1 for archived, 0 for unpublished, and 1 for published.
   *                              filter.language => '*', filter.language => array('*')
   *                              filter.access => 1, for filtering based on "Public" view level permission
   *
   * @return  array               $category->id, $category->title, $category->language, $category->level
   */
  public static function getCategories($config = array('filter.published' => array(0, 1)))
  {
    $hash = md5(serialize($config));

    if (!isset(static::$categories[$hash]))
    {
      $config = (array) $config;

      $db     = Factory::getDbo();
      $user   = Factory::getUser();

      $groups = implode(',', $user->getAuthorisedViewLevels());

      $query = $db->getQuery(true)
        ->select  ('categories.id, categories.title, categories.level, categories.language')
        ->from    ('#__categories AS categories')
        ->where   ('categories.parent_id > 0');

      // Filter on the extension.
      $query->where('extension = ' . $db->quote('com_cajobboard'));

      // Filter on user access level
      $query->where('categories.access IN (' . $groups . ')');

      // Filter on the published state
      if (isset($config['filter.published']) && is_array($config['filter.published']))
      {
        $config['filter.published'] = ArrayHelper::toInteger($config['filter.published']);

        $query->where('categories.published IN (' . implode(',', $config['filter.published']) . ')');
      }

      // Filter on the language if set in config
      if (isset($config['filter.language']))
      {
        if (is_string($config['filter.language']))
        {
          $query->where('categories.language = ' . $db->quote($config['filter.language']));
        }
        elseif (is_array($config['filter.language']))
        {
          foreach ($config['filter.language'] as &$language)
          {
            $language = $db->quote($language);
          }
          $query->where('categories.language IN (' . implode(',', $config['filter.language']) . ')');
        }
      }

      // Filter on the access if set in config
      if (isset($config['filter.access']))
      {
        if (is_string($config['filter.access']))
        {
          $query->where('categories.access = ' . $db->quote($config['filter.access']));
        }
        elseif (is_array($config['filter.access']))
        {
          foreach ($config['filter.access'] as &$access)
          {
            $access = $db->quote($access);
          }
          $query->where('categories.access IN (' . implode(',', $config['filter.access']) . ')');
        }
      }

      $query->order('categories.lft');

      $db->setQuery($query);

      // Assemble the list options.
      static::$categories[$hash] = $db->loadObjectList();
    }

    return static::$categories[$hash];
  }


  /**
   * Returns a category's primary key (id) field value by 'title' field value
   *
   * @param   string   $categoryTitle   The category title indented with hyphens if it is lower level than root categories
   *
   * @return  int   The primary key (id) of the category
   */
  public static function getCategoryIdByTitle($categoryTitle)
  {
    // array, each element is an object: $category->id, $category->title, $category->language, $category->level
    $categories = self::getCategories();

    $id = null;

    foreach($categories as $category)
    {
      if ($categoryTitle == $category->title)
      {
        $id = $category->id;
        break;
      }
    }

    return $id;
  }


  /**
   * Returns a category's 'title' field value by primary key (id)
   *
   * @param   int   $categoryId   The primary key (id) of the category
   *
   * @return  string   The category title indented with hyphens if it is lower level than root categories
   */
  public static function getCategoryTitleById($categoryId)
  {
    if (empty(self::$categoryMapIdToName))
    {
      // array, each element is an object: $category->id, $category->title, $category->language, $category->level
      $categories = self::getCategories();

      foreach ($categories as $category)
      {
        self::$categoryMapIdToName[$category->id] = $category->title;
      }
    }

    return self::$categoryMapIdToName[$categoryId];
  }


  /**
   * Returns a category title, formatted with dashes to show level in category hierarchy
   *
   * @param   Object   $category  A POPO object with the properties 'id', 'title', 'level', and 'language' for the category
   *
   * @return  string              The category title indented with hyphens if it is lower level than root categories
   */
  public static function getIndentedCategoryTitle($category)
  {
    $repeat = ($category->level - 1 >= 0) ? $category->level - 1 : 0;

    $title = str_repeat('- ', $repeat) . ucfirst($category->title);

    if ($category->language !== '*')
    {
      $title .= ' (' . $category->language . ')';
    }

    return $title;
  }


  /**
   * Returns a category title, formatted with dashes to show level in category hierarchy
   *
   * @param   string   $modelName   The model name to get a root category ID for
   *
   * @return  string   The category title indented with hyphens if it is lower level than root categories
   */
  public static function getItemRootCategoryId($model)
  {
    $modelName = trim( implode(" ", preg_split( '/(?=[A-Z])/', $model->getName() )));

    $categoryId = self::getCategoryIdByTitle($modelName);

    if ( !$categoryId )
    {
      $categoryId = self::getCategoryIdByTitle('Uncategorised');
    }

    if ( !$categoryId )
    {
      throw new NoDefaultCategory( Text::sprintf('COM_CAJOBBOARD_EXCEPTION_NO_DEFAULT_CATEGORY_MSSG', $modelName) );
    }

    return $categoryId;
  }


  /**
   * Normalize a plural, camel-cased view name to a space-separated title case category title
   *
   * @param   string   $viewName   The CamelCased pluralized name of the view, e.g. 'Answers'
   *
   * @return  string   The title case category title
   */
  public static function getNormalizedCategoryName($viewName)
  {
    preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $viewName, $matches);

    $ret = $matches[0];

    // Handle acronyms, with multiple sequential upper case characters
    foreach ($ret as &$match) {
      $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
    }

    return ucwords( implode(' ', $ret) );
  }


  /**
   * Sets the 'selected' flag to the default category for a view job board category
   * if this is a new item, or returns the category id of the selected category
   *
   * @param   Object   $categories  An array of category POPO objects with the properties 'id', 'title', 'level', and 'language' for each category
   * @param   string   $cat_id      The category id of this item
   * @param   string   $viewName    The plural name of the view, for setting a category on new records e.g. 'Answers'
   *
   * @return  int      The category id that should have the 'selected' flag added to its HTML: <option value="" selected>
   */
  public static function selectedHelper(&$categories, $cat_id, $viewName = 'uncategorised')
  {
    $normalizedCategoryName = self::getNormalizedCategoryName($viewName);

    foreach ($categories as $category)
    {
      if ($category->id == $cat_id)
      {
        return $category->id;
      }

      if ( strtolower($category->title) == strtolower($normalizedCategoryName) )
      {
        $uncategorisedId = $category->id;
      }
    }

    if(!$uncategorisedId)
    {
      throw new Exception("The default Calligraphic Job Board category \(\"$view\"\) is missing from Joomla's #__categories table. It should have been added on installation of this component.");
    }

    return $uncategorisedId;
  }
}

