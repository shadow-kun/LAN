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
	class LANViewTeam extends JViewLegacy
	{
		/**
		 * @var		array		The array of the player records to display in the list.
		 * @sicne 	0.0
		 */
		protected $players;
		
		/**
		* @var JObject The data for the record being displayed.
		* @since 0.0
		*/
		protected $currentPlayer;
		
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
			$layout = JRequest::getVar('layout');
			// Initialise variables.
			
			$this->item				= $this->get('Item');
			if($layout == 'qrcode')
			{
				
			}
				$this->players		= $this->get('Players');
				$this->currentPlayer	= $this->get('CurrentPlayer');
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
			JToolbarHelper::confirm('lan.confirm', 'COM_LAN_TEAM_REGISTRATION_CONFIRM_TRUE');
		}
	}
?>