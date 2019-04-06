<?php
/**
 * Predefined Tasks List Controller Mixin
 *
 * @package   Calligraphic Job Board
 * @version   0.1 May 1, 2018
 * @author    Calligraphic, LLC http://www.calligraphic.design
 * @copyright Copyright (C) 2018 Calligraphic, LLC, (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

namespace Calligraphic\Cajobboard\Admin\Controller\Mixin;

// no direct access
defined('_JEXEC') or die;

/**
 * Force a Controller to allow access to specific tasks only, no matter which tasks are already defined in this
 * Controller.
 */
trait PredefinedTaskList
{
	/**
	 * A list of predefined tasks. Trying to access any other task will result in the first task of this list being
	 * executed instead.
	 *
	 * @var array
	 */
  protected $predefinedTaskList = array();


	/**
	 * Overrides the execute method to implement the predefined task list feature
	 *
	 * @param   string  $task  The task to execute
	 *
	 * @return  mixed  The controller task result
	 */
	public function execute($task)
	{
		if (!in_array($task, $this->predefinedTaskList) && $task != 'default')
		{
      // notify user that the task they've requested isn't valid
      \JFactory::getApplication()->enqueueMessage(\JText::sprintf( 'COM_CAJOBBOARD_TASK_NOT_IN_LIST', $task ), 'error');
      $task = 'browse';
    }

		return parent::execute($task);
  }


	/**
	 * Sets the predefined task list and registers the first task in the list as the Controller's default task
	 *
	 * @param   array  $taskList  The task list to register
	 */
	public function setPredefinedTaskList(array $taskList)
	{
		// First, unregister all known tasks which are not in the taskList
    $allTasks = $this->getTasks();

		foreach ($allTasks as $task)
		{
			if (in_array($task, $taskList))
			{
				continue;
      }

			$this->unregisterTask($task);
    }

		// Set the predefined task list
    $this->predefinedTaskList = $taskList;

		// Set the default task
		$this->registerDefaultTask(reset($this->predefinedTaskList));
	}
}
