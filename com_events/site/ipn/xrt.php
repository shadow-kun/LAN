<?php define('_JEXEC', 1);
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
	$query->where('a.transaction_id = "6WP62498CN6382614"');
					
	// Runs query
	$result = $db->setQuery($query)->loadObject();
	$db->query();
	$query	= $db->getQuery(true);
	
	//var_dump($result);
	$pCurrency = '';
	$pEmail = '';
	
	// Sets Variable for payment
		if(isset($_POST['item_number']) == true)
		{
			//$item_number = JRequest::getVar('item_number');
			$item_number = intval(substr(JRequest::getVar('item_number'), 2));
			
		}
		else if (isset($_POST['item_number1']) == true)
		{
			//$item_number = JRequest::getVar('item_number1');
			$item_number = intval(substr(JRequest::getVar('item_number1'), 2));
		}
		else 
		{
			
			$item_number = intval(substr('E-21', 2));
			//$item_number = 51685;
		}
		
		echo $item_number;
		
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
			$paypalAmount = 40.0;
		}
		
		// Check that payment_amount/payment_currency are correct
			$query	= $db->getQuery(true);
			$query->select('a.id AS id, a.params AS params');
			$query->from('#__events_events AS a');
			
			$query->select('p.user AS user, p.status AS status');
			$query->join('LEFT', '#__events_players AS p ON a.id = p.event');
			
			// Selects current transaction.
			$query->where('p.id = ' . $item_number);
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();
			
			$event = $result->id;
			$user = $result->user;
			
	
	
	if(json_decode($result->params)->paypal_global == 0)
	{
			$query	= $db->getQuery(true);
		$query->select('a.params');
		$query->from('#__extensions AS a');
		
		// Selects current user.
		$query->where('name = "com_events"');
						
		// Runs query
		$results2 = $db->setQuery($query)->loadObject();
		$db->query();
		
		$pparams = json_decode($results2->params);
		$pCurrency = $pparams->paypal_currency;
		$pEmail = $pparams->paypal_email;
	}
	else
	{
		$pCurrency = json_decode($result->params)->paypal_currency;
		$pEmail = json_decode($result->params)->paypal_email;
	}
	$pAmount = json_decode($result->params)->cost_prepay;
	
	
	//if($prepay == 2)
							{
								
								include('../models/default.php');  
								include('../models/event.php');  
								
								$model = new EventsModelsEvent();
								{
									//$model->sendTicket(43, 261);
									//$model->sendTicket(1,515);
								}
								
							}
	?>