<?php
/**
 * Multi Family Insiders Bootstrap v3 Template with Schema.org markup
 *
 * com_users profile/edit.php template override
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

  // no direct access
  defined('_JEXEC') or die;

  HTMLHelper::_('behavior.keepalive');
  HTMLHelper::_('behavior.formvalidation');
  HTMLHelper::_('formbehavior.chosen', 'select');

  //load user_profile plugin language
  $lang = Factory::getLanguage();

  $lang->load('plg_user_profile', JPATH_ADMINISTRATOR);
?>

<div class="profile-edit<?php echo $this->pageclass_sfx?> well-lg bg-white">

  <?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
      <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
    </div>
  <?php endif; ?>

  <script type="text/javascript">
    Joomla.twoFactorMethodChange = function(e)
    {
      var selectedPane = 'com_users_twofactor_' + jQuery('#jform_twofactor_method').val();

      jQuery.each(jQuery('#com_users_twofactor_forms_container>div'), function(i, el) {
        if (el.id != selectedPane)
        {
          jQuery('#' + el.id).hide(0);
        }
        else
        {
          jQuery('#' + el.id).show(0);
        }
      });
    }
  </script>

  <form id="member-profile" action="<?php echo Route::_('index.php?option=com_users&task=profile.save'); ?>" method="post" class="form-validate form-horizontal" role="form" enctype="multipart/form-data">

    <?php foreach ($this->form->getFieldsets() as $group => $fieldset):// Iterate through the form fieldsets and display each one.?>

      <?php $fields = $this->form->getFieldset($group);?>

      <?php if (count($fields)):?>

        <fieldset>

          <?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.?>
            <legend><?php echo Text::_($fieldset->label); ?></legend>
          <?php endif;?>

          <?php foreach ($fields as $field):// Iterate through the fields in the set and display them.?>

            <?php if ($field->hidden):// If the field is hidden, just display the input.?>

              <div class="form-group">
                <?php echo $field->input;?>
              </div>

            <?php else:?>

              <div class="form-group">
                <div class="col-sm-2 control-label">

                  <?php echo $field->label; ?>

                  <?php if (!$field->required && $field->type != 'Spacer') : ?>
                    <span class="optional" title="&lt;strong&gt;"><?php echo Text::_('COM_USERS_OPTIONAL'); ?></span>
                  <?php endif; ?>

                </div>

                <div class="col-sm-10">
                  <?php $required ='' ; if ($field->required){ $required =  'required'; } ?>
                  <?php $field->__set('class', $field->getAttribute('class')." form-control $required"); ?>
                  <?php echo $field->input;?>
                </div>

              </div>
            <?php endif;?>

          <?php endforeach;?>

        </fieldset>

      <?php endif;?>

    <?php endforeach;?>

  <?php if (count($this->twofactormethods) > 1): ?>

    <fieldset>

      <legend><?php echo Text::_('COM_USERS_PROFILE_TWO_FACTOR_AUTH') ?></legend>

      <div class="form-group">
        <div class="control-label">
          <label
            id="jform_twofactor_method-lbl"
            for="jform_twofactor_method"
            class="hasTooltip"
            title="&lt;strong&gt;<?php echo Text::_('COM_USERS_PROFILE_TWOFACTOR_LABEL') ?>&lt;/strong&gt;<br/><?php echo Text::_('COM_USERS_PROFILE_TWOFACTOR_DESC') ?>"
          >
            <?php echo Text::_('COM_USERS_PROFILE_TWOFACTOR_LABEL'); ?>
          </label>
        </div>

        <div class="controls">
          <?php echo HTMLHelper::_('select.genericlist', $this->twofactormethods, 'jform[twofactor][method]', array('onchange' => 'Joomla.twoFactorMethodChange()'), 'value', 'text', $this->otpConfig->method, 'jform_twofactor_method', false) ?>
        </div>

      </div>

      <div id="com_users_twofactor_forms_container">

        <?php foreach($this->twofactorform as $form): ?>

          <?php $style = $form['method'] == $this->otpConfig->method ? 'display: block' : 'display: none'; ?>

          <div id="com_users_twofactor_<?php echo $form['method'] ?>" style="<?php echo $style; ?>">
            <?php echo $form['form'] ?>
          </div>

        <?php endforeach; ?>

      </div>
    </fieldset>

    <fieldset>

      <legend>
        <?php echo Text::_('COM_USERS_PROFILE_OTEPS') ?>
      </legend>

      <div class="alert alert-info">
        <?php echo Text::_('COM_USERS_PROFILE_OTEPS_DESC') ?>
      </div>

      <?php if (empty($this->otpConfig->otep)): ?>

        <div class="alert alert-warning">
          <?php echo Text::_('COM_USERS_PROFILE_OTEPS_WAIT_DESC') ?>
        </div>

      <?php else: ?>

        <?php foreach ($this->otpConfig->otep as $otep): ?>

          <span class="col-sm-3">
            <?php echo substr($otep, 0, 4) ?>-<?php echo substr($otep, 4, 4) ?>-<?php echo substr($otep, 8, 4) ?>-<?php echo substr($otep, 12, 4) ?>
          </span>

        <?php endforeach; ?>

      <div class="clearfix"></div>

      <?php endif; ?>

    </fieldset>

  <?php endif; ?>

		<div class="form-group">

			<div class="col-sm-offset-2 col-sm-10">

        <button type="submit" class="btn btn-primary validate"><span><?php echo Text::_('JSUBMIT'); ?></span></button>

        <a class="btn" href="<?php echo Route::_(''); ?>" title="<?php echo Text::_('JCANCEL'); ?>"><?php echo Text::_('JCANCEL'); ?></a>

        <input type="hidden" name="option" value="com_users" />
        <input type="hidden" name="task" value="profile.save" />

        <?php echo HTMLHelper::_('form.token'); ?>

			</div>

		</div>
	</form>
</div>
