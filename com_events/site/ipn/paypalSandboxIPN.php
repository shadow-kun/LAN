<?php
	// CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
	// Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
	// Set this to 0 once you go live or don't require logging.
	
	define("DEBUG", 1);

	// Set to 0 once you're ready to go live

	define("USE_SANDBOX", 1);

	define("LOG_FILE", "./ipn.log");

	// Read POST data

	// reading posted data directly from $_POST causes serialization

	// issues with array data in POST. Reading raw POST data from input stream instead.

	$raw_post_data = file_get_contents('php://input');
	$raw_post_array = explode('&', $raw_post_data);

	$myPost = array();

	foreach ($raw_post_array as $keyval) {
		$keyval = explode ('=', $keyval);
		if (count($keyval) == 2)
			$myPost[$keyval[0]] = urldecode($keyval[1]);
	}
	
	// read the post from PayPal system and add 'cmd'
	
	$req = 'cmd=_notify-validate';
	
	if(function_exists('get_magic_quotes_gpc')) {
		$get_magic_quotes_exists = true;
	}

	foreach ($myPost as $key => $value) {
		if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
			$value = urlencode(stripslashes($value));
		} else {
			$value = urlencode($value);
		}
		$req .= "&$key=$value";
	}
	
	// Post IPN data back to PayPal to validate the IPN data is genuine
	// Without this step anyone can fake IPN data
	if(USE_SANDBOX == true) {
		$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	} else {
		$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
	}

	$ch = curl_init($paypal_url);
	if ($ch == FALSE) {
		return FALSE;
	}
	
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
	
	if(DEBUG == true) {
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
	}

	// CONFIG: Optional proxy configuration
	//curl_setopt($ch, CURLOPT_PROXY, $proxy);
	//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
	// Set TCP timeout to 30 seconds
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
	
	// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
	// of the certificate as shown below. Ensure the file is readable by the webserver.
	// This is mandatory for some environments.
	//$cert = __DIR__ . "./cacert.pem";
	//curl_setopt($ch, CURLOPT_CAINFO, $cert);
	$res = curl_exec($ch);
	if (curl_errno($ch) != 0) // cURL error
	{
		if(DEBUG == true) {	
			error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
		}
		curl_close($ch);
		exit;
	} else {
		// Log the entire HTTP response if debug is switched on.
		if(DEBUG == true) {
			error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
			error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
		}
		curl_close($ch);
	}

	// Inspect IPN validation result and act accordingly
	// Split response headers and payload, a better way for strcmp
	$tokens = explode("\r\n\r\n", trim($res));
	$res = trim(end($tokens));
	if (strcmp ($res, "VERIFIED") == 0) {
		// check whether the payment_status is Completed
		// check that txn_id has not been previously processed
		// check that receiver_email is your PayPal email
		// check that payment_amount/payment_currency are correct
		// process payment and mark item as paid.
		// assign posted variables to local variables
		//$item_name = $_POST['item_name'];
		//$item_number = $_POST['item_number'];
		//$payment_status = $_POST['payment_status'];
		//$payment_amount = $_POST['mc_gross'];
		//$payment_currency = $_POST['mc_currency'];
		//$txn_id = $_POST['txn_id'];
		//$receiver_email = $_POST['receiver_email'];
		//$payer_email = $_POST['payer_email'];
		
		/********************************************************/
		
		// Authentication protocol is complete - OK to process notification contents
				
		// Possible processing steps for a payment include the following:
		// Connect To Joomla
		
		define('_JEXEC', 1);

		if (file_exists('../../../defines.php'))
		{
			include_once '../../../defines.php';
		}

		if (!defined('_JDEFINES'))
		{
			define('JPATH_BASE', '../../../');
			require_once JPATH_BASE . 'includes/defines.php';
		}

		require_once JPATH_BASE . 'includes/framework.php';
		
		// Mark afterLoad in the profiler.
		JDEBUG ? $_PROFILER->mark('afterLoad') : null;

		// Instantiate the application.
		$app = JFactory::getApplication('site');
		
		// Gets database connection
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->select('a.id AS id, a.params AS params');
		$query->from('#__events_payments AS a');
					
		// Selects current user.
		$query->where('a.transaction_id = ' . $db->quote(JRequest::getVar('txn_id')));
		
		// file_put_contents('query.txt', $query);				
		// Runs query
		$result = $db->setQuery($query)->loadObject();
		$db->query();
		$query	= $db->getQuery(true);
		
		
		// Check that txn_id has not been previously processed
		if(!isset($result->id))
		{	
			$item_type = '';
			// Sets Variable for payment
			if(isset($_POST['item_number']) == true)
			{
				$item_number = intval(substr($_POST['item_number'], 2));
				$item_type = substr($_POST['item_number'], 0, 1);
			}
			else if (isset($_POST['item_number1']) == true)
			{
				$item_number = intval(substr($_POST['item_number1'], 2));
				$item_type = substr($_POST['item_number1'], 0, 1);
			}
			else 
			{
				$item_number = 0;
				$item_type = 'E';
			}
			
			if(isset($_POST['mc_gross']) == true)
			{
				$paypalAmount = floatval($_POST['mc_gross']);
			}
			else if (isset($_POST['mc_gross1']) == true)
			{
				$paypalAmount = floatval($_POST['mc_gross1']);
			}
			else 
			{
				$paypalAmount = floatval(0.0);
			}
			
			
			
			if(strcasecmp($item_type, 'E') == 0)
			{
				// ************ Logs the new payment *************
				// Check that payment_amount/payment_currency are correct
				$query->select('p.user AS user');
				$query->from('#__events_players AS p');
				
				// Selects current transaction.
				$query->where('p.id = ' . intval($item_number));
				
				// Runs query
				$user = $db->setQuery($query)->loadResult();
				$db->query();
				$query	= $db->getQuery(true);
				
				
				// Sets JSON Params data
				$params = $db->quote(json_encode(array('payment_method' => 'paypal', 'payment_status' => $db->quote(JRequest::getVar('payment_status')))));
			
				// Sets columns
				$colums = array('id', 'created_time', 'userEventID', 'transaction_id', 'amount', 'currency', 'params', 'user');

				// Sets values
				$values = array('NULL', 'NULL', intval($item_number), $db->quote(JRequest::getVar('txn_id')), $paypalAmount, $db->quote(JRequest::getVar('mc_currency')), $params, $user);

				// Prepare Insert Query $db->quoteName('unconfirmed')
				$query  ->insert($db->quoteName('#__events_payments'))
						->columns($db->quoteName($colums))
						->values(implode(',', $values));
				
				// Set the query and execute item
				$db->setQuery($query);
				$db->query();
				$query	= $db->getQuery(true);
				
					
				// Check that the payment_status is Completed
				if(strcasecmp($_POST['payment_status'], 'Completed') == 0)
				{
					// Check that payment_amount/payment_currency are correct
					$query->select('a.id AS id, a.params AS params, a.body AS body, a.title AS title');
					$query->from('#__events_events AS a');
					
					$query->select('p.user AS user, p.status AS status');
					$query->join('LEFT', '#__events_players AS p ON a.id = p.event');
					
					// Selects current transaction.
					$query->where('p.id = ' . intval($item_number));
					
					// Runs query
					$result = $db->setQuery($query)->loadObject();
					$db->query();
					$event = $result;
					$user = $result->user;
					
					$query	= $db->getQuery(true);
					$query->select('a.params');
					$query->from('#__extensions AS a');
					
					// Selects current user.
					$query->where('name = "com_events"');
					
					// Runs query
					$results2 = $db->setQuery($query)->loadObject();
					$db->query();
					$query	= $db->getQuery(true);
					
					$pparams = json_decode($results2->params);
					$prepay = json_decode($result->params)->prepay;
					if($prepay === '')
					{
						$prepay = $pparams->prepay;
					}
						
					$pEmail = '';	
					if(json_decode($result->params)->paypal_global == 0)
					{	$pCurrency = $pparams->paypal_currency;
						$pEmail = $pparams->paypal_email;
					}
					else
					{
						$pCurrency = json_decode($event->params)->paypal_currency;
						$pEmail = json_decode($event->params)->paypal_email;
					}
					
					$pAmount = json_decode($event->params)->cost_prepay;
					
					
					//file_put_contents('query3.txt', $pCurrency . ' - ' . $pEmail . ' - ' . $pAmount);
					//file_put_contents('query2.txt', $event->id );
					
					
				
					
					//file_put_contents('query3.txt', 'hereasdasdasdh' . $event->params . $pAmount . ' ' . $paypalAmount);
					//file_put_contents('query2.txt', 'Results: ' . isset($result->id) . ', Amount: ' . ($pAmount == $paypalAmount) . ', Email: ' . (strcasecmp($_POST['business'], $pEmail) == 0) . ',Currency: ' . (strcasecmp($_POST['mc_currency'], $pCurrency) == 0));
					
					$checks = false;
					if(isset($result->id)) 
					{
						if($pAmount == $paypalAmount)
						{
							if(strcasecmp($_POST['business'], $pEmail) == 0)
							{
								if(strcasecmp($_POST['mc_currency'], $pCurrency) == 0)
								{
									$checks = true;
								}
							}
						}
					}
								
					
				
					if($checks == true)
					{
						// Process payment
						
						// Gets data to update
						$query	= $db->getQuery(true);
						$fields = $db->quoteName('status') . ' = 4';
						
						// Sets the conditions of which event and which player to update
						$conditions = array($db->quoteName('id') . ' = ' . intval($item_number));
						
						// Executes Query
						$query->update($db->quoteName('#__events_players'));
						$query->set($fields);
						$query->where($conditions);
						
						$db->setQuery($query);
						
						$db->query();
						
						// Check that payment_amount/payment_currency are correct
						$query	= $db->getQuery(true);
						$query->select('count(id) AS id');
						$query->from('#__events_players');
						$query->where($db->quoteName('event') . ' = ' . $event->id . ' AND ' . $db->quoteName('status') . ' = 4');
						$prepaids = $db->setQuery($query)->loadObject();
						
						// Gets data to update
						$query	= $db->getQuery(true);
						$fields = $db->quoteName('players_prepaid') . ' = ' . $prepaids->id;
						
						// Sets the conditions of which event and which player to update
						$conditions = array($db->quoteName('id') . ' = ' . $event->id);
						
						// Executes Query
						$query->update($db->quoteName('#__events_events'));
						$query->set($fields);
						$query->where($conditions);
						
						$db->setQuery($query);
						
						$db->query();
						
						if($prepay == 2)
						{
							
							include('../models/default.php');  
							include('../models/event.php');  
							
							$model = new EventsModelsEvent();
							$model->sendTicket($event->id, $user);
						}	
					}
					
					
				}	
			}		
		
			else if(strcasecmp($item_type, 'S') == 0)
			{
			// ************ Logs the new payment *************
				// Check that payment_amount/payment_currency are correct
				$query	= $db->getQuery(true);
				$query->select('p.user AS user');
				$query->from('#__events_shop_orders AS p');
				
				// Selects current transaction.
				$query->where('p.id = ' . intval($item_number));
				
				// Runs query
				$user = $db->setQuery($query)->loadResult();
				$db->query();
				
				$query	= $db->getQuery(true);
				// Sets JSON Params data
				$params = $db->quote(json_encode(array('payment_method' => 'paypal', 'payment_status' => $db->quote(JRequest::getVar('payment_status')))));
			
				// Sets columns
				$colums = array('id', 'created_time', 'orderID', 'transaction_id', 'amount', 'currency', 'params', 'user');

				// Sets values
				$values = array('NULL', 'NULL', intval($item_number), $db->quote(JRequest::getVar('txn_id')), $paypalAmount, $db->quote(JRequest::getVar('mc_currency')), $params, $user);

				// Prepare Insert Query $db->quoteName('unconfirmed')
				$query  ->insert($db->quoteName('#__events_payments'))
						->columns($db->quoteName($colums))
						->values(implode(',', $values));
				
				// Set the query and execute item
				$db->setQuery($query);
				$db->query();
				$query	= $db->getQuery(true);
				
				// Check that the payment_status is Completed
				if(strcmp(JRequest::getVar('payment_status'), 'Completed') == 0)
				{
					
						var_dump(intval($item_number));
					// Check that payment_amount/payment_currency are correct
					$query->select('a.id AS id, a.amount AS amount, a.status AS status, a.user AS user');
					$query->from('#__events_shop_orders AS a');
												
					// Selects current transaction.
					$query->where('a.id = ' . intval($item_number));
					
					// Runs query
					$result = $db->setQuery($query)->loadObject();
					$db->query();
					
					$query	= $db->getQuery(true);
					$query->select('a.params');
					$query->from('#__extensions AS a');
					
					// Selects current user.
					$query->where('name = "com_events"');
									
					// Runs query
					$results2 = $db->setQuery($query)->loadObject();
					$db->query();
					
					$pparams = json_decode($results2->params);
					$prepay = $pparams->prepay;
					
					$pCurrency = $pparams->paypal_currency;
					$pEmail = $pparams->paypal_email;
					
					$pAmount = $result->amount;
					
					
					if(isset($result->id) && $pAmount == $paypalAmount && strcmp(JRequest::getVar('business'), $pEmail) == 0 && strcmp(JRequest::getVar('mc_currency'), $pCurrency) == 0)
					{
						// Process payment
						
						// Gets data to update
						$query	= $db->getQuery(true);
						$fields = $db->quoteName('status') . ' = 2';
						
						// Sets the conditions of which event and which player to update
						$conditions = array($db->quoteName('id') . ' = ' . $result->id);
						// Executes Query
						$query->update($db->quoteName('#__events_shop_orders'));
						$query->set($fields);
						$query->where($conditions);
						
						$db->setQuery($query);
						
						$db->query();
						
						// Check that payment_amount/payment_currency are correct
						
						
						/*if($prepay == 2)
						{
							
							include('../models/default.php');  
							include('../models/event.php');  
							
							$model = new EventsModelsEvent();
							$model->sendTicket($event->id, $user);
						}*/
					}
					
				}
			}
		}
		
		
		/********************************************************/
		
		if(DEBUG == true) {
			error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
		}
	} else if (strcmp ($res, "INVALID") == 0) {
		// log for manual investigation
		// Add business logic here which deals with invalid IPN messages
		if(DEBUG == true) {
			error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
		}
	}
?>