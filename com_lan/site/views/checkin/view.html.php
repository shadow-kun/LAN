<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	* @package LAN
	* @subpackage com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/

	jimport('joomla.application.component.view');
	jimport('joomla.access.access');

	/**
	* Event view.
	*
	* @package LAN
	* @subpackage com_event
	* @since 0.0
	*/
	class LANViewCheckin extends JViewLegacy
	{
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

		protected $groupCheckedIn;
		/**
		* Prepare and display the Event view.
		*
		* @return void
		* @since 0.0
		*/
		
		public function display($tpl = null)
		{
			$layout = JRequest::getVar('layout');
			// Initialise variables.
			
			$this->item				= $this->get('Item');
			
			$this->groupCheckedIn 	= $this->get('CheckedInGroup');
			$this->form				= $this->get('Form');
			$this->state			= $this->get('State');
			
			$app = JFactory::getApplication();
			
			// Check for errors.
			if (count($errors = $this->get('Errors'))) 
			{
				JError::raiseError(500, implode("\n", $errors));
				return false;
			}
			
			parent::display();
		}
		
		protected function addToolbar()
		{
		
		}
	}
?>