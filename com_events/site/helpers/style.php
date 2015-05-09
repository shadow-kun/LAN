<?php
    // no direct access
    defined('_JEXEC') or die('Restricted access');
     
    class EventsHelpersStyle
    {
		function load()
		{
			$document = JFactory::getDocument();

			//stylesheets
			//$document->addStylesheet(JURI::base().'components/com_events/assets/css/style.css');
			 
			//javascripts
			$document->addScript(JURI::base().'components/com_events/assets/js/events.js');
		}
    }
	
?>