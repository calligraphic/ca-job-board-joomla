<?php
/**
 * Multi Family Insiders Bootstrap v3 Template with Schema.org markup
 *
 * layouts joomla/content/info_block/hits.php template override
 *
 * @package     Calligraphic Job Board
 *
 * @version     0.1 May 1, 2018
 * @author      Calligraphic, LLC http://www.calligraphic.design
 * @copyright   Copyright (C) 2018 Calligraphic, LLC
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

  use \Joomla\CMS\Language\Text;

  // no direct access
  defined('_JEXEC') or die;
?>

<dd class="hits">

    <i class="fa fa-eye"></i>

    <meta itemprop="interactionCount" content="UserPageVisits:<?php echo $displayData['item']->hits; ?>" />

    <?php echo Text::sprintf('COM_CONTENT_ARTICLE_HITS', $displayData['item']->hits); ?>

</dd>
