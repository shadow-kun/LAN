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

<form action="<?php echo JRoute::_('index.php?option=com_lan&view=teams');?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<div><button name="selection" class="btn btn-primary" value="team_new"><?php echo JText::_('COM_LAN_TEAMS_SUMMARY_NEW_LABEL');?></button></div>
		<?php foreach ($this->items as $i => $item) :
				$item->max_ordering = 0;
				$ordering	= ($listOrder == 'a.ordering'); 
				$canCreate	= $user->authorise('core.create',		'com_lan.category.' . $item->category_id);
				$canEdit	= $user->authorise('core.edit',			'com_lan.team.' . $item->id);
				$canCheckin = $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $user->get('id') || $item-> checked_out == 0;
				$canChange	= $user->authorise('core.edit.state',	'com_lan.team.' . $item->id) && $canCheckin;
			?>
			<div class="media well well-small span12">
				<div class="media-body">
					<h2><a href="<?php echo JRoute::_('index.php?option=com_lan&view=team&id=' . $item->id); ?>"><?php echo $this->escape($item->title); ?></a></h2>
					<?php 
						$tokens = explode('<hr id="system-readmore" />',$item->body);
						echo $tokens[0];
					?>
				</div>
			</div>
			<div class="clr"></div>
		<?php endforeach; ?>
	</div>
	<input type="hidden" name="task" value="team" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>