@extends('app')

@section('title'){{ trans('misc.donate_to').' '.$response->title.' - ' }}@endsection

@section('css')
<style>
/**
 * The CSS shown here will not be introduced in the Quickstart guide, but shows
 * how you can use CSS to style your Element's container.
 */
.StripeElement {
  box-sizing: border-box;
  height: 40px;
  padding: 11px 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  background-color: white;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;
  margin-bottom: 10px
}

.StripeElement--focus {
	border-color: #f45302;
}

.StripeElement--invalid {
  border-color: #fa755a;
}

.StripeElement--webkit-autofill {
  background-color: #fefde5 !important;
}
</style>

@endsection

@section('content')

<div class="jumbotron mb-0 bg-sections text-center" style="background-image: url('{{ url('public/campaigns/large', $response->large_image) }}')">
      <div class="container wrap-jumbotron position-relative">
      	<h1 class="text-break">{{ trans('misc.donate_to') }} {{ $response->title }}</h1>
      	<p class="mb-0">{{ trans('misc.donate_to_subtitle') }}</p>
      </div>
    </div>

<!-- Container
============================= -->
<div class="container py-5">

  <div class="row">

 <div class="wrap-container-lg">

 <!-- Col MD -->
 <div class="col-md-12">

   @if( isset($pledge) )
     <div class="card mb-3" style="position: initial;">
       <div class="card-body">

         <h6 class="card-title" style="line-height: inherit;">
           <i class="fa fa-gift"></i> {{trans('misc.seleted_pledge')}} <small><a href="{{url('donate',$response->id)}}">{{trans('misc.remove')}}</a></small>
         </h6>

         <hr />

         <h5 class="card-title">
           <small>{{trans('misc.pledge')}}</small> <strong class="font-default">{{App\Helper::amountFormat($pledge->amount)}}</strong>
       </h5>
         <h6 class="card-subtitle mb-2 text-muted">{{ $pledge->title }}</h6>
         <p class="card-text">{{ $pledge->description }}</p>

         <small class="btn-block text-muted">
           <i class="far fa-clock"></i> {{trans('misc.delivery')}}:
         </small>
         <strong>{{ date('F, Y', strtotime($pledge->delivery)) }}</strong>
       </div>
     </div>

 	 <div class="panel panel-default d-none">
   		<div class="panel-body">
 				<h3 class="btn-block margin-zero" style="line-height: inherit;">
 					{{trans('misc.seleted_pledge')}} <small><a href="{{url('donate',$response->id)}}">{{trans('misc.remove')}}</a></small>
 				</h3>
  			<h3 class="btn-block margin-zero" style="line-height: inherit;">
  				<small>{{trans('misc.pledge')}}</small>
  				<strong class="font-default">{{App\Helper::amountFormat($pledge->amount)}}</strong>
  				</h3>
 				<h4>{{ $pledge->title }}</h4>
  				<p class="wordBreak">
  					{{ $pledge->description }}
  				</p>

 				<small class="btn-block text-muted">
 					{{trans('misc.delivery')}}:
 				</small>
 				<strong>{{ date('F, Y', strtotime($pledge->delivery)) }}</strong>
  		</div><!-- panel-body -->
  	</div><!-- End Panel -->
 @endif

   <!-- Start Panel -->
    	<div class="panel panel-default panel-transparent mb-4">
   	  <div class="panel-body">
   	    <div class="media none-overflow">
   			  <div class="d-flex my-2 align-items-center">
   			      <img class="rounded-circle mr-2" src="{{url('public/avatar',$response->user()->avatar)}}" width="60" height="60">

   						<div class="d-block">
                <small class="d-block">{{ trans('misc.organizer') }}</small>
   						{{ trans('misc.by') }} <strong class="text-dark">{{ $response->user()->name }}</strong>

   						@if (Auth::guest() || Auth::check() && Auth::user()->id != $response->user()->id)
   			    		<a href="#" title="{{trans('misc.contact_organizer')}}" class="text-muted" data-toggle="modal" data-target="#sendEmail">
   			    				<i class="fa fa-envelope"></i>
   			    		</a>
   						@endif

   							<div class="d-block">
   								<small class="media-heading text-muted btn-block margin-zero">{{trans('misc.created')}} {{ date($settings->date_format, strtotime($response->date) ) }} <span class="align-middle mx-1" style="font-size:8px;">|</span>
   								@if( $response->location != '' )
   							 <i class="fa fa-map-marker-alt mr-1"></i> {{$response->location}}
   							 @endif
   							 </small>
   							</div>
   						</div>
   			  </div>
   			</div>
   	  </div>
   	</div><!-- End Panel -->

    @include('includes.contact_organizer')

    <span class="progress progress-xs mb-3">
  		<span class="percentage bg-success" style="width: {{$percentage }}%" aria-valuemin="0" aria-valuemax="100" role="progressbar"></span>
  	</span>

  	<small class="btn-block mb-4 text-muted">
  		<strong class="text-strong-small">{{ App\Helper::amountFormat($response->donations()->sum('donation')) }}</strong> {{trans('misc.raised_of')}} {{ App\Helper::amountFormat($response->goal)}} {{strtolower(trans('misc.goal')) }}
  		<strong class="text-percentage">{{$percentage }}%</strong>
  	</small>

    <hr>

  @if (session('notification'))
  <div class="alert alert-success btn-sm alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ session('notification') }}
            </div>
  @endif

  @if (session('notify_error'))
  <div class="alert alert-danger btn-sm alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ session('notify_error') }}
            </div>
          @endif
 <form method="POST" action="{{ url('donate', $response->id) }}" enctype="multipart/form-data" id="formDonation">
 
   <input type="hidden" name="_token" value="{{ csrf_token() }}">
   <input type="hidden" name="_id" value="{{ $response->id }}">
   @if(isset($pledge))
     <input id="_pledge" type="hidden" name="_pledge" value="{{ $pledge->id }}">
   @endif

   @if ($settings->captcha_on_donations == 'on')
     @captcha
   @endif

   <div class="form-group">
  
         <label>{{ trans('misc.enter_your_donation') }}</label>
         <div class="input-group has-success">
           <div class="input-group-prepend">
             <span class="input-group-text">{{$settings->currency_symbol}}</span>
           </div>
           <input type="number" min="{{$settings->min_donation_amount}}"  autocomplete="off" id="onlyNumber" class="form-control form-control-lg" name="amount" id="amount" @if( isset($pledge) )readonly='readonly'@endif value="@if( isset($pledge) ){{$pledge->amount}}@endif" placeholder="{{trans('misc.minimum_amount')}} @if($settings->currency_position == 'left') {{$settings->currency_symbol.$settings->min_donation_amount}} @else {{$settings->min_donation_amount.$settings->currency_symbol}} @endif {{$settings->currency_code}}">
         
         </div>
       </div>

      <!-- Start -->
         <div class="form-row form-group">
           <!-- Start -->
           <div class="col">
           <label>{{ trans('auth.full_name') }}</label>
             <input type="text" id="cardholder-name" value="@if( Auth::check() ){{Auth::user()->name}}@endif" name="full_name" class="form-control input-lg" placeholder="{{ trans('misc.first_name_and_last_name') }}">
             @error('full_name')
             <span class="invalid-feedback" role="alert">
                 <strong>{{ $message }}</strong>
             </span>
         @enderror
           </div><!-- /. End-->

           <!-- Start -->
           <div class="col">
             <label>{{ trans('auth.email') }}</label>
               <input type="text" id="cardholder-email" value="@if( Auth::check() ){{Auth::user()->email}}@endif" name="email" class="form-control input-lg" placeholder="{{ trans('auth.email') }}">
               @error('email')
               <span class="invalid-feedback" role="alert">
                   <strong>{{ $message }}</strong>
               </span>
           @enderror
           </div><!-- /. End-->

         </div><!-- /. form-row-->

           <div class="form-row form-group">
               <!-- Start -->
                 <div class="col">
                   <label>{{ trans('misc.country') }}</label>
                     <select id="country" name="country" class="custom-select" >
                       <option value="">{{trans('misc.select_one')}}</option>
                      
                       @foreach (App\Models\Countries::orderBy('name')->get() as $country)
                       <option @if( Auth::check() ){{ auth()->user()->countries_id == $country->id }} selected="selected" @endif  value="{{ $country->name }}" {{$country->id == 110 ? 'selected' : ''}} >{{ $country->name }}</option>
                       @endforeach
                       </select>
                    
                     </div><!-- /. End--> 

               <!-- Start -->
                 <div class="col">
                   <label>{{ trans('misc.postal_code') }}</label>
                     <input type="text" id="postal_code" value="{{ old('postal_code') }}" name="postal_code" class="form-control" placeholder="{{ trans('misc.postal_code') }}">
                   
                 </div><!-- /. End-->

                 

               </div><!-- form-row -->
 <!-- ***** Form Group ***** -->
              <div class="form-group">
              <p>Phone number</p>            
               <input size="70"  class="form-control" id="phone1" name="phone" value="@if( Auth::check() ){{Auth::user()->phone_number}}@endif"  type="tel"> 
          
            </div><!-- ***** Form Group ***** -->  
            
           
              
               <!-- Start -->
                 <div class="form-group">
                     <input type="text" id="comment" value="{{ old('comment') }}" name="comment" class="form-control input-lg" placeholder="{{ trans('misc.leave_comment') }}">
                 </div><!-- /. End-->

                 <!-- Start -->
                   <div class="form-group">
                     <label>{{ trans('misc.payment_gateway') }}</label>
                         @foreach (PaymentGateways::where('enabled', '1')->orderBy('type')->get(); as $payment)
                            
                         <?php

                           if($payment->type == 'card' ) {

                             $paymentName = '<i class="far fa-credit-card mr-1"></i> '. trans('misc.debit_credit_card') . ' ('.$payment->name.')';
                           } elseif ($payment->type == 'bank') {
                             $paymentName = '<i class="fa fa-university mr-1"></i> '.trans('misc.bank_transfer');
                           } elseif ($payment->type == 'mobile') {
                            $paymentName = '<i class="fa fa-mobile" aria-hidden="true"></i>'.' '.$payment->name;
                           }else {
                             $paymentName = '<i class="fa fa-wallet mr-1"></i> '.trans('misc.pay_through').' '.$payment->name;
                           }

                          ?>

                         <div class="custom-control custom-radio mb-2">
                          <input @if (PaymentGateways::where('enabled', '1')->count() == 1) checked @endif type="radio" id="payment_gateway{{$payment->id}}" name="payment_gateway" value="{{$payment->id}}" class="custom-control-input paymentGateway">
                          <label class="custom-control-label" for="payment_gateway{{$payment->id}}">{!! $paymentName !!}</label>
                  
                        </div>

                        @if ($_bankTransfer)

                          @if ($payment->id == 3)

                            <div class="btn-block @if (PaymentGateways::where('enabled', '1')->count() != 1) d-none-custom @endif" id="bankTransferBox">
                              <div class="alert alert-info">
                              <h5 class="font-weight-bold"><i class="fa fa-university"></i> {{trans('misc.make_payment_bank')}}</h5>
                              <ul class="list-unstyled">
                                  <li>
                                    {!!nl2br($_bankTransfer->bank_info)!!}
                                  </li>
                              </ul>
                            </div>

                            <div class="row form-group">
                            <!-- Start -->
                              <div class="col-sm-12">
                                <label>{{ trans('admin.bank_transfer_details') }}</label>
                                  <textarea name="bank_transfer" rows="4" class="form-control input-lg" placeholder="{{ trans('admin.bank_transfer_info') }}"></textarea>
                            </div><!-- /. End-->
                            </div><!-- row form-control -->
                            </div><!-- Alert -->
                          @endif
                        @endif

                        @if ($payment->id == 2)
                        <div id="stripeContainer" class="@if (PaymentGateways::where('enabled', '1')->count() != 1) d-none-custom @endif">
                          <div id="card-element" class="margin-bottom-10">
                            <!-- A Stripe Element will be inserted here. -->
                          </div>
                          <!-- Used to display form errors. -->
                          <div id="card-errors" class="alert alert-danger d-none-custom" role="alert"></div>
                        </div>
                        @endif

                        @endforeach
                        @error('payment_gateway')
             <span class="invalid-feedback" role="alert">
                 <strong>{{ $message }}</strong>
             </span>
         @enderror
                   </div><!-- /. End-->

     <div class="form-group custom-control custom-checkbox">
       <input class="custom-control-input" id="customControlInline" name="anonymous" type="checkbox" value="1">
       <label class="custom-control-label" for="customControlInline">{{ trans('misc.anonymous_donation') }}</label>
     </div>

    <!-- Alert -->
   <div class="alert alert-danger d-none-custom" id="errorDonation">
     <ul class="list-unstyled m-0" id="showErrorsDonation"></ul>
   </div><!-- Alert -->

         <div class="box-footer text-center">
           @if ($settings->captcha_on_donations == 'on')
             <p class="help-block">
               <em>{{trans('misc.user_captcha')}} @if($settings->registration_active == 'on')- <a href="{{url('register')}}">{{trans('auth.sign_up')}}</a>@endif - <a href="{{url('login')}}">{{trans('auth.login')}}</a></em>
                 <small class="btn-block text-center">{{trans('misc.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{trans('misc.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{trans('misc.terms')}}</a></small>
             </p>
           @endif

           <hr />

           <button type="submit" id="buttonDonation" class="btn btn-lg btn-primary w-100 no-hover mb-2"><i></i> {{ trans('misc.donate') }}</button>
           <div class="btn-block text-center margin-top-20">
           <a href="{{url('campaign',$response->id)}}" class="text-muted">
           <i class="fa fa-long-arrow-alt-left"></i>	{{trans('auth.back')}}</a>
        </div>
         </div><!-- /.box-footer -->
       </form>

</div><!-- /COL MD -->
</div><!-- wrap-container-lg -->

</div><!-- Row -->

 </div><!-- container wrap-ui -->

@endsection

@section('javascript')
<!-- <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src='https://js.paystack.co/v1/inline.js'></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script> 
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script type="text/javascript">
 
 var input = document.querySelector("#phone1");
 

var iti = window.intlTelInput(input, {
  preferredCountries: ["ke", "co", "in", "de"],
  utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"// just for formatting/placeholders etc 
});


// document.getElementById('b2cSimulate').addEventListener('click', (event) => {
//     event.preventDefault()
    
//     const requestBody = {
//         amount: document.getElementById('amount').value,
//         occasion: document.getElementById('occasion').value,
// 		remarks: document.getElementById('remarks').value,
//         phone: document.getElementById('phone').value
//     }

//     axios.post('http://localhost/Fundme/Script/simulateb2c', requestBody)
//     .then((response) => {
//         if(response.data.ResponseDescription){
//             document.getElementById('c2b_response').innerHTML = response.data.Result.ResultDesc
//         } else {
//             document.getElementById('c2b_response').innerHTML = response.data.errorMessage
//         }
//     })
//     .catch((error) => {
//         console.log(error);
//     })
// })

// document.getElementById('getaccesstoken').addEventListener('click', (event) => {
//   event.preventDefault()

//   axios.post('http://localhost/Fundme/Script/get-token', {})
//   .then((response) => {
//     console.log(response.data);
//     document.getElementById('access_token').innerHTML = response.data
//   })
//   .catch((error) => {
//     console.log(error);
//   })
// })

// document.getElementById('registerUrls').addEventListener('click', (event) => {
//   event.preventDefault()

//   axios.post('http://localhost/Fundme/Script/registerUrls', {})
//   .then((response) => {
//     console.log(response.data);
//   }) 
//   .catch((error) => {
//     console.log(error);
//   })
// })
//  document.getElementById('stkpush').addEventListener('click', (event) => {
//     event.preventDefault()
    
//     const requestBody = {
//         amount: document.getElementById('amount').value,
//         account: document.getElementById('account').value,
//         phone: document.getElementById('phone').value
//     }

//     axios.post('http://localhost/Fundme/Script/stkpush', requestBody)
//     .then((response) => {
//         if(response.data.ResponseDescription){
//             document.getElementById('c2b_response').innerHTML = response.data.ResponseDescription
//         } else {
//             document.getElementById('c2b_response').innerHTML = response.data.errorMessage
//         }
//     })
//     .catch((error) => {
//         console.log(error);
//     })
// })

$(document).ready(function() {

	$("#onlyNumber").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
});

$('.paymentGateway').on('change', function(){

    if($(this).val() == '3') {
			$('#bankTransferBox').slideDown();
		} else {
				$('#bankTransferBox').slideUp();
		}

		if($(this).val() == '2') {
			$('#stripeContainer').slideDown();
		} else {
			$('#stripeContainer').slideUp();
		}

});


@if(isset($_stripe->key))
// Create a Stripe client.

var stripe = Stripe('{{$_stripe->key}}');
// const stripe = Stripe('{{env('STRIPE_KEY')}}');

// Create an instance of Elements.
var elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
  base: {
    color: '#32325d',
    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
    fontSmoothing: 'antialiased',
    fontSize: '16px',
    '::placeholder': {
      color: '#aab7c4'
    }
  },
  invalid: {
    color: '#fa755a',
    iconColor: '#fa755a'
  }
};

// Create an instance of the card Element.
var cardElement = elements.create('card', {style: style, hidePostalCode: true});

// Add an instance of the card Element into the `card-element` <div>.
cardElement.mount('#card-element');

// Handle real-time validation errors from the card Element.
cardElement.addEventListener('change', function(event) {
  var displayError = document.getElementById('card-errors');
  var payment = $('input[name=payment_gateway]:checked').val();

  if(payment == 2) {
    if (event.error) {
  		displayError.classList.remove('d-none-custom');
      displayError.textContent = event.error.message;
      $('#buttonDonation').removeAttr('disabled');
      $('#buttonDonation').find('i').removeClass('spinner-border spinner-border-sm align-baseline mr-1');
    } else {
  		displayError.classList.add('d-none-custom');
      displayError.textContent = '';
    }
  }

});

var cardholderName = document.getElementById('cardholder-name');
var cardholderEmail = document.getElementById('cardholder-email');
var cardButton = document.getElementById('buttonDonation');

function chargeDonation() {

	//ev.preventDefault();

  var payment = $('input[name=payment_gateway]:checked').val();

  if(payment == 2) {

  stripe.createPaymentMethod('card', cardElement, {
    billing_details: {name: cardholderName.value, email: cardholderEmail.value}
  }).then(function(result) {
    if (result.error) {

      if(result.error.type == 'invalid_request_error') {

          if(result.error.code == 'parameter_invalid_empty') {
            $('.popout').addClass('popout-error').html('{{trans('admin.card_required_name_email')}}').fadeIn('500').delay('5000').fadeOut('500');
          } else {
            $('.popout').addClass('popout-error').html(result.error.message).fadeIn('500').delay('5000').fadeOut('500');
          }
      }
      $('#buttonDonation').removeAttr('disabled');
      $('#buttonDonation').find('i').removeClass('spinner-border spinner-border-sm align-baseline mr-1');

    } else {

      $('#buttonDonation').attr({'disabled' : 'true'});
      $('#buttonDonation').find('i').addClass('spinner-border spinner-border-sm align-baseline mr-1');

      // Otherwise send paymentMethod.id to your server
      $('input[name=payment_method_id]').remove();

			var $input = $('<input id=payment_method_id type=hidden name=payment_method_id />').val(result.paymentMethod.id);
			$('#formDonation').append($input);

			$.ajax({
 		 	headers: {
         	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     		},
 		   type: "POST",
			 dataType: 'json',
 		   url:"{{url('payment/stripe/charge')}}",
 		   data: $('#formDonation').serialize(),
 		   success: function(result) {
           handleServerResponse(result);

           if(result.success == false) {
             $('#buttonDonation').removeAttr('disabled');
             $('#buttonDonation').find('i').removeClass('spinner-border spinner-border-sm align-baseline mr-1');
           }
 		 }//<-- RESULT
 	   })

    }//ELSE
  });
}//PAYMENT STRIPE
}

function handleServerResponse(response) {
  if (response.error) {
    $('.popout').addClass('popout-error').html(response.error).fadeIn('500').delay('5000').fadeOut('500');
    $('#buttonDonation').removeAttr('disabled');
    $('#buttonDonation').find('i').removeClass('spinner-border spinner-border-sm align-baseline mr-1');

  } else if (response.requires_action) {
    // Use Stripe.js to handle required card action
    stripe.handleCardAction(
      response.payment_intent_client_secret
    ).then(function(result) {
      if (result.error) {
        $('.popout').addClass('popout-error').html('{{trans('misc.error_payment_stripe_3d')}}').fadeIn('500').delay('10000').fadeOut('500');
        $('#buttonDonation').removeAttr('disabled');
        $('#buttonDonation').find('i').removeClass('spinner-border spinner-border-sm align-baseline mr-1');

      } else {
        // The card action has been handled
        // The PaymentIntent can be confirmed again on the server

				var $input = $('<input type=hidden name=payment_intent_id />').val(result.paymentIntent.id);
				$('#formDonation').append($input);

        $('input[name=payment_method_id]').remove();

				$.ajax({
	 		 	headers: {
	         	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	     		},
	 		   type: "POST",
				 dataType: 'json',
	 		   url:"{{url('payment/stripe/charge')}}",
	 		   data: $('#formDonation').serialize(),
	 		   success: function(result){

					 if(result.success) {
             $('#buttonDonation').attr({'disabled' : 'true'});
             $('#buttonDonation').find('i').addClass('spinner-border spinner-border-sm align-baseline mr-1');
             $url = '{{url('paypal/donation/success', $response->id)}}';
         		  window.location.href = $url;
					 } else {
						 $('.popout').addClass('popout-error').html(result.error).fadeIn('500').delay('5000').fadeOut('500');
             $('#buttonDonation').removeAttr('disabled');
             $('#buttonDonation').find('i').removeClass('spinner-border spinner-border-sm align-baseline mr-1');
					 }
	 		 }//<-- RESULT
	 	   })
      }// ELSE
    });
  } else {
    // Show success message
    if(response.success) {
      $('#buttonDonation').attr({'disabled' : 'true'});
      $('#buttonDonation').find('i').addClass('spinner-border spinner-border-sm align-baseline mr-1');
      $url = '{{url('paypal/donation/success', $response->id)}}';
  		window.location.href = $url;
    }

  }
}
@endif

//<---------------- Donate ----------->>>>
@if ($settings->captcha_on_donations == 'on')
_submitEvent = function() {
  sendDonation();

@if(isset($_stripe->key))
  chargeDonation();
  @endif

};
  @else
  $(document).on('click','#buttonDonation', function(s) {
    s.preventDefault();
    sendDonation();

    @if(isset($_stripe->key))
      chargeDonation();
      @endif

    });//<<<-------- * END FUNCTION CLICK * ---->>>>
@endif


function sendDonation() {

  var element = $(this);
  var payment = $('input[name=payment_gateway]:checked').val();
  $('#buttonDonation').attr({'disabled' : 'true'});
  $('#buttonDonation').find('i').addClass('spinner-border spinner-border-sm align-baseline mr-1');

  $.ajax({
        type: "POST",
        dataType: 'json',
        url:"{{url('donate', $response->id)}}",
        data: $('#formDonation').serialize(),
        success: function(result) {
            // success
            if(result.success == true && result.insertBody) {

              $('#bodyContainer').html('');

             $(result.insertBody).appendTo("#bodyContainer");

             if (payment != 1 && payment != 2) {
               $('#buttonDonation').removeAttr('disabled');
               $('#buttonDonation').find('i').removeClass('spinner-border spinner-border-sm align-baseline mr-1');
             }

              //element.removeAttr('disabled');
              $('#errorDonation').fadeOut();

            } else if(result.success == true && result.url) {
              window.location.href = result.url;
            } else {

              var error = '';

              for($key in result.errors) {
                error += '<li><i class="far fa-times-circle mr-2"></i> ' + result.errors[$key] + '</li>';
              }

              $('#showErrorsDonation').html(error);
              $('#errorDonation').fadeIn(500);
              $('#buttonDonation').find('i').removeClass('spinner-border spinner-border-sm align-baseline mr-1');
              $('#buttonDonation').removeAttr('disabled');

            }
        },
        error: function(responseText, statusText, xhr, $form) {
            // error
            $('#buttonDonation').removeAttr('disabled');
            $('#buttonDonation').find('i').removeClass('spinner-border spinner-border-sm align-baseline mr-1');
            $('.popout').addClass('popout-error').html('Error ('+xhr+')').fadeIn('500').delay('5000').fadeOut('500');
        }
    });
}
//<---------------- End Donate ----------->>>>
</script>
@endsection
