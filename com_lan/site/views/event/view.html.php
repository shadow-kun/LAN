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
			// Initialise variables.
			$this->item				= $this->get('Item');
			$this->players			= $this->get('Players');
			$this->currentPlayer	= $this->get('CurrentPlayer');
			$this->form				= $this->get('Form');
			$this->state			= $this->get('State');
			
			$message = JRequest::getVar('id',NULL,'POST');
			$app = JFactory::getApplication();
			
			// Checks to see if a specific value has been passed via POST
			if(null !== JRequest::getVar('register',NULL,'POST'))
			{
				if(!isset($this->currentPlayer->status))
				{
					
					$app = JFactory::getApplication('site');
					$waitlist = $this->item->params->get('waitlist_override');
			
					if((isset($waitlist) && $waitlist == 1 || (!(isset($waitlist)) && $app->getParams('com_lan')->get('waitlist') == 1)) || ($this->item->players_current < $this->item->players_max))
					{
						$this->get('SavePlayerEvent');
						$this->get('SendTicket');
					}
				}
				$app->redirect(JRoute::_('index.php?option=' . $this->option . '&view=event&id=' . $this->item->id , false));
			}
			elseif(null !== JRequest::getVar('confirm',NULL,'POST'))
			{
				if((int) $this->currentPlayer->status == 1)
				{
					$this->get('ConfirmPlayerEvent');
				}
				$app->redirect(JRoute::_('index.php?option=' . $this->option . '&view=event&id=' . $this->item->id , false));
			}
			elseif(null !== JRequest::getVar('cancel',NULL,'POST'))
			{
				$app->redirect(JRoute::_('index.php?option=' . $this->option . '&view=event&id=' . $this->item->id , false));
			}
			elseif(null !== JRequest::getVar('confirmDelete',NULL,'POST'))
			{
				if(isset($this->currentPlayer->status))
				{
					if($this->currentPlayer->status == 'Confirmed')
					{	
						$this->get('UnconfirmPlayerEvent');
					}
					$this->get('DeletePlayerEvent');
				}
				
				$app->redirect(JRoute::_('index.php?option=' . $this->option . '&view=event&id=' . $this->item->id , false));
			}
	
			// Check for errors.
			if (count($errors = $this->get('Errors'))) 
			{
				JError::raiseError(500, implode("\n", $errors));
				return false;
			}

			
			$pathway = $app->getPathway();
			$pathway->addItem($this->escape($this->item->title), JRoute::_('index.php?option=com_lan&view=event&id=' . $this->item->id));
			

			parent::display();
		}
		
		protected function addToolbar()
		{
			JToolbarHelper::confirm('lan.confirm', 'COM_LAN_EVENT_REGISTRATION_CONFIRM_TRUE');
		}
	}
?>