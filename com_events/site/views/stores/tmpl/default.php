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

<form action="<?php echo JRoute::_('index.php?option=com_events&view=stores');?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<?php foreach ($this->stores as $c => $store) :
				$store->max_ordering = 0;
				$ordering	= ($listOrder == 'a.ordering'); 
				$canCreate	= $this->user->authorise('core.create',		'com_events.category.' . $store->category_id);
				$canEdit	= $this->user->authorise('core.edit',		'com_events.store.' . $store->id);
				$canCheckin = $this->user->authorise('core.manage',		'com_checkin') || $store->checked_out == $user->get('id') || $store-> checked_out == 0;
				$canChange	= $this->user->authorise('core.edit.state',	'com_events.store.' . $store->id) && $canCheckin;
				
				$store->params = json_decode($store->params, false);
			?>
			<div class="media well well-small">
				<div class="media-body">
					<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=store&id=' . $store->id); ?>"><?php echo $this->escape($store->title); ?></a></h2>
					<?php 
						$tokens = explode('<hr id="system-readmore" />',$store->body);
						echo $tokens[0];
					?>
				</div>
				
			</div>
			<div class="clr"></div>
		<?php endforeach; ?>
	</div>
	<?php echo JHtml::_( 'form.token' ); ?>
</form>