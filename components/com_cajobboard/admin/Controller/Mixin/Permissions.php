<?php
/**
 * Site Permissions Controller Mixin
 *
 * @package   Calligraphic Job Board
 * @version   0.1 May 1, 2018
 * @author    Calligraphic, LLC http://www.calligraphic.design
 * @copyright Copyright (C) 2018 Calligraphic, LLC, (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * $taskPrivileges is an associative array for required ACL privileges per task.
 * Callbacks should return a taskArea. Default task privileges:
 *
 *    $taskPrivileges = array(
 *     	// 'taskArea' => 'permission'
 *     	'*editown' => 'core.edit.own',
 *     	'add' => 'core.create',
 *     	'apply' => '&getACLForApplySave',
 *     	'archive' => 'core.edit.state',
 *     	'cancel' => 'core.edit.state',
 *     	'copy' => '@add',
 *     	'edit' => 'core.edit',
 *     	'loadhistory' => '@edit',
 *     	'orderup' => 'core.edit.state',
 *     	'orderdown' => 'core.edit.state',
 *     	'publish' => 'core.edit.state',
 *     	'remove' => 'core.delete',
 *     	'forceRemove' => 'core.delete',
 *     	'save' => '&getACLForApplySave',
 *     	'savenew' => 'core.create',
 *     	'saveorder' => 'core.edit.state',
 *     	'trash' => 'core.edit.state',
 *     	'unpublish' => 'core.edit.state',
 *   );
 *
 * '@task' means 'apply the same privileges as "task"'. Creating a circular reference
 * with @task notation will always return true when that task is requested:
 *
 *   'mytask' => array('@mytask')
 *
 * Controller constructor $config['taskPrivileges'] are merged with the above
 */

namespace Calligraphic\Cajobboard\Admin\Controller\Mixin;

use \FOF30\Container\Container;
use \FOF30\Model\DataModel;
use \Joomla\CMS\Language\Text;
use \Calligraphic\Cajobboard\Admin\Controller\Exception\TaskNotAllowed;

// no direct access
defined('_JEXEC') or die;

trait Permissions
{
  // @TODO: Submit PR to fix access checking in FOF30 DataController triggerEvent() method.
  //        checkACL split up here, should be submitted to include in FOF DataController and removed here

	/**
	 * Checks if the current user has enough privileges for the requested ACL area. This overridden method supports
	 * asset tracking as well.
	 *
	 * @param   string  $area  The ACL area, e.g. core.manage
	 *
	 * @return  boolean  True if the user has the ACL privilege specified
	 */
  protected function checkACL($area)
  {
    // Resolves @task and &callback notations for ACL privileges set on the
    // $taskPrivileges array to a normalized privilege name e.g. core.edit
    $area = $this->getACLRuleFor($area);

    // $area is true here if (1) no callback method found with &callback notation, (2) the referenced task has
    // no ACL map in $taskPrivileges (like @Execute), or (3) a circular reference in $taskPrivileges array.
    // The last is the default way to always return true, e.g. 'mytask' => array('@mytask')
    if (is_bool($area))
    {
      return $area;
    }

    // Models which use item-level asset tracking don't work with read tasks
    if ( 'read' == $this->getTask() )
    {
      return true;
    }

		// Check if we're dealing with ids
    $ids = $this->getRequestIds();

		// No item-level permissions (IDs) tracked, return result of checking general rules and component authorisation
		if (empty($ids))
		{
			return $this->filterAndCheckAuth($area);
    }

    $assetNamePrefix = $this->container->componentName . '.' . strtolower( $this->container->inflector->singularize($this->view) );

		foreach ($ids as $id)
		{
      $assetName = $assetNamePrefix . '.' . $id;

      // Check the area against the item-level permission
			if ($this->container->platform->authorise($area, $assetName) )
			{
				return true;
      }

      // Allow any action if user is the owner of the item and has edit.own permissions
      if ( $this->hasEditOwnPermission($area, $assetName) && $this->isOwner($id) )
      {
        return true;
      }
		}

		// Not authorised if no result found
		return false;
  }


  /*
   * Check if item allows 'editown'
   *
   * @return  bool   Returns true if the user is allowed this action
   */
  protected function hasEditOwnPermission($area, $assetName)
  {
    if (( $area != 'core.edit.state' ) && ( $this->container->platform->authorise( $this->getACLRuleFor('@*editown'), $assetName )))
    {
      return true;
    }

    return false;
  }


  /*
   * Check if current user is the owner of the item for handling 'editown' permission
   *
   * @return  bool   Returns true if the user is allowed this action
   */
  protected function isOwner($id)
  {
    /** @var DataModel $model */
    $model = $this->getModel();

    $model->load($id);

    if (!$model->hasField('created_by'))
    {
      return false;
    }

    $owner_id = (int) $model->getFieldValue('created_by', null);

    // test owner against current user
    if ($owner_id == $this->container->platform->getUser()->id)
    {
      return true;
    }

    return false;
  }


  /*
   * Check ACL for FOF-specific area values, and check Joomla! core ACL otherwise
   *
   * @return  bool   Returns true if the user is allowed this action
   */
  protected function filterAndCheckAuth($area)
  {
    // action forbidden if area is falsy or HTTP Forbidden response code
    if (in_array(strtolower($area), array('false','0','no','403')))
    {
      return false;
    }

    // action allowed if area is truthy
    if (in_array(strtolower($area), array('true','1','yes')))
    {
      return true;
    }

    // action allowed if area is 'guest', and the current user is a guest user
    if (in_array(strtolower($area), array('guest')))
    {
      return $this->container->platform->getUser()->guest;
    }

    // action allowed if area is 'user', and the current user is a registered (logged-in) user
    if (in_array(strtolower($area), array('user')))
    {
      return !$this->container->platform->getUser()->guest;
    }

    // action allowed if no area given
    if (empty($area))
    {
      return true;
    }

    // check Joomla! care ACL if all FOF-specific areas are unmatched
    return $this->container->platform->authorise($area, $this->container->componentName);
  }


  /*
   * Get an array of record IDs from the request
   *
   * @return  array|null   Returns array of record IDs, or null if there is none
   */
  protected function getRequestIds()
  {
		/** @var DataModel $model */
    $model = $this->getModel();

		if ( is_object($model) && ($model instanceof DataModel) && $model->isAssetsTracked() )
		{
			return $this->getIDsFromRequest($model, false);
    }
  }
}
