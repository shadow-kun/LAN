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

<form action="<?php echo JRoute::_('index.php?option=com_lan&view=events');?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<?php foreach ($this->items as $i => $item) :
				$item->max_ordering = 0;
				$ordering	= ($listOrder == 'a.ordering'); 
				$canCreate	= $user->authorise('core.create',		'com_lan.category.' . $item->category_id);
				$canEdit	= $user->authorise('core.edit',			'com_lan.event.' . $item->id);
				$canCheckin = $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $user->get('id') || $item-> checked_out == 0;
				$canChange	= $user->authorise('core.edit.state',	'com_lan.event.' . $item->id) && $canCheckin;
			?>
			<div class="media well well-small span12">
				<div class="media-body">
					<!--<a class="pull-right" href="<?php echo JRoute::_('index.php?option=com_lan&view=event&id=' . $item->id); ?>">
						<img src="http://www.gravatar.com/avatar/<?php echo md5(strtolower(trim($this->profile->email))); ?>?s=150" />
					</a>-->
					<h2><a href="<?php echo JRoute::_('index.php?option=com_lan&view=event&id=' . $item->id); ?>"><?php echo $this->escape($item->title); ?></a></h2>
					<h4><?php echo date('jS', strtotime($this->escape($item->event_start_time))); ?> - <?php echo date('jS F Y', strtotime($this->escape($item->event_end_time))); ?></h4>
					<?php if(isset($item->params->location))
					{ 
						echo '<h4>' . JText::_('COM_LAN_EVENTS_LIST_LOCATION') . ': ' . $this->escape($item->params->location) . '</h4>';
					}?>
					<p><strong><?php echo JText::_('COM_LAN_EVENTS_LIST_PLAYERS'); ?></strong> - <?php echo $this->escape($item->players_current); ?> / <?php echo $this->escape($item->players_confirmed); ?> / <?php echo $this->escape($item->players_max); ?><br />
					<strong><?php echo JText::_('COM_LAN_EVENTS_LIST_PREPAID'); ?></strong> - <?php echo $this->escape($item->players_prepaid); ?> / <?php echo $this->escape($item->players_prepay); ?></p>
					<?php 
						$tokens = explode('<hr id="system-readmore" />',$item->body);
						echo $tokens[0];
					?>
				</div>
			</div>
			<div class="clr"></div>
		<?php endforeach; ?>
	</div>
</form>