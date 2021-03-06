<?php
/**
 * Multi Family Insiders Bootstrap v3 Template with Schema.org markup
 *
 * com_tags tag/default_items.php template partial override
 *
 * @package     Calligraphic Job Board
 *
 * @version     0.1 May 1, 2018
 * @author      Calligraphic, LLC http://www.calligraphic.design
 * @copyright   Copyright (C) 2018 Calligraphic, LLC
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

  use \Joomla\CMS\Factory;
  use \Joomla\CMS\HTML\HTMLHelper;
  use \Joomla\CMS\Language\Text;
  use \Joomla\CMS\Router\Route;
  use \Joomla\CMS\Uri\Uri;

  // no direct access
  defined('_JEXEC') or die;

  HTMLHelper::addIncludePath(JPATH_COMPONENT.'/helpers');

  HTMLHelper::_('behavior.framework');
  HTMLHelper::_('formbehavior.chosen', 'select');

  // Get the user object.
  $user = Factory::getUser();

  // Check if user is allowed to add/edit based on tags permissions.
  // Do we really have to make it so people can see unpublished tags???
  $canEdit = $user->authorise('core.edit', 'com_tags');
  $canCreate = $user->authorise('core.create', 'com_tags');
  $canEditState = $user->authorise('core.edit.state', 'com_tags');
  $items = $this->items;
  $n = count($this->items);
?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">

	<?php if ($this->params->get('show_headings') || $this->params->get('filter_field') || $this->params->get('show_pagination_limit')) : ?>

    <fieldset class="filters btn-toolbar">

      <?php if ($this->params->get('filter_field')) :?>

        <div class="btn-group">

          <label class="filter-search-lbl element-invisible" for="filter-search">
            <?php echo Text::_('COM_TAGS_TITLE_FILTER_LABEL').'&#160;'; ?>
          </label>

          <input
            type="text"
            name="filter-search"
            id="filter-search"
            value="<?php echo $this->escape($this->state->get('list.filter')); ?>"
            class="inputbox form-control"
            onchange="document.adminForm.submit();"
            title="<?php echo Text::_('COM_TAGS_FILTER_SEARCH_DESC'); ?>"
            placeholder="<?php echo Text::_('COM_TAGS_TITLE_FILTER_LABEL'); ?>"
          >

        </div>

      <?php endif; ?>

      <?php if ($this->params->get('show_pagination_limit')) : ?>

        <div class="btn-group pull-right">

          <label for="limit" class="element-invisible">
            <?php echo Text::_('JGLOBAL_DISPLAY_NUM'); ?>
          </label>

          <?php echo $this->pagination->getLimitBox(); ?>

        </div>

      <?php endif; ?>

      <input type="hidden" name="filter_order" value="" >
      <input type="hidden" name="filter_order_Dir" value="" >
      <input type="hidden" name="limitstart" value="" >
      <input type="hidden" name="task" value="" >

      <div class="clearfix"></div>

    </fieldset>

	<?php endif; ?>

	<?php if ($this->items == false || $n == 0) : ?>
		<p><?php echo Text::_('COM_TAGS_NO_ITEMS'); ?></p>
	<?php else : ?>

	<ul class="category list-striped">

		<?php foreach ($items as $i => $item) : ?>

			<?php if ($item->core_state == 0) : ?>
				<li class="system-unpublished cat-list-row<?php echo $i % 2; ?>">
			<?php else: ?>
				<li class="cat-list-row<?php echo $i % 2; ?> clearfix" >

          <h3>
            <a href="<?php echo Route::_(TagsHelperRoute::getItemRoute($item->content_item_id, $item->core_alias, $item->core_catid, $item->core_language, $item->type_alias, $item->router)); ?>">
              <?php echo $this->escape($item->core_title); ?>
            </a>
          </h3>
        <?php endif; ?>

        <?php echo $item->event->afterDisplayTitle; ?>
        <?php $images  = json_decode($item->core_images);?>

        <?php if ($this->params->get('tag_list_show_item_image', 1) == 1 && !empty($images->image_intro)) :?>
          <img src="<?php echo htmlspecialchars($images->image_intro);?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>">
        <?php endif; ?>

        <?php if ($this->params->get('tag_list_show_item_description', 1)) : ?>
          <?php echo $item->event->beforeDisplayContent; ?>
          <span class="tag-body">
            <?php echo HTMLHelper::_('string.truncate', $item->core_body, $this->params->get('tag_list_item_maximum_characters')); ?>
          </span>
          <?php echo $item->event->afterDisplayContent; ?>
        <?php endif; ?>

			</li>

		<?php endforeach; ?>

	</ul>

	<?php endif; ?>

</form>
