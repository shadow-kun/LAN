<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	* @package 		Events Party!
	* @subpackage 	com_events
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
	class EventsViewItem extends JViewLegacy
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
			$canDo	= EventsHelper::getActions();

			JToolBarHelper::title(
				JText::_(
					'COM_EVENTS_SHOP_ITEM_'.
					($checkedOut
						? 'VIEW_ITEM'
						: ($isNew ? 'ADD_ITEM' : 'EDIT_ITEM')).'_TITLE',
					'item'
				)
			);

			// If not checked out, can save the item.
			if (!$checkedOut && $canDo->get('core.edit')) 
			{
				JToolBarHelper::apply('item.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('item.save', 'JTOOLBAR_SAVE');
				JToolBarHelper::custom('item.save2new', 'save-new.png', null, 'JTOOLBAR_SAVE_AND_NEW', false);
			}

			// If an existing item, can save to a copy.
			if (!$isNew && $canDo->get('core.create')) 
			{
				JToolBarHelper::custom('item.save2copy', 'save-copy.png', null, 'JTOOLBAR_SAVE_AS_COPY', false);
			}
			if (empty($this->item->id)) 
			{
				JToolBarHelper::cancel('item.cancel', 'JTOOLBAR_CANCEL');
			} 
			else 
			{
				JToolBarHelper::cancel('item.cancel', 'JTOOLBAR_CLOSE');
			}
		}
	}