<?php
	/**** Test Harness to verify that I can connect to the $db from an outside source. ****/
	define('_JEXEC', 1);

	if (file_exists(__DIR__ . '../../../defines.php'))
	{
		include_once __DIR__ . '../../../defines.php';
	}

	if (!defined('_JDEFINES'))
	{
		define('JPATH_BASE', __DIR__);
		require_once JPATH_BASE . '../../../includes/defines.php';
	}
	
	require_once JPATH_BASE . 'includes/framework.php';

	// Mark afterLoad in the profiler.
	JDEBUG ? $_PROFILER->mark('afterLoad') : null;

	// Instantiate the application.
	$app = JFactory::getApplication('site');
					
	// Gets database connection
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);

			//Sets JSON Params data
			$params = $db->quote(json_encode(array($db->quote('payment_status') => $db->quote(JRequest::getVar('payment_status')))));


			// Sets columns
			$colums = array('id', 'created_time', 'userEventID', 'amount', 'currency', 'params');

			// Sets values
			$values = array('NULL', 'NULL', $db->quote(JRequest::getVar('item_number')), $db->quote(JRequest::getVar('mc_gross')), $db->quote(JRequest::getVar('mc_currency')), $params);

			// Prepare Insert Query $db->quoteName('unconfirmed')
			$query  ->insert($db->quoteName('#__lan_payments'))
					->columns($db->quoteName($colums))
					->values(implode(',', $values));
					
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
	
	
	