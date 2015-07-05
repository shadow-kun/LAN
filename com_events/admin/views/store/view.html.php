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
	class EventsViewStore extends JViewLegacy
	{
		/**
		* @var JObject The data for the record being displayed.
		* @since 0.0
		*/
		protected $store;
		
		/**
		* @var JObject The data for the record being displayed.
		* @since 1.0
		*/
		protected $groups;
		
		/**
		* @var JObject The data for the record being displayed.
		* @since 0.0
		*/
		protected $orders;
		
		/**
		* @var JObject The data for the record being displayed.
		* @since 0.0
		*/
		protected $ordersSummary;
		
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
			$this->store			= $this->get('Item');
			$this->groups			= $this->get('Groups');
			$this->form				= $this->get('Form');
			$this->state			= $this->get('State');
			$this->orders			= $this->get('Orders');
			$this->ordersSummary	= $this->get('OrdersSummary');
			$this->payments			= $this->get('Payments');

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
			$isNew	= ($this->store->id == 0);
			$checkedOut	= !($this->store->checked_out == 0 || $this->store->checked_out == $user->get('id'));
			$canDo	= EventsHelper::getActions();

			JToolBarHelper::title(
				JText::_(
					'COM_EVENTS_SHOP_STORE_'.
					($checkedOut
						? 'VIEW_STORE'
						: ($isNew ? 'ADD_STORE' : 'EDIT_STORE')).'_TITLE',
					'store'
				)
			);

			// If not checked out, can save the item.
			if (!$checkedOut && $canDo->get('core.edit')) 
			{
				JToolBarHelper::apply('store.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('store.save', 'JTOOLBAR_SAVE');
				JToolBarHelper::custom('store.save2new', 'save-new.png', null, 'JTOOLBAR_SAVE_AND_NEW', false);
			}

			// If an existing item, can save to a copy.
			if (!$isNew && $canDo->get('core.create')) 
			{
				JToolBarHelper::custom('store.save2copy', 'save-copy.png', null, 'JTOOLBAR_SAVE_AS_COPY', false);
			}
			if (empty($this->item->id)) 
			{
				JToolBarHelper::cancel('store.cancel', 'JTOOLBAR_CANCEL');
			} 
			else 
			{
				JToolBarHelper::cancel('store.cancel', 'JTOOLBAR_CLOSE');
			}
		}
	}