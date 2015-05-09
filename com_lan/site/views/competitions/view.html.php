<?php
	// No direct access to this file
	defined('_JEXEC') or die('Restricted access');
	 
	// import Joomla view library
	jimport('joomla.application.component.view');
	 
	/**
	 * HTML View class for the HelloWorld Component
	 */
	class LANViewCompetitions extends JViewLegacy
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
		
		// Overwriting JView display method
		function display($tpl = null) 
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

			// Display the view
			parent::display($tpl);
		}
	}
?>