<?php
 /**
  * Places List View Template
  *
  * @package   Calligraphic Job Board
  * @version   0.1 May 1, 2018
  * @author    Calligraphic, LLC http://www.calligraphic.design
  * @copyright Copyright (C) 2018 Calligraphic, LLC
  * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
  *
  */

  // no direct access
  defined('_JEXEC') or die;

  // collection of review postings model objects for this list view
  $items = $this->getItems();
?>

@section('header')
  <h4>Places List View</h4>
@show

@section('sidebar')
  <p></p>
@show

@section('item')
  <div class="container-fluid places-list">
    @each('site:com_cajobboard/Places/default_item', $items, 'item', 'text|COM_CAJOBBOARD_PLACES_NO_PLACES_FOUND')
  </div>
@show

@section('footer')
  <p></p>
@show
