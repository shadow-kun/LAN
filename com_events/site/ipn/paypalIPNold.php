<?php

	// Send an empty HTTP 200 OK response to acknowledge receipt of the notification 
	header('HTTP/1.1 200 OK'); 
	
	// Build the required acknowledgement message out of the notification just received
	$req = 'cmd=_notify-validate';               // Add 'cmd=_notify-validate' to beginning of the acknowledgement
		

	foreach ($_POST as $key => $value) {         // Loop through the notification NV pairs
		$value = urlencode(stripslashes($value));  // Encode these values
		$req  .= "&$key=$value";                   // Add the NV pairs to the acknowledgement
	}
  
			
	// Set up the acknowledgement request headers
	$header  = "POST /cgi-bin/webscr HTTP/1.1\r\n";                    // HTTP POST request
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	//$header .= "Host: www.paypal.com\r\n";  // www.paypal.com for a live site 
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	
	// Open a socket for the acknowledgement request
	$fp = fsockopen('tls://www.paypal.com', 443, $errno, $errstr, 30);

	$stuff = null;
	if (!$fp) 
	{
		// HTTP ERROR
	} 
	else 
	{
	
		// Send the HTTP POST request back to PayPal for validation
		fputs($fp, $header . $req);
		
		while (!feof($fp)) // While not EOF
		{                     
			$res = fgets($fp, 1024);               // Get the acknowledgement response
			
			if (stripos($res, "VERIFIED") !== false) // Response contains VERIFIED - process notification
			{
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
								
				// Runs query
				$result = $db->setQuery($query)->loadObject();
				$db->query();
				$query	= $db->getQuery(true);
				
				// Check that txn_id has not been previously processed
				if(!isset($result->id))
				{	
					// Sets Variable for payment
					if(isset($_POST['item_number']) == true)
					{
						$item_number = JRequest::getInt('item_number');
					}
					else if (isset($_POST['item_number1']) == true)
					{
						$item_number = JRequest::getInt('item_number1');
					}
					else 
					{
						$item_number = 0;
					}
					
					if(isset($_POST['mc_gross']) == true)
					{
						$paypalAmount = JRequest::getInt('mc_gross');
					}
					else if (isset($_POST['mc_gross1']) == true)
					{
						$paypalAmount = JRequest::getFloat('mc_gross1');
					}
					else 
					{
						$paypalAmount = 0;
					}
					
					// ************ Logs the new payment *************
					// Check that payment_amount/payment_currency are correct
					$query->select('p.user AS user');
					$query->from('#__events_players AS p');
					
					// Selects current transaction.
					$query->where('p.id = ' . $item_number);
					
					// Runs query
					$user = $db->setQuery($query)->loadResult();
					$db->query();
					
					// Sets JSON Params data
					$params = $db->quote(json_encode(array('payment_method' => 'paypal', 'payment_status' => $db->quote(JRequest::getVar('payment_status')))));

					// Sets columns
					$colums = array('id', 'created_time', 'userEventID', 'transaction_id', 'amount', 'currency', 'params', 'user');

					// Sets values
					$values = array('NULL', 'NULL', $item_number, $db->quote(JRequest::getVar('txn_id')), $paypalAmount, $db->quote(JRequest::getVar('mc_currency')), $params, $user);

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
						// Check that payment_amount/payment_currency are correct
						$query->select('a.id AS id, a.params AS params, a.body AS body, a.title AS title');
						$query->from('#__events_events AS a');
						
						$query->select('p.user AS user, p.status AS status');
						$query->join('LEFT', '#__events_players AS p ON a.id = p.event');
						
						// Selects current transaction.
						$query->where('p.id = ' . $item_number);
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
						
						$pparams = json_decode($results2->params);
						$prepay = json_decode($result->params)->prepay;
						if($prepay === '')
						{
							$prepay = $pparams->prepay;
						}
							
							
						if(json_decode($result->params)->paypal_global == 0)
						{	$pCurrency = $pparams->paypal_currency;
							$pEmail = $pparams->paypal_email;
						}
						else
						{
							$pCurrency = json_decode($result->params)->paypal_currency;
							$pEmail = json_decode($result->params)->paypal_email;
						}
						$pAmount = json_decode($result->params)->cost_prepay;
						
						
						if(isset($result->id) && $pAmount == $paypalAmount && strcmp(JRequest::getVar('business'), $pEmail) == 0 && strcmp(JRequest::getVar('mc_currency'), $pCurrency) == 0)
						{
							// Process payment
							
							// Gets data to update
							$query	= $db->getQuery(true);
							$fields = $db->quoteName('status') . ' = 4';
							
							// Sets the conditions of which event and which player to update
							$conditions = array($db->quoteName('id') . ' = ' . $item_number);
							
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

			} 
			
			else if (stripos($res, "INVALID") !== false)
			{
				//Response contains INVALID - reject notification

				
				die('INVALID');
			}
		}
		fclose($fp);  // Close the file
	}
			
?>