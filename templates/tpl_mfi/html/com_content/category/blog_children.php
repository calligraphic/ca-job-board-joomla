<?php
/**
 * @package     Calligraphic Job Board
 *
 * @version     0.1 May 1, 2018
 * @author      Calligraphic, LLC http://www.calligraphic.design
 * @copyright   Copyright (C) 2018 Calligraphic, LLC
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

  // no direct access
  defined('_JEXEC') or die;

  JHtml::_('bootstrap.tooltip');

  $lang	= JFactory::getLanguage();
  $class = ' class="first"';
?>

<?php if (count($this->children[$this->category->id]) > 0 && $this->maxLevel != 0) : ?>

	<?php foreach ($this->children[$this->category->id] as $id => $child) : ?>
		<?php
		if ($this->params->get('show_empty_categories') || $child->numitems || count($child->getChildren())) :
			if (!isset($this->children[$this->category->id][$id + 1])) :
				$class = ' class="last"';
			endif;
		?>

		<div<?php echo $class; ?>>
			<?php $class = ''; ?>
			<?php if ($lang->isRTL()) : ?>
			<h3 class="page-header item-title">
				<?php if ( $this->params->get('show_cat_num_articles', 1)) : ?>
					<span class="badge badge-info tip hasTooltip" title="<?php echo JHtml::tooltipText('COM_CONTENT_NUM_ITEMS'); ?>">
						<?php echo $child->getNumItems(true); ?>
					</span>
				<?php endif; ?>
				<a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($child->id)); ?>">
				<?php echo $this->escape($child->title); ?></a>

				<?php if (count($child->getChildren()) > 0 && $this->maxLevel > 1) : ?>
					<a href="#category-<?php echo $child->id;?>" data-toggle="collapse" data-toggle="button" class="btn btn-default btn-mini pull-right"><span class="fa fa-plus-circle"></span></a>
				<?php endif;?>
			</h3>
			<?php else : ?>
			<h3 class="page-header item-title"><a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($child->id));?>">
				<?php echo $this->escape($child->title); ?></a>
				<?php if ( $this->params->get('show_cat_num_articles', 1)) : ?>
					<span class="badge badge-info tip hasTooltip" title="<?php echo JHtml::tooltipText('COM_CONTENT_NUM_ITEMS'); ?>">
						<?php echo $child->getNumItems(true); ?>
					</span>
				<?php endif; ?>

				<?php if (count($child->getChildren()) > 0 && $this->maxLevel > 1) : ?>
					<a href="#category-<?php echo $child->id;?>" data-toggle="collapse" data-toggle="button" class="btn btn-default btn-mini pull-right"><span class="fa fa-plus-circle"></span></a>
				<?php endif;?>
			<?php endif;?>
			</h3>

			<?php if ($this->params->get('show_subcat_desc') == 1) : ?>
			<?php if ($child->description) : ?>
				<div class="category-desc">
					<?php echo JHtml::_('content.prepare', $child->description, '', 'com_content.category'); ?>
				</div>
			<?php endif; ?>
			<?php endif; ?>

			<?php if (count($child->getChildren()) > 0 && $this->maxLevel > 1) : ?>
			<div class="collapse fade" id="category-<?php echo $child->id; ?>">
				<?php
				$this->children[$child->id] = $child->getChildren();
				$this->category = $child;
				$this->maxLevel--;
				echo $this->loadTemplate('children');
				$this->category = $child->getParent();
				$this->maxLevel++;
				?>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	<?php endforeach; ?>

<?php endif;
