<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		Events Party!
	* @subpackage	com_events
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/

   /**
	* LAN Party Events HTML View
	*
	* @package		LAN
	* @subpackage	com_lan
	*/
	
	jimport('joomla.application.component.view');
	
	class EventsViewCompetitions extends JViewLegacy
	{
		/**
		 * @var		array		The array of the records to display in the list.
		 * @sicne 	0.0
		 */
		 
		protected $items;
		
		/**
		 * @var		JPagination	The pagination object for the list.
		 * @since	0.0
		 */
		 
		protected $pagination;
		
		/**
		 * @var		JObject		The model state.
		 * @since	0.0
		 */
		 
		protected $state;
		
		/**
		 * Prepare and display the Messages view.
		 *
		 * @return	void
		 * @since 	0.0
		 */
		
		public function display($tpl = NULL)
		{
			$this->items		= $this->get('Items');
			$this->pagination	= $this->get('Pagination');
			$this->state		= $this->get('State');
			
			// Check for errors.
			if (count($errors = $this->get('Errors')))
			{
				JError::raiseError(500, implode("\n", $errors));
				return false;
			}
				
			// Add the toodbar and display the view layout.
			$this->addToolbar();
			parent::display();
		}
		
		/**
		 * Add the page title and toolbar.
		 *
		 * @return	void
		 * @since	0.0
		 */
		
		protected function addToolbar()
		{
			// Initialise variables.
			$state	= $this->get('State');
			$canDo	= LANHelper::getActions();
			
			JToolBarHelper::title(JText::_('COM_EVENTS_COMPETITIONS_TITLE'));
			
			if($canDo->get('core.create')) 
			{
				JToolBarHelper::addNew('competition.add');
			}
			
			if($canDo->get('core.edit')) 
			{
				JToolBarHelper::editList('competition.edit');
			}
			
			if($canDo->get('core.edit.state')) 
			{
				JToolBarHelper::publishList('competitions.publish', 'JTOOLBAR_PUBLISH');
				JToolBarHelper::unpublishList('competitions.unpublish', 'JTOOLBAR_UNPUBLISH');
				JToolBarHelper::archiveList('competitions.archive', 'JTOOLBAR_ARCHIVE');
			}
			
			if($state->get('filter.published') == -2 && $canDo->get('core.delete')) 
			{
				JToolBarHelper::deleteList('', 'competitions.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			else if ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('competitions.trash');
			}
			
			if($canDo->get('core.admin')) 
			{
				JToolBarHelper::preferences('com_events');
			}
		}
		
		protected function getSortFields()
		{
			return array(
				'a.ordering'     => JText::_('JGRID_HEADING_ORDERING'),
				'a.state'        => JText::_('JSTATUS'),
				'a.title'        => JText::_('JGLOBAL_TITLE'),
				'category_title' => JText::_('JCATEGORY'),
				'access_level'   => JText::_('JGRID_HEADING_ACCESS'),
				'a.created_by'   => JText::_('JAUTHOR'),
				'language'       => JText::_('JGRID_HEADING_LANGUAGE'),
				'a.created'      => JText::_('JDATE'),
				'a.id'           => JText::_('JGRID_HEADING_ID')
			);
		}
	}
?>