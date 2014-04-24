<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		LAN
	* @subpackage	com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/

   /**
	* LAN Party Team HTML View
	*
	* @package		LAN
	* @subpackage	com_lan
	*/
	
	jimport('joomla.application.component.view');
	
	class LANViewLAN extends JViewLegacy
	{
		/**
		 * Override the display method for the view
		 * 
		 * @return 	void
		 * @since 	0.0
		 */
		public function display()
		{
			$this->addToolbar();
			parent::display();
		}		
		
		/**$tpl = NULL
		 * Add the page title and toolbar
		 *
		 * @return 	void
		 * @since	0.0
		 */
		
		protected function addToolbar()
		{
			$canDo	= LANHelper::getActions();
			
			// Add the view title
			JToolBarHelper::title(JText::_('COM_LAN_LAN_TITLE'));
			
			// Check if the Options button can be added.
			if ($canDo->get('core.admin'))
			{
				JToolBarHelper::preferences('com_lan');
			}
		}
	}
?>

