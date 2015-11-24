<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */

	jimport('joomla.database.table');

	/**
	 * Event table.
	 *
	 * @package		Events Party!
	 * @subpackage	com_events
	 * @since		0.0
	 */
class EventsTableStore extends JTable
{
	/**
	 * Constructor.
	 *
	 * @param	JDatabase	$db	A database connector object.
	 *
	 * @return	HelloTableMessage
	 * @since	1.0
	 */
	public function __construct($db)
	{
		parent::__construct('#__events_shop_stores', 'id', $db);
	}

	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param	array		$array	The input array to bind.
	 * @param	string		$ignore	A list of fields to ignore in the binding.
	 *
	 * @return	null|string	null is operation was satisfactory, otherwise returns an error
	 * @see		JTable:bind
	 * @since	0.0
	 */
	public function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Overloaded check method to ensure data integrity.
	 *
	 * @return	boolean	True on success.
	 * @since	0.0
	 */
	public function check()
	{
		// Check for valid name.
		if (trim($this->title) === '') {
			$this->setError(JText::_('COM_EVENTS_ERROR_SHOP_STORES_TITLE'));
			return false;
		}

		return true;
	}

	/**
	 * Overload the store method for the Weblinks table.
	 *
	 * @param	boolean	$updateNulls	Toggle whether null values should be updated.
	 *
	 * @return	boolean	True on success, false on failure.
	 * @since	0.0
	 */
	public function store($updateNulls = false)
	{
		// Initialise variables.
		$date	= JFactory::getDate()->tosql();
		$userId	= JFactory::getUser()->get('id');

		if (empty($this->id)) {
			// New record.
			$this->created_time		= $date;
			$this->created_user_id	= $userId;
		} else {
			// Existing record.
			$this->modified_time	= $date;
			$this->modified_user_id	= $userId;
		}

		// Attempt to store the data.
		return parent::store($updateNulls);
	}
}