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



<form action="<?php echo JRoute::_('index.php?option=com_events&view=teams'); ?>"
	method="post" name="adminForm" id="teams-register-form" class="form-validate">
	<?php	 
		switch($layout)
		{
			case 'create':
				if($result == 'success')
				{
					echo EventsHelpersView::load('teams','_result-create-success','phtml'); 
				}
				else
				{
					echo EventsHelpersView::load('teams','_result-create-failure','phtml'); 
				}
				break;
			default:
				break;
		}
	?>
</form>