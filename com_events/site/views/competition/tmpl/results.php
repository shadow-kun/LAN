<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	 
	$layout = JRequest::getVar('useraction');
	$result = JRequest::getVar('result');
?>
<form action="<?php echo JRoute::_('index.php?option=com_events&view=competition&id='.(int) $this->competition->id); ?>"
	method="post" name="adminForm" id="event-register-form" class="form-validate">
	<?php	 
		switch($layout)
		{
			case 'register':
				if($result == 'success')
				{
					echo EventsHelpersView::load('competition','_result-register-success','phtml'); 
				}
				else
				{
					echo EventsHelpersView::load('competition','_result-register-failure','phtml'); 
				}
				break;
			case 'unregister':
				if($result == 'success')
				{
					echo EventsHelpersView::load('competition','_result-unregister-success','phtml'); 
				}
				else
				{
					echo EventsHelpersView::load('competition','_result-unregister-failure','phtml'); 
				}
				break;
			default:
				break;
		}
	?>
</form>