<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	* @package LAN
	* @subpackage com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/

	jimport('joomla.application.component.view');

	/**
	* Event view.
	*
	* @package LAN
	* @subpackage com_event
	* @since 0.0
	*/
	class LANViewEvent extends JViewLegacy
	{
		/**
		* @var JObject The data for the record being displayed.
		* @since 0.0
		*/
		protected $item;

		/**
		* @var JForm The form object for this record.
		* @since 0.0
		*/
		protected $form;

		/**
		* @var JObject The model state.
		* @since 0.0
		*/
		protected $state;

		/**
		* Prepare and display the Event view.
		*
		* @return void
		* @since 0.0
		*/
		
		public function display($tpl = null)
		{
			// Intialiase variables.
			$this->item		= $this->get('Item');
			$this->form		= $this->get('Form');
			$this->state	= $this->get('State');

			// Check for errors.
			if (count($errors = $this->get('Errors'))) 
			{
				JError::raiseError(500, implode("\n", $errors));
				return false;
			}

			parent::display();
		}
	}
?>