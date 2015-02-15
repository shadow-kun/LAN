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
			$this->players	= $this->get('Players');
			$this->form		= $this->get('Form');
			$this->state	= $this->get('State');
			
			// Check for errors.
			if (count($errors = $this->get('Errors'))) 
			{
				JError::raiseError(500, implode("\n", $errors));
				return false;
			}

			$this->addToolbar();
			parent::display();
		}

		/**
		* Add the page title and toolbar.
		*
		* @return void
		* @since 0.0
		*/
		protected function addToolbar()
		{
			JRequest::setVar('hidemainmenu', true);

			$user	= JFactory::getUser();
			$isNew	= ($this->item->id == 0);
			$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
			$canDo	= LANHelper::getActions();

			JToolBarHelper::title(
				JText::_(
					'COM_LAN_EVENT_'.
					($checkedOut
						? 'VIEW_EVENT'
						: ($isNew ? 'ADD_EVENT' : 'EDIT_EVENT')).'_TITLE',
					'event'
				)
			);

			// If not checked out, can save the item.
			if (!$checkedOut && $canDo->get('core.edit')) 
			{
				JToolBarHelper::apply('event.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('event.save', 'JTOOLBAR_SAVE');
				JToolBarHelper::custom('event.save2new', 'save-new.png', null, 'JTOOLBAR_SAVE_AND_NEW', false);
			}

			// If an existing item, can save to a copy.
			if (!$isNew && $canDo->get('core.create')) 
			{
				JToolBarHelper::custom('event.save2copy', 'save-copy.png', null, 'JTOOLBAR_SAVE_AS_COPY', false);
			}
			if (empty($this->item->id)) 
			{
				JToolBarHelper::cancel('event.cancel', 'JTOOLBAR_CANCEL');
			} 
			else 
			{
				JToolBarHelper::cancel('event.cancel', 'JTOOLBAR_CLOSE');
			}
		}
	}