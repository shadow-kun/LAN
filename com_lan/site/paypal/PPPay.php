<?php 
$path = '../../lib';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once('PPBootStrap.php');


// # Pay API
// Use the Pay API operation to transfer funds from a sender's PayPal account to one or more receivers' PayPal accounts. You can use the Pay API operation to make simple payments, chained payments, or parallel payments; these payments can be explicitly approved, preapproved, or implicitly approved.
// This sample code uses AdaptivePayments PHP SDK to make API call. You can
// download the SDK [here](https://github.com/paypal/sdk-packages/tree/gh-pages/adaptivepayments-sdk/php)
class Pay
{
	
	public function simplePay()
	{
		
		// ##PayRequest
		// The code for the language in which errors are returned, which must be
		// en_US.
		$requestEnvelope = new RequestEnvelope("en_US");
		
		$receiver = array();
		$receiver[0] = new Receiver();
		
		// A receiver's email address
		$receiver[0]->email = "dj38870-facilitator@hotmail.com";
		
		// Amount to be credited to the receiver's account
		$receiver[0]->amount = "4.00";
		
		$receiverList = new ReceiverList($receiver);
		
		// PayRequest which takes mandatory params:
		//
		// * `Request Envelope` - Information common to each API operation, such
		// as the language in which an error message is returned.
		// * `Action Type` - The action for this request. Possible values are:
		// * PAY - Use this option if you are not using the Pay request in
		// combination with ExecutePayment.
		// * CREATE - Use this option to set up the payment instructions with
		// SetPaymentOptions and then execute the payment at a later time with
		// the ExecutePayment.
		// * PAY_PRIMARY - For chained payments only, specify this value to delay
		// payments to the secondary receivers; only the payment to the primary
		// receiver is processed.
		// * `Cancel URL` - URL to redirect the sender's browser to after
		// canceling the approval for a payment; it is always required but only
		// used for payments that require approval (explicit payments)
		// * `Currency Code` - The code for the currency in which the payment is
		// made; you can specify only one currency, regardless of the number of
		// receivers
		// * `Recevier List` - List of receivers
		// * `Return URL` - URL to redirect the sender's browser to after the
		// sender has logged into PayPal and approved a payment; it is always
		// required but only used if a payment requires explicit approval
		$payRequest = new PayRequest($requestEnvelope, "PAY", "http://localhost/cancel", "AUD", $receiverList, "http://localhost/return");
		
		// The URL to which you want all IPN messages for this payment to be
		// sent.
		// This URL supersedes the IPN notification URL in your profile
		$payRequest->ipnNotificationUrl = "http://localhost/ipn";
		
		return $this->makeAPICall($payRequest);
	}	
	
	private function makeAPICall($payRequest)
	{
		$logger = new PPLoggingManager('Pay');
		// ## Creating service wrapper object
		// Creating service wrapper object to make API call and loading
		// configuration file for your credentials and endpoint
		$service = new AdaptivePaymentsService();
		
		try {
			// ## Making API call
			// Invoke the appropriate method corresponding to API in service
			// wrapper object
			$response = $service->Pay($payRequest);
			
		} catch(Exception $ex) {
			$logger->error("Error Message : ". $ex->getMessage());
		}
		
		// ## Accessing response parameters
		// You can access the response parameters in
		// response object as shown below
		// ### Success values
		if ($response->responseEnvelope->ack == "Success")
		{
		
			// The pay key, which is a token you use in other Adaptive
			// Payment APIs (such as the Refund Method) to identify this
			// payment. The pay key is valid for 3 hours; the payment must
			// be approved while the pay key is valid.
			$logger->log("Pay Key : ".$response->payKey);
		
			// Once you get success response, user has to redirect to PayPal
			// for the payment. Construct redirectURL as follows,
			// `redirectURL=https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey="
			// + $response->payKey`
		}
		// ### Error Values
		// Access error values from error list using getter methods
		else{
			$logger->error("API Error Message : ".$response->error[0]->message);
		}
		
		return $response;
	}
}

