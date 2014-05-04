<?php
    // no direct access
    defined('_JEXEC') or die('Restricted access');
     
    class LANHelpersStyle
    {
		function load()
		{
			$document = JFactory::getDocument();
     
			//stylesheets
			$document->addStylesheet(JURI::base().'components/com_lan/assets/css/style.css');
     
			//javascripts
			$document->addScript(JURI::base().'components/com_lan/assets/js/lan.js');
		}
    }
?>