<?php
/**
 * Multi Family Insiders Bootstrap v3 Template with Schema.org markup
 *
 * com_weblinks form/edit.php template override
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
  use \Joomla\CMS\Language\Text;
  use \Joomla\CMS\Router\Route;

  // no direct access
  defined('_JEXEC') or die;

  HTMLHelper::_('behavior.keepalive');
  HTMLHelper::_('behavior.formvalidation');
  HTMLHelper::_('formbehavior.chosen', 'select');
  HTMLHelper::_('behavior.modal', 'a.modal_jform_contenthistory');

  // Create shortcut to parameters.
  $params = $this->state->get('params');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'weblink.cancel' || document.formvalidator.isValid(document.getElementById('adminForm')))
		{
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task);
		}
	}
</script>

<div class="edit<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
    <h1>
      <?php echo $this->escape($this->params->get('page_heading')); ?>
    </h1>
	<?php endif; ?>

	<form action="<?php echo Route::_('index.php?option=com_weblinks&view=form&w_id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-vertical">

		<div class="btn-toolbar">

			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('weblink.save')">
					<span class="fa fa-check"></span> <?php echo Text::_('JSAVE') ?>
				</button>
			</div>

			<div class="btn-group">
				<button type="button" class="btn btn-default" onclick="Joomla.submitbutton('weblink.cancel')">
					<span class="fa fa-close"></span> <?php echo Text::_('JCANCEL') ?>
				</button>
			</div>

			<?php if ($params->get('save_history', 0)) : ?>
				<div class="btn-group">
					<?php echo $this->form->getInput('contenthistory'); ?>
				</div>
			<?php endif; ?>

		</div>

		<hr class="hr-condensed" />

		<?php echo $this->form->renderField('title'); ?>
		<?php echo $this->form->renderField('alias'); ?>
		<?php echo $this->form->renderField('catid'); ?>
		<?php echo $this->form->renderField('url'); ?>
		<?php echo $this->form->renderField('tags'); ?>

		<?php if ($params->get('save_history', 0)) : ?>
			<?php echo $this->form->renderField('version_note'); ?>
		<?php endif; ?>

		<?php if ($this->user->authorise('core.edit.state', 'com_weblinks.weblink')) : ?>
			<?php echo $this->form->renderField('state'); ?>
		<?php endif; ?>

		<?php echo $this->form->renderField('language'); ?>

		<?php echo $this->form->renderField('description'); ?>

		<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />

		<input type="hidden" name="task" value="" />

		<?php echo HTMLHelper::_('form.token'); ?>

	</form>

</div>
