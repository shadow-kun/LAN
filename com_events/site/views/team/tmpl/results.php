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

<form action="<?php echo JRoute::_('index.php?option=com_events&view=team&id=' . $this->team->id); ?>"
	method="post" name="adminForm" id="teams-register-form" class="form-validate">
	<?php	 
		switch($layout)
		{
			case 'details':
				if($result == 'success')
				{
					echo EventsHelpersView::load('team','_result-details-success','phtml'); 
				}
				else
				{
					echo EventsHelpersView::load('team','_result-details-failure','phtml'); 
				}
				break;
			case 'delete':
				if($result == 'success')
				{
					echo EventsHelpersView::load('team','_result-delete-success','phtml'); 
				}
				else
				{
					echo EventsHelpersView::load('team','_result-delete-failure','phtml'); 
				}
				break;
			case 'leader':
				if($result == 'success')
				{
					echo EventsHelpersView::load('team','_result-leader-success','phtml'); 
				}
				else
				{
					echo EventsHelpersView::load('team','_result-leader-failure','phtml'); 
				}
				break;
			default:
				break;
		}
	?>
</form>