<?php
 /**
  * Answers Site List View Item Template
  *
  * @package   Calligraphic Job Board
  * @version   0.1 May 1, 2018
  * @author    Calligraphic, LLC http://www.calligraphic.design
  * @copyright Copyright (C) 2018 Calligraphic, LLC
  * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
  *
  */

  use \Calligraphic\Cajobboard\Site\Helper\User;
  use \Calligraphic\Cajobboard\Site\Helper\Format;

  // no direct access
  defined('_JEXEC') or die;

  /** @var \Calligraphic\Cajobboard\Site\Model\Answers $item */

  // model data fields
  $answerID       = $item->getFieldValue('answer_id');
  $createdBy      = $item->getFieldValue('created_by');      // userid of the creator of this answer.
  $createdOn      = $item->getFieldValue('created_on');
  // $description    = $item->getFieldValue('description');     // Text of the answer.
  $downvoteCount  = $item->getFieldValue('downvote_count');  // Downvote count for this item.
  $featured       = $item->getFieldValue('featured');        // bool whether this answer is featured or not
  $hits           = $item->getFieldValue('hits');            // Number of hits this answer has received
  // $isPartOf       = $item->getFieldValue('isPartOf');        // This property points to a QAPage entity associated with this answer. FK to #__cajobboard_qapage(qapage_id)
  // $modifiedBy     = $item->getFieldValue('modified_by');     // userid of person that modified this answer.
  // $modifiedOn     = $item->getFieldValue('modified_on');
  $title          = $item->getFieldValue('name');            // A title to use for the answer.
  // $parentItem     = $item->getFieldValue('parentItem');      // The question this answer is intended for. FK to #__cajobboard_questionss(question_id)
  // $Publisher      = $item->getFieldValue('Publisher');       // The company that wrote this answer. FK to #__organizations(organization)id).
  // $slug           = $item->getFieldValue('slug');            // Alias for SEF URL
  $text           = $item->getFieldValue('text');            // The actual text of the answer itself.
  $upvoteCount    = $item->getFieldValue('upvote_count');    // Upvote count for this item.

  // authorisation
  $user = $this->container->platform->getUser();

  $userId = $user->id;
  $authorId = $item->Author->getId();

  $canUserEdit = User::canEdit($user, $item);

  $authorAvatarUri = User::getAvatar($authorId);
  $authorProfileLink = User::getLinkToUserProfile($authorId);
  $lastSeen = User::lastSeen($item->Author->lastvisitDate);
  $name = $item->Author->getFieldValue('name');
  $postedOn = Format::getCreatedOnText($createdOn);
?>

{{--
  #1 - Answer Title
--}}
@section('answer_title')
  {{-- link to individual answer --}}
  <a class="answer-title" href="@route('index.php?option=com_cajobboard&view=answer&task=read&id='. (int) $answerID)">
    {{{ $title }}}
  </a>
@overwrite

{{--
  #2 - Answer Text
--}}
@section('answer_text')
  <p class="answer-text">
    <b>{{{ $text }}}</b>
  </p>
@overwrite


{{--
  #3 - Answer Author's name
--}}
@section('authors_name')
  <a href="{{ $authorProfileLink }}" class="author-name">
    {{{ $name }}}
  </a>
@overwrite

{{--
  #4 - Answer Author's avatar
--}}
@section('authors_avatar')
  <a href="{{ $authorProfileLink }}" class="author-avatar">
    <img src="{{{ $authorAvatarUri }}}" alt="Avatar" class="img-thumbnail" height="24" width="24">
  </a>
@overwrite

{{--
  #5 - Answer Author's last seen
--}}
@section('author_last_seen')
  <span class="author-last-seen">
    {{ $lastSeen }}
  </span>
@overwrite

{{--
  #6 - Answer Posted Date
--}}
@section('answer_posted_date')
  <span class="answer-posted-date">
    @lang('COM_CAJOBBOARD_ANSWERS_POSTED_ON_BUTTON_LABEL')
    {{ $postedOn }}
  </span>
@overwrite

{{--
  #7 - Answer Upvotes
--}}
@section('answer_upvotes')
  <button class="btn btn-primary btn-xs btn-answer answer-upvotes pull-right" type="button">
    @lang('COM_CAJOBBOARD_ANSWERS_UPVOTES_BUTTON_LABEL')
    <span class="badge">
      {{{ $upvoteCount }}}
    </span>
  </button>
@overwrite

{{--
  #8 - Answer Downvotes
--}}
@section('answer_downvotes')
  <button class="btn btn-primary btn-xs btn-answer answer-downvotes pull-right" type="button">
    @lang('COM_CAJOBBOARD_ANSWERS_DOWNVOTES_BUTTON_LABEL')
    <span class="badge">
      {{{ $downvoteCount }}}
    </span>
  </button>
@overwrite

{{--
  #9 - "Report answer" Button
--}}
@section('report_answer')
  <button type="button" class="btn btn-primary btn-xs btn-answer report-answer pull-right" data-toggle="modal" data-target="#report-answer">
    @lang('COM_CAJOBBOARD_REPORT_ANSWERS_BUTTON_LABEL')
  </button>
@overwrite


{{--
  #10 - Edit Button for logged-in users that have permission to edit the item
--}}
@section('edit_answer')
  @if ($canUserEdit)
    <a class="edit-answer-link" href="@route('index.php?option=com_cajobboard&view=answer&task=edit&id='. (int) $answerID)">
      <button type="button" class="btn btn-warning btn-xs btn-answer edit-answer-button pull-right">
        @lang('COM_CAJOBBOARD_EDIT_ANSWERS_BUTTON_LABEL')
      </button>
    </a>
  @endif
@overwrite


{{--
  Responsive container for desktop and mobile
--}}
<div class="row answer-list-item media <?php echo ($featured) ? 'featured' : ''; ?>">
  <h4>@yield('answer_title')</h4>
  <p>@yield('answer_text')</p>

  <div>
    @yield('answer_posted_date')
  </div>

  <div class="clearfix"></div>

  <div>
    <a href="#">
      @yield('authors_avatar')
    </a>
    <a href="#">
      @yield('authors_name')
    </a>
    @yield('author_last_seen')
  </div>

  <div class="clearfix"></div>

  <div>
    @yield('edit_answer')
    @yield('report_answer')
    @yield('answer_downvotes')
    @yield('answer_upvotes')
  </div>
</div>{{-- End responsive container --}}

<div class="clearfix"></div>
