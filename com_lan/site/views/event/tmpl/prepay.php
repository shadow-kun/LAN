<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	 
	 //JHtml::addIncludePath(JPATH_COMPONENT.'helpers/html');
	JHtml::stylesheet('com_lan/admin.css', null, true);
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
	
	$app = JFactory::getApplication();
	$pathway = $app->getPathway();
	$pathway->addItem(JText::_('COM_LAN_EVENT_SUMMARY_PREPAY', true), JRoute::_('index.php?option=com_lan&view=event&layout=prepay&id=' . $this->item->id));
?>

<script>
	Joomla.submitbutton = function(task)
	{
		var form = document.id(event-register-form);
		if (task == 'cancel' || document.formvalidator.isValid(form)) {
			Joomla.submitform(task, form);
		}
	}
</script>
				

	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_lan&view=event&id=' . $this->item->id); ?>"><?php echo $this->escape($this->item->title); ?></a> <strong> - </strong> 
		<a href="<?php echo JRoute::_('index.php?option=com_lan&view=event&layout=prepay&id=' . $this->item->id); ?>"><?php echo JText::_('COM_LAN_EVENTS_PREPAY_TITLE', true) ?></a></h2>
	<?php if(($this->currentPlayer->status <= 2)) : ?>	
		<div class="row-fluid">
			
			<?php if(isset($this->item->params['prepay'])) 
			// Gets global setting if not explicitly set.
			{
				$prepay = $this->item->params['prepay'];
			} 
			else
			{
				// Set as disabled for now.
				$prepay = 0;
			}
			
			if($prepay == 2)
			{			
				echo '<p>' . JText::_('COM_LAN_EVENT_REGISTRATION_PREPAY_MANDATORY_MSG') . '</p>';
			}
			elseif ($prepay == 1)
			{
				echo '<p>' . JText::_('COM_LAN_EVENT_REGISTRATION_PREPAY_OPTIONAL_MSG') . '</p>';
			}
			else 
			{
				echo '<p>' . JText::_('COM_LAN_EVENT_REGISTRATION_PREPAY_DISABLED_MSG') . '</p>';
			}?>
			</br >
		</div>
		<?php if($prepay > 0) : ?>
			<div class="row-fluid">
				<table class="list table table-striped">
					<thead>
						<tr>
							<th width="20%">
								<?php echo JText::_('COM_LAN_EVENT_REGISTRATION_PREPAY_TABLE_PAYMENT_TYPE_TITLE'); ?>
							</th>
							<th>
								<?php echo JText::_('COM_LAN_EVENT_REGISTRATION_PREPAY_TABLE_PAYMENT_DESC_TITLE'); ?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php echo $this->loadTemplate('paypal'); ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	<?php else :
		echo '<div class="row-fluid"><p>' . JText::_('COM_LAN_EVENT_REGISTRATION_PREPAY_PAID_MSG') . '</p></div>';
	endif; ?>
		
		
	<input type="hidden" name="option" value="com_lan" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="view" value="event" />
    <?php echo JHtml::_('form.token'); ?>
