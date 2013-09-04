/**
 * ownCloud - Invitations App
 *
 * @author Lennart Rosam
 * @copyright 2013 MSP Medien Systempartner GmbH & Co. KG <lennart.rosam@medien-systempartner.de>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

var OC_Invite = {
   /**
   * Sends the given user object to the server
   * for serverside validation and updates the
   * UI accordingly
   *
   * @param user: The user to validate
   */
   validateServerside: function(user, validate) {
	$.ajax({
	  url: 'users/test',
	  data: user,
	  type: 'post',
	  data: JSON.stringify(user),
	  contentType: "application/json",
	  headers: {
		Accept: "application/json"
	  },


	  success: function(validationResult){
		var usernameValidation = validationResult.usernameValidation;
		var emailValidation = validationResult.emailValidation;
		if(validate == 'username') {
		  if(usernameValidation.validUsername) {
			$('em#user-invalid').hide();
			$('em#user-valid').show();
			$('input#username').removeClass('error');
		  } else {
			$('em#user-invalid').show().text(t('invite', usernameValidation.msg.text));
			$('em#user-valid').hide();
			$('input#username').addClass('error');
		  }
		}

		if(validate == 'email') {
		  if(emailValidation.validEmail) {
			$('em#email-invalid').hide();
			$('em#email-valid').show();
			$('input#email').removeClass('error');
		  } else {
			$('em#email-invalid').show().text(t('invite', emailValidation.msg.text));
			$('em#email-valid').hide();
			$('input#email').addClass('error');
		  }
		}
	  },

	  error: function(error) {
		alert('Check your connection, reload the page and try again!');
		$('button#send-invite').attr('disabled', 'disabled');
	  }
	});
  },

  /**
   * Creates a user from the input form
   */
   createUserFromForm: function() {
	return {
	  user: {
		username: $('input#username').val(),
		email: $('input#email').val(),
		groups: $('select.chosen-select').val()
	  }
	}
  },

  /**
   * Handles the focusout event on the input elements
   */
   inputFocusOutHandler: function(evt){
	var search = this.value;
	var validate = $(this).attr('id');
	OC_Invite.validateServerside(OC_Invite.createUserFromForm(), validate);
  },

  selectChanged: function(evt){
	var groups = $(this).val();
	if(groups != null || $(this).data('admin') === 1) {
	  $('.chzn-container').removeClass('error');
	} else {
	  $('.chzn-container').addClass('error');
	}
  },

  /**
   * Sends the invite
   */

   sendInvite: function(evt){
	evt.preventDefault();

	// Prevent double clicking
	if($(this).is(':disabled')) {
	  return;
	}

	$(this).attr('disabled', 'disabled');
	$(this).text(t('invite', 'Sending invite...'));

	var user = OC_Invite.createUserFromForm();
	$.ajax({
	  url: 'users',
	  data: user,
	  type: 'post',
	  data: JSON.stringify(user),
	  contentType: "application/json",
	  headers: {
		Accept: "application/json"
	  },
	  success: function(validationResult){
		$('div#invite-form-content').fadeOut('fast', function() {
		  $('div#invite-success').fadeIn('fast');
		});
	  },
	  error: function(error) {
		var httpStatusCode = error.status;
		error = $.parseJSON(error.responseText);

		if(httpStatusCode === 400) {
		  if(!error.validUser) {
			$('input#username').addClass('error');
		  }

		  if(!error.validEmail){
			$('input#email').addClass('error');
		  }

		  if(!error.validGroups) {
			$('div.chzn-container').addClass('error');
		  }
		  $('button#send-invite').text(t('invite', 'Send invite'));
		  $('button#send-invite').removeAttr('disabled');
		}

		// Something went seriously wrong - do not continue!
		if(httpStatusCode === 500) {
		  if(error && error.msg) {
			alert(error.msg);
		  } else {
			alert(t(
				'invite',
				'Something went very wrong. Please contact your administrator!'
			));
		  }
		  $('button#send-invite').text(error.msg);
		}
	  }
	})
   },

   /**
	* Invites more people to ownCloud
	*/
   inviteMore: function(evt) {
	  evt.preventDefault();
	  var url = OC.Router.generate('invite_index');
	  window.location = url;
   }

}



// Listen for form updates
$(document).ready(function(){
	$('input#username').on('focusout', OC_Invite.inputFocusOutHandler);
	$('input#email').on('focusout', OC_Invite.inputFocusOutHandler);
	$('select.chosen-select').chosen();
	$('button#send-invite').click(OC_Invite.sendInvite);
	$('button#invite-more').click(OC_Invite.inviteMore);
	$('select.chosen-select').change(OC_Invite.selectChanged);
});