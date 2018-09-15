<?php
/**
 * Site Person Controller (for login)
 *
 * @package   Calligraphic Job Board
 * @version   0.1 May 1, 2018
 * @author    Calligraphic, LLC http://www.calligraphic.design
 * @copyright Copyright (C) 2018 Calligraphic, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

namespace Calligraphic\Cajobboard\Site\Controller;

// Framework classes
use FOF30\Container\Container;
use FOF30\Controller\DataController;
use FOF30\View\Exception\AccessForbidden;
use JLog;

use Calligraphic\Cajobboard\Site\Helper\RegistrationHelper;

// no direct access
defined('_JEXEC') or die;

class Registration extends Controller
{
	/**
	 * Overridden. Limit the tasks we're allowed to execute.
	 *
	 * @param   Container $container
	 * @param   array     $config
	 */
	public function __construct(Container $container, array $config = array())
	{
    parent::__construct($container, $config);

    $this->predefinedTaskList = [
      'default',
      'Login',
      'LoginWithSocialAccount'
    ];
  }

	/**
	 * Runs before executing a task in the controller, overriden to keep from ACL check
   * with no area set. Seems like bug inController triggerEvent() method
	 *
	 * @param   string  $task  The task to execute
	 *
	 * @return  bool
	 */
	public function onBeforeExecute($task)
	{
    // Do any ACL? This runs for *any* task, even public ones
		return true;
  }

	/**
	 * Login user
	 *
	 * @return  bool
	 */
	public function Login()
	{
    JLog::add('Person controller, Login() method called', JLog::DEBUG, 'cajobboard');

    return true;
  }

	/**
	 * Login user with a social media account.
	 *
	 * @return  bool
	 */
	public function LoginWithSocialAccount()
	{
    JLog::add('Person controller, LoginWithSocialAccount() method called', JLog::DEBUG, 'cajobboard');

    return true;
  }
}
