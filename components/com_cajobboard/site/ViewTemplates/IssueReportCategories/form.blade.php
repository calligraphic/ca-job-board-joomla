<?php
 /**
  * Site Issue Report Categories Edit View Template
  *
  * @package   Calligraphic Job Board
  * @version   September 12, 2019
  * @author    Calligraphic, LLC http://www.calligraphic.design
  * @copyright Copyright (C) 2019 Calligraphic, LLC
  * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
  */

  use \Joomla\CMS\Language\Text;

  // no direct access
  defined('_JEXEC') or die;

  /** @var  FOF30\View\DataView\Html                                   $this */
  /** @var \Calligraphic\Cajobboard\Site\Model\IssueReportCategories   $item */
  $item = $this->getItem();

  // Using an include so that local vars in the included file are in scope here also
  include(JPATH_COMPONENT . '/ViewTemplates/Common/common_local_vars.php');
  include(JPATH_COMPONENT . '/ViewTemplates/IssueReportCategories/local_vars.php');

  // The name of the crud view
  $crud = 'edit';

  $isEditView = ('edit' == $task);
?>

{{--
  Responsive component
--}}
@section('answer-edit-container')
  <form action="{{ $postAction }}" method="post" name="siteForm" id="siteForm" class="cajobboard-form">
    <div class="row media @jhtml('helper.commonwidgets.getAttributeClass', 'container', $prefix, $crud)">

      <header class="form-header well @jhtml('helper.commonwidgets.getAttributeClass', 'header', $prefix, $crud)">
        @jhtml('helper.editwidgets.header', $isEditView, $humanViewNameSingular, $prefix, $crud)
      </header>

      <div class="form-group">
        @jhtml('helper.editwidgets.bespokeCategory', $category, $categoryPlaceholder, $humanViewNameSingular, $prefix, $crud)
      </div>

      <div class="form-group">
        @jhtml('helper.editwidgets.url', $url, $urlPlaceholder, $humanViewNameSingular, $prefix, $crud)
      </div>

      @jhtml('helper.buttonwidgets.submit', $prefix, $crud)

      @if ($isEditView)
        @jhtml('helper.buttonwidgets.delete', $humanViewNameSingular, $canUserEdit, $itemId, $prefix, $crud, false)
      @endif

      @jhtml('helper.buttonwidgets.cancel', $browseViewLink, $prefix, $crud)
    </div>

    {{-- Hidden CSRF form field --}}
    @jhtml('helper.buttonwidgets.hiddenCsrfField')
  </form>

  {{-- Form with CSRF field for delete action --}}
  @if ($isEditView)
    @jhtml('helper.buttonwidgets.deleteActionCsrfField', $deleteAction, $itemId)
  @endif
@show

{{--
  Modal templates used in common for all default_item views, only
  take bandwidth hit of including modal HTML if user is logged in
--}}
@if ( !$isGuestUser )
  @yield('report-item-modal')
@endif
