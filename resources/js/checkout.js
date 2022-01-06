$(document).ready(function(){

    // Set stripe public key
    //Stripe.setPublishableKey(stripe_public_key);
    let stripe = Stripe(stripe_public_key);
    let elements = stripe.elements();

    // add elements to page
    let card_number = elements.create('cardNumber');
    card_number.mount('#card_number'); $('#card_number').addClass('form-control');

    let card_expiry = elements.create('cardExpiry');
    card_expiry.mount('#card_expiry'); $('#card_expiry').addClass('form-control');

    let card_cvc = elements.create('cardCvc');
    card_cvc.mount('#card_cvc'); $('#card_cvc').addClass('form-control');

    card_number.addEventListener('change', function(event) {
        if (event.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: event.error.message
            });
        }
    });

    card_expiry.addEventListener('change', function(event) {
        if (event.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: event.error.message
            });
        }
    });

    card_cvc.addEventListener('change', function(event) {
        if (event.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: event.error.message
            });
        }
    });

    // Visual form validation
    $(document).on('keyup focusout blur change', '#pay-form .required', function(){
        if($('#terms-accept').is(':checked') && $('[name="card-owner"]').val() !== '') {
            $('#pay-form button').removeAttr('disabled');
        } else {
            $('#pay-form button').attr('disabled','disabled');
        }
    });

    //console.log('City = '+city +' postoced = '+ postcode);

    // Handle checkout button
    $('#pay-form button').click(function(e) {
        // Check progress
        if( window.checkoutInProgress ){
            return false;
        }

        // Set helper variable
        window.checkoutInProgress = true;

        // Change button
        $('#pay-form button').html('Please wait...');
        $('#pay-form button').attr('disabled','disabled');

        // Get card details
        let ccOwner = $('.card-owner').val();

        stripe.confirmCardPayment(stripe_client_secret, {
            payment_method: {
                card: card_number,
                billing_details: {
                    name: ccOwner,
                    address: {
                        city: city ? city : null,
                        country: country ? country : null, // country code
                        line1: line1 ? line1 : null,
                        line2: line2 ? line2 : null,
                        postal_code: postcode ? postcode : null,
                        state: county ? county : null
                    }
                }
            },
            receipt_email: receipt_email ? receipt_email : null
        }).then(function(result) {
            if (result.error) {
                // Show error

                // check payment intent isn't already paid.
                if(result.error.payment_intent.status == 'succeeded')
                {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'This payment has already been processed, please wait while we check this...',
                    });

                    setTimeout(function() {
                        postPayment();
                    },1200);

                }
                else
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: result.error.message,
                    });
                    resetCheckoutProgress();
                }

            } else {
                // The payment has succeeded.
                postPayment();
            }
        });
    });
});

function postPayment()
{
    $.ajax({
        method : 'POST',
        url : url,
        data : {'payment_id' : payment_id, 'invoice_id' : invoice_id, '_token': $('[name="csrf-token"]').attr('content')},
        success : function(response) {
            if( !response.success )
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message ? response.message : 'Payment failed, please try another payment method.'
                });
                resetCheckoutProgress();
            }
            else
            {
                window.location = '/invoices/payment-response/'+ response.payment_ref;
            }
        },
        error : function(XHR, textStatus, error) {
            let message;
            if(XHR.status === 422) {
                let response = XHR.responseJSON;
                let errors = response.errors;
                message = 'You have errors in your form.';
                $.each( errors, function( key, value ) {
                    //$('#edit-form [name="'+ key +'"]').parent().append('<span id="'+ key +'-error" class="invalid">This field is required.</span>');
                    message += value;
                });
            }
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message ? message : 'An error occurred checking over the payment, please reload this page to see if payment was successful.',
                //footer: '<a href>Why do I have this issue?</a>'
            });

            resetCheckoutProgress();
        }
    });
}

/*
 *	Reset progress
 */
function resetCheckoutProgress()
{
    // Set helper variable
    window.checkoutInProgress = false;

    // Change button
    $('#pay-form button').html('Pay &amp; place order');
    $('#pay-form button').removeAttr('disabled');
}



