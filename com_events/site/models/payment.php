<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	* @package 		LAN
	* @subpackage 	com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/

	jimport('joomla.application.component.model');

	/**
	* Message model.
	*
	* @package LAN
	* @subpackage com_lan
	* @since 1.0
	*/
	class EventsModelsPayment extends EventsModelsDefault
	{
		/**
		 * Model context string.
		 *
		 * @var        string
		 */
		  function __construct()
		{
			parent::__construct();
		}
		
		public function storePayment($item_number, $amount, $paymentMethod, $user)
		{
			// ************ Logs the new payment *************
			
			// Runs query
			$db = $this->getDb();
			$query = $db->getQuery(true);
			
			// Sets JSON Params data
			$params = $db->quote(json_encode(array('payment_method' => $paymentMethod, 'payment_status' => 'Completed', 'transaction_user' => JFactory::getUser()->id)));

			// Sets columns
			$colums = array('id', 'created_time', 'userEventID', 'transaction_id', 'amount', 'currency', 'params', 'user');

			// Sets values
			$values = array('NULL', 'NULL', $item_number, 'NULL', $amount, 'NULL', $params, $user);

			// Prepare Insert Query $db->quoteName('unconfirmed')
			$query  ->insert($db->quoteName('#__events_payments'))
					->columns($db->quoteName($colums))
					->values(implode(',', $values));
			
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
			
			// Gets data to update
			$query	= $db->getQuery(true);
			$fields = $db->quoteName('status') . ' = 3';
			
			// Sets the conditions of which event and which player to update
			$conditions = array($db->quoteName('id') . ' = ' . $item_number);
			
			// Executes Query
			$query->update($db->quoteName('#__events_players'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
			
			return true;
		}
	}
?>