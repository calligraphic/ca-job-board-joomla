<?php
 /**
  * Multi Family Insiders Bootstrap V3 Template Error Page
  *
  * @package   Calligraphic Job Board
  * @version   0.1 May 1, 2018
  * @author    Calligraphic, LLC http://www.calligraphic.design
  * @copyright Copyright (C) 2018 Calligraphic, LLC, (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
  * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
  */

  // no direct access
  defined('_JEXEC') or die;

  /** @var \Joomla\CMS\Document\ErrorDocument   $this */

  include 'Includes/params.php';
  include 'Includes/moduleHelper.php';
?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>">
  <?php include 'Partials/_head.php'; ?>
  <?php include 'Partials/_error.php'; ?>
</html>
