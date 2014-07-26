<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	jimport('joomla.application.component.helper');
	
	//JHtml::addIncludePath(JPATH_COMPONENT.'helpers/html');
	JHtml::stylesheet('com_lan/admin.css', null, true);
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
	
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_lan&view=competition&id='.(int) $this->item->id); ?>"
	method="post" name="adminForm" id="event-form" class="form-validate">
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_lan&view=competition&id=' . $this->item->id); ?>"><?php echo $this->escape($this->item->title); ?></a></h2>
					
	<div class="form-horizontal">
		<p><strong><?php echo JText::_('COM_LAN_COMPETITION_START_LABEL', true); ?></strong> - 
			<?php echo date('g:i A l, jS F Y', strtotime($this->escape($this->item->competition_start))); ?></br >
		<strong><?php echo JText::_('COM_LAN_COMPETITION_END_LABEL', true); ?></strong> - 
			<?php echo date('g:i A l, jS F Y', strtotime($this->escape($this->item->competition_end))); ?></p>
			
		<!-- Need to have a restrict access cause here -->
		
		<?php if(JFactory::getUser()->guest) { 
			echo '<p><a href="' . JRoute::_('index.php?option=com_users&view=login') . '">';
			echo JText::_('COM_LAN_COMPETITION_SUMMARY_LOGIN', true) . '</a>';
		} else { 
			if(isset($this->currentPlayer->status))
			{
				echo '<p><a href="' .  JRoute::_('index.php?option=com_lan&view=competition&layout=unregister&id=' . $this->item->id) . '">';
				echo JText::_('COM_LAN_COMPETITION_SUMMARY_UNREGISTER', true) . '</a> ';
			}
			else
			{
				echo '<p><a href="' .  JRoute::_('index.php?option=com_lan&view=competition&layout=register&id=' . $this->item->id) . '">';
				echo JText::_('COM_LAN_COMPETITION_SUMMARY_REGISTER', true) . '</a>';
			}
		} ?></p>
		
		<div class="row-fluid">
			<div class="span8">
				<?php $tokens = explode('<hr id="system-readmore" />',$this->item->body);
					if(count($tokens) === 1)
					{
						echo $tokens[0];
					}
					else
					{
						echo $tokens[1];
					}
				?>
			</div>
		</div>
		
		<h3><?php echo JText::_('COM_LAN_COMPETITION_SUBHEADING_PLAYERS_LIST', true) ?></h3>
			<table class="list table table-striped">
				<thead>
					<tr>
						<th width="1%">
							<?php echo JHTML::_('grid.sort', 'COM_LAN_COMPETITION_TABLE_COMPETITION_PLAYERS_ORDER', 'id', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo JHTML::_('grid.sort', 'COM_LAN_COMPETITION_TABLE_COMPETITION_PLAYERS_PLAYER', 'p.username', $listDirn, $listOrder); ?>
						</th>
						<th width="20%">
							<?php echo JHTML::_('grid.sort', 'COM_LAN_COMPETITION_TABLE_COMPETITION_PLAYERS_STATUS', 'status', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="15">
							<?php //echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<?php foreach ($this->players as $p => $player) :
						$player->max_ordering = 0;
						$ordering	= ($listOrder == 'id');
					?>
					<tr class="row<?php echo $p % 2; ?>">
						<td class="left">
							<?php echo (int) $p + 1; ?>
						</td>
						<td class="left">
							<?php echo $this->escape($player->username); ?>
						</td>
						<td class="center">
							<?php /*echo $this->escape($player->status); */ ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
	</div>
</form>