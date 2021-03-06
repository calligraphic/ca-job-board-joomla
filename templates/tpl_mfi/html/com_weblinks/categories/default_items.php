<?php
/**
 * Multi Family Insiders Bootstrap v3 Template with Schema.org markup
 *
 * com_weblinks categories/default_itmes.php template partial override
 *
 * @package     Calligraphic Job Board
 *
 * @version     0.1 May 1, 2018
 * @author      Calligraphic, LLC http://www.calligraphic.design
 * @copyright   Copyright (C) 2018 Calligraphic, LLC
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

  use \Joomla\CMS\HTML\HTMLHelper;
  use \Joomla\CMS\Router\Route;

  // no direct access
  defined('_JEXEC') or die;

  HTMLHelper::_('bootstrap.tooltip');

  $class = ' class="first"';
?>

<?php if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0) : ?>

	<?php foreach($this->items[$this->parent->id] as $id => $item) : ?>

		<?php
      if ($this->params->get('show_empty_categories_cat') || $item->numitems || count($item->getChildren())) :
        if (!isset($this->items[$this->parent->id][$id + 1]))
        {
          $class = ' class="last"';
        }
		?>

      <div <?php echo $class; ?> >
      <?php $class = ''; ?>

        <h3 class="page-header item-title">

          <a href="<?php echo Route::_(WeblinksHelperRoute::getCategoryRoute($item->id));?>">
            <?php echo $this->escape($item->title); ?>
          </a>

          <?php if ($this->params->get('show_cat_num_articles_cat') == 1) :?>
            <span class="badge badge-info tip hasTooltip" title="<?php echo HTMLHelper::tooltipText('COM_WEBLINKS_NUM_ITEMS'); ?>">
              <?php echo $item->numitems; ?>
            </span>
          <?php endif; ?>

          <?php if (count($item->getChildren()) > 0 && $this->maxLevelcat > 1) : ?>
            <a href="#category-<?php echo $item->id;?>" data-toggle="collapse" data-toggle="button" class="btn btn-default btn-sm pull-right">
              <span class="fa fa-plus"></span>
            </a>
          <?php endif;?>

        </h3>

        <?php if ($this->params->get('show_subcat_desc_cat') == 1) :?>

          <?php if ($item->description) : ?>

            <div class="category-desc">
              <?php echo HTMLHelper::_('content.prepare', $item->description, '', 'com_weblinks.categories'); ?>
            </div>

          <?php endif; ?>

        <?php endif; ?>

        <?php if (count($item->getChildren()) > 0 && $this->maxLevelcat > 1) :?>

          <div class="collapse fade" id="category-<?php echo $item->id;?>">
            <?php
            $this->items[$item->id] = $item->getChildren();
            $this->parent = $item;
            $this->maxLevelcat--;
            echo $this->loadTemplate('items');
            $this->parent = $item->getParent();
            $this->maxLevelcat++;
            ?>
          </div>

        <?php endif; ?>

      </div>

		<?php endif; ?>

	<?php endforeach; ?>

<?php endif; ?>
