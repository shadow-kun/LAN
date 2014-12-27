<?php
	// no direct access
	defined( '_JEXEC' ) or die( 'Restricted access' );
	
	//Display partial views
	class EventsViewsEventPhtml extends JViewHTML
	{
		function render()
		{
			$this->params = JComponentHelper::getParams('com_events');
			return parent::render();
		}
	}
?>