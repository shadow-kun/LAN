<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	
	header("Location: " . JRoute::_('index.php?option=com_events&view=team&id=' . $this->team));
	die();
?>