<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		LAN
	* @subpackage	com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/

	JHtml::_('behavior.tooltip');
	
	$user		= JFactory::getUser();
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_lan&view=competitions');?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<?php foreach ($this->items as $i => $item) :
				$item->max_ordering = 0;
				$ordering	= ($listOrder == 'a.ordering'); 
				$canCreate	= $user->authorise('core.create',		'com_lan.category.' . $item->category_id);
				$canEdit	= $user->authorise('core.edit',			'com_lan.competition.' . $item->id);
				$canCheckin = $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $user->get('id') || $item-> checked_out == 0;
				$canChange	= $user->authorise('core.edit.state',	'com_lan.competition.' . $item->id) && $canCheckin;
			?>
			<div class="media well well-small span12">
				<div class="media-body">
					<h2><a href="<?php echo JRoute::_('index.php?option=com_lan&view=competition&id=' . $item->id); ?>"><?php echo $this->escape($item->title); ?></a></h2>
					<?php 
						$tokens = explode('<hr id="system-readmore" />',$item->body);
						echo $tokens[0];
					?>
					<?php if(json_decode($item->params)->competition_team === 1) : ?>
						<p><strong><?php echo JText::_('COM_LAN_COMPETITIONS_LIST_PLAYERS_CURRENT_LABEL'); ?></strong> - 
						<?php if(isset(json_decode($item->params)->competition_limit)) : 
							echo ' / ' . (int) json_decode($item->params)->competition_limit;
						endif; ?>
						</p>
					<?php else : ?>
						<p><strong><?php echo JText::_('COM_LAN_COMPETITIONS_LIST_TEAMS_CURRENT_LABEL'); ?></strong> - 
						<?php if(isset(json_decode($item->params)->competition_limit)) : 
							echo ' / ' . (int) json_decode($item->params)->competition_limit;
						endif; ?>
						<?php echo json_decode($item->params)->competition_team; ?>
						</p>
					<?php endif; ?>
				</div>
			</div>
			<div class="clr"></div>
		<?php endforeach; ?>
	</div>
</form>