<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	
	/**
	 * @package		LAN
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	jimport( 'joomla.access.access' );
	
	jimport('joomla.application.component.helper');
	 
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
?>

<form action="<?php echo JRoute::_('index.php?option=com_events&view=store&layout=payments'); ?>"
	method="post" name="adminForm" id="store-form" class="form-validate">	
	<div class="span5 well">
		
	</div>
	<?php echo JHtml::_( 'form.token' ); ?>
	
	<input type="hidden" name="option" value="com_events" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="view" value="checkin" />
</form>