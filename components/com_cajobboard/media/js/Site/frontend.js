/**
 * Frontend Javascript
 *
 * @package   Calligraphic Job Board
 * @version   May 1, 2018
 * @author    Calligraphic, LLC http://www.calligraphic.design
 * @copyright Copyright (C) 2018 Calligraphic, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

/* eslint-disable no-undef */


/**
 * Register modules in this file with global onload handler
 */
jQuery(document).ready(function() {
  // starRating.init();
});


/**
 * Trigger a hidden form on clicking buttons for 'delete' tasks to send CSRF token
 */
deleteSubmit = function(id) {
  const form = jQuery('#deleteForm-' + id);
  form.submit();
};


/**
 * Read a hidden form on clicking buttons for 'downvote_count' tasks to send CSRF token
 */
downvoteAction = function(id) {
  const form = jQuery('#downvoteForm-' + id);

  // @TODO:
  // 1. Read the CSRF key from the form's input element
  // 2. Send an XHR request with the CSRF key for a downvote
  // 3. Update the value in the box or show a flash message based on the response
};


/**
 * Read a hidden form on clicking buttons for 'upvote_count' tasks to send CSRF token
 */
upvoteAction = function(id) {
  // @TODO: same as downvoteAction
};


/**
 * Change pagination limit for page (number of items to show from select box)
 */
paginationLimitSubmit = function() {
  let url = window.location.href;

  const limitVal = jQuery('#pagination-limit').children('option:selected').val();

  if (url.indexOf('?') > -1){
    url += '&limit=' + limitVal;
  } else {
    url += '?limit=' + limitVal;
  }

  window.location.href = url;
};


/**
* Methods to handle setting proper Bootstrap UI validation state, appends
* or removes jQuery DOM element with error message below input box
*/
/* eslint-disable no-unused-vars */
const validationUI = {
/* eslint-enable no-unused-vars */
  /**
  * Change input box to success state UI, and remove any error messages below
  *
  * @param   string|object  element  The reference to the input box to work on, or an HTML string
  *
  * @return  object  Returns the element passed, or a new element if HTML string passed in
  */
  setSuccess: function(element) {
    // create jQuery DOM element if HTML string passed
    if (!(element instanceof jQuery) && typeof element === 'string') {
      element = $(element);

    } else if (!(element instanceof jQuery)) throw new Error('"element" parameter to frontend/validationUI.setSuccess must be a string or jQuery object');

    // remove error border on input box if present
    if (element.hasClass('has-error')) element.removeClass('has-error');

    // set success border on input box
    if (!element.hasClass('has-success')) element.addClass('has-success');

    // remove error message if present
    const nextElement = element.next();
    if (nextElement.hasClass('validation-error')) nextElement.remove();

    return element;
  },

  /*
  * Change input box to failure state UI, and add queued error messages below
  *
  * @param  string|object  element  The reference to the input box to work on, or an HTML string
  * @param  string|object  errorMessage  The error message to display below the input box, or a jQuery DOM object
  *
  * @return  object  Returns the element passed, or a new element if HTML string passed in
  */
  setFailure: function(element, errorMessage) {
    // create jQuery DOM elements for parameters if HTML string passed
    if (!(element instanceof jQuery) && typeof element === 'string') {
      element = $(element);

    } else if (!(element instanceof jQuery)) throw new Error('"element" parameter to frontend/validationUI.setFailure must be a string or jQuery object');

    if (!(errorMessage instanceof jQuery) && typeof errorMessage === 'string') {
      errorMessage = $(errorMessage);
    } else if (!(errorMessage instanceof jQuery)) throw new Error('"errorMessage" parameter to frontend/validationUI.setFailure must be a string or jQuery object');

    // remove success border on input box if present
    if (element.hasClass('has-success')) element.removeClass('has-success');

    // set error border on input box
    if (!element.hasClass('has-error')) element.addClass('has-error');

    // remove any error message that might be present
    const nextElement = element.next();
    if (nextElement.hasClass('validation-error')) nextElement.remove();

    if(errorMessage) element.append(errorMessage);
  }
};


/**
* Methods to handle form input validation
*/
/* eslint-disable no-unused-vars */
const validationHelper = {
/* eslint-enable no-unused-vars */
  /**
  * Validate email for presence of '@' symbol, at least one period, and a domain between 2 and 4 characters
  *
  * @param  string  email  The email address to validate
  *
  * @return  bool|string  False if email is valid, or the error name if validation failed
  */
  validateEmail: function(email) {
    var validEmailRegExp = new RegExp(/^\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i);

    return validEmailRegExp.test(email) ? false : 'invalidEmail';
  },

  /*
  * Validate password based on system settings passed in hidden type input fields for
  * length, and including minimum number of symbols, uppercase characters, and numbers
  *
  * @param  string  password  The password to validate
  * @param  object  params    An object setting validation parameters with this shape:
  *   {
  *     minimumPasswordLength:    ?int,
  *     minimumPasswordUppercase: ?int,
  *     minimumPasswordIntegers:  ?int,
  *     minimumPasswordSymbols:   ?int
  *   }
  *
  * @return  bool|object  False if password is valid, or the error name if validation failed
  */
  validatePassword: function(password, params) {
    // Anonymous function returns true if string matches and is therefore invalid
    const invalidErrors = {
      invalidPasswordLength:    function (password) { password.length  >= params.minimumPasswordLength ? true : false; },
      invalidPasswordUppercase: function (password) { password.match( /[A-Z]/g ).length -1  >= params.minimumPasswordUppercase ? true : false; },
      invalidPasswordIntegers:  function (password) { password.match( /[A-Z]/g ).length -1  >= params.minimumPasswordIntegers ? true : false; },
      invalidPasswordSymbols:   function (password) { password.match( /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/gi ).length -1 >= params.minimumPasswordSymbols ? true : false; }
    };

    for (const error in invalidErrors) {
      // make sure property isn't on prototype chain of error object
      if (!invalidErrors.hasOwnProperty(error)) continue;

      // return error name if validation test fails
      if (invalidErrors[error](password)) return error;
    }

    // password is valid, return false
    return false;
  }
};
