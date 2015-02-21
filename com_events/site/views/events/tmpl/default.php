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
		<?php foreach ($this->events as $e => $event) :
				$event->max_ordering = 0;
				$ordering	= ($listOrder == 'a.ordering'); 
				$canCreate	= $this->user->authorise('core.create',		'com_events.category.' . $event->category_id);
				$canEdit	= $this->user->authorise('core.edit',		'com_events.event.' . $event->id);
				$canCheckin = $this->user->authorise('core.manage',		'com_checkin') || /*$event->checked_out == $user->get('id') ||*/ $event-> checked_out == 0;
				$canChange	= $this->user->authorise('core.edit.state',	'com_events.event.' . $event->id) && $canCheckin;
			?>
			<div class="media well well-small">
				<div class="media-body">
					
					<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=event&id=' . $event->id); ?>"><?php echo $this->escape($event->title); ?></a></h2>
					<h4><?php echo date('jS', strtotime($this->escape($event->event_start_time))); ?> - <?php echo date('jS F Y', strtotime($this->escape($event->event_end_time))); ?></h4>
					<?php if(isset($event->params->location))
					{ 
						echo '<h4>' . JText::_('COM_EVENTS_EVENTS_LIST_LOCATION') . ': ' . $this->escape($event->params->location) . '</h4>';
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
		<?php endforeach; ?>
	</div>
	<?php echo JHtml::_( 'form.token' ); ?>
</form>