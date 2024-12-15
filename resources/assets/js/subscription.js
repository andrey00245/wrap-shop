$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  const subscription = $('#subscription-form')

  subscription.on('submit', function (e) {
    e.preventDefault();
    const action = $(this).attr('action')
    const method = $(this).attr('method')
    const data = {
      email: subscription.find('input[name="email"]').val()
    }

    const button = subscription.find('button.save-form')
    const tempVar = button.text();
    const parentBlock = $('<div class="spinner"></div>');
    const childBlock1 = $('<div class="bounce1"></div>');
    const childBlock2 = $('<div class="bounce2"></div>');
    const childBlock3 = $('<div class="bounce3"></div>');

    parentBlock.append(childBlock1,childBlock2,childBlock3)
    button.text('');
    button.append(parentBlock)
    button.prop('disabled', true);

    const formErrorContainer =  $('.form-error-container');
    const errorBlock = formErrorContainer.find('.error-text')
    const subscribeSuccess = $('.foot-form-thanks')

    $.ajax({
      url: action,
      method: method,
      data: data,
      success: function (response) {
        if (response['success']){
          formErrorContainer.hide();
          subscribeSuccess.removeClass('hide');
        }
        else {
          errorBlock.text(response['message']);
        }
      },
      error: function (xhr) {
        errorBlock.text(xhr['responseJSON']['message']);
        button.text(tempVar);
        button.prop('disabled', false);
      },
    });
  });
});
