<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		LAN
	 * @subpackage	COM_EVENTS
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	jimport('joomla.application.component.helper');
	
	//JHtml::addIncludePath(JPATH_COMPONENT.'helpers/html');
	//JHtml::stylesheet('com_events/admin.css', null, true);
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
	
	//$listOrder	= $this->escape($this->state->get('list.ordering'));
	//$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_events&view=events');?>"	method="post" name="adminForm" id="events-form" class="form-validate">
	<div class="row-fluid">
		<?php $player_events = array();
			foreach($this->userEvents as $ue => $event) :
				array_push($player_events, intval($event->event));
			endforeach;
			// If invite only event and invited allowed to be seen, else skip event.
			foreach ($this->events as $e => $event) : if($event->published > 0 || ($event->published == -1 && in_array(intval($event->id), $player_events))) : 
				$event->max_ordering = 0;
				$ordering	= ($listOrder == 'a.ordering'); 
				$canCreate	= $this->user->authorise('core.create',		'com_events.category.' . $event->category_id);
				$canEdit	= $this->user->authorise('core.edit',		'com_events.event.' . $event->id);
				$canCheckin = $this->user->authorise('core.manage',		'com_checkin') || /*$event->checked_out == $user->get('id') ||*/ $event-> checked_out == 0;
				$canChange	= $this->user->authorise('core.edit.state',	'com_events.event.' . $event->id) && $canCheckin;
				$event->params = (object) json_decode($event->params);
			?>
			<div class="media well well-small">
				<div class="media-body">
					
					<?php if(!empty($event->params->event_image)) : ?>
						<div>
							<img src="<?php echo $event->params->event_image; ?>" style="max-width:200px; float:right;" />
						</div>
					<?php endif;?>
					<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=event&id=' . $event->id); ?>"><?php echo $this->escape($event->title); ?></a></h2>
					<h4><?php echo date('jS', strtotime($this->escape($event->event_start_time))); ?> - <?php echo date('jS F Y', strtotime($this->escape($event->event_end_time))); ?></h4>
					<?php if(!empty($event->params->location))
					{ 
						echo '<p><strong>' . JText::_('COM_EVENTS_EVENTS_LIST_LOCATION') . ': </strong>' . $this->escape($event->params->location) . '</p>';
					}?>
					<p><strong><?php echo JText::_('COM_EVENTS_EVENTS_LIST_PLAYERS'); ?></strong> - <?php echo $this->escape($event->players_current); ?> / <?php echo $this->escape($event->players_confirmed); ?> / <?php echo $this->escape($event->players_max); ?><br />
					<strong><?php echo JText::_('COM_EVENTS_EVENTS_LIST_PREPAID'); ?></strong> - <?php echo $this->escape($event->players_prepaid); ?> / <?php echo $this->escape($event->players_prepay); ?></p>
					<?php 
						$tokens = explode('<hr id="system-readmore" />',$event->body);
						echo $tokens[0];
					?>
				</div>
			</div>
			<div class="clr"></div>
		<?php endif; endforeach; ?>
	</div>
	<?php echo JHtml::_( 'form.token' ); ?>
</form>