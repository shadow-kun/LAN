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
	//$listOrder	= $this->escape($this->state->get('list.ordering'));
	//$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_events&view=competitions');?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<?php foreach ($this->competitions as $c => $competition) :
				$competition->max_ordering = 0;
				$ordering	= ($listOrder == 'a.ordering'); 
				$canCreate	= $this->user->authorise('core.create',		'com_lan.category.' . $competition->category_id);
				$canEdit	= $this->user->authorise('core.edit',			'com_lan.competition.' . $competition->id);
				$canCheckin = $this->user->authorise('core.manage',		'com_checkin') || $competition->checked_out == $user->get('id') || $competition-> checked_out == 0;
				$canChange	= $this->user->authorise('core.edit.state',	'com_lan.competition.' . $competition->id) && $canCheckin;
				
				$competition->params = json_decode($competition->params, false);
			?>
			<div class="media well well-small">
				<div class="media-body">
					<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=competition&id=' . $competition->id); ?>"><?php echo $this->escape($competition->title); ?></a></h2>
					<?php 
						$tokens = explode('<hr id="system-readmore" />',$item->body);
						echo $tokens[0];
					?>
					<?php if($competition->params->competition_team == 0) : ?>
						<p><strong><?php echo JText::_('COM_EVENTS_COMPETITIONS_CURRENT_USERS_LABEL'); ?></strong> - 
						<?php echo $this->model->getCompetitionPlayers($competition->id); ?>
						<?php if(isset($competition->params->competition_limit)) : 
							echo ' / ' . (int) $competition->params->competition_limit;
						endif; ?>
						</p>
					<?php else : ?>
						<p><strong><?php echo JText::_('COM_EVENTS_COMPETITIONS_CURRENT_TEAMS_LABEL'); ?></strong> - 
						<?php echo $this->model->getCompetitionTeams($competition->id); ?>
						<?php if(isset($competition->params->competition_limit)) : 
							echo ' / ' . (int) $competition->params->competition_limit;
						endif; ?>
						</p>
					<?php endif; ?>
				</div>
				
			</div>
			<div class="clr"></div>
		<?php endforeach; ?>
	</div>
	<?php echo JHtml::_( 'form.token' ); ?>
</form>