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

<form action="<?php echo JRoute::_('index.php?option=com_events&view=event&id='.(int) $this->event->id); ?>"
	method="post" name="adminForm" id="event-form" class="form-validate">
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=event&id=' . $this->event->id); ?>"><?php echo $this->escape($this->event->title); ?></a></h2>
					
	<div class="form-horizontal">
		<p><strong><?php echo JText::_('COM_EVENTS_EVENT_SUMMARY_START_TIME_LABEL', true); ?></strong> - 
			<?php echo date('g:i A l, jS F Y', strtotime($this->escape($this->event->event_start_time))); ?><br />
			<strong><?php echo JText::_('COM_EVENTS_EVENT_SUMMARY_END_TIME_LABEL', true); ?></strong> - 
			<?php echo date('g:i A l, jS F Y', strtotime($this->escape($this->event->event_end_time))); ?></p>
			
			<p>
			<?php if(isset(json_decode($this->event->params)->venue)) : ?>
				<strong><?php echo JText::_('COM_EVENTS_EVENT_SUMMARY_VENUE', true); ?></strong> - 
				<?php echo $this->escape(json_decode($this->event->params)->location); ?>
				<?php if(isset(json_decode($this->event->params)->venue)) : 
					echo '<br />';
				endif; ?>
			<?php endif; ?>
			<?php if(isset(json_decode($this->event->params)->location)) : ?>
				<strong><?php echo JText::_('COM_EVENTS_EVENT_SUMMARY_LOCATION', true); ?></strong> - 
				<?php echo $this->escape(json_decode($this->event->params)->location); ?></p>
			<?php endif; ?>
			
		<p><strong><?php echo JText::_('COM_EVENTS_EVENT_LIST_PLAYERS'); ?></strong> - <?php echo $this->escape($this->event->players_current); ?> / <?php echo $this->escape($this->event->players_confirmed); ?> / <?php echo $this->escape($this->event->players_max); ?><br />
			<strong><?php echo JText::_('COM_EVENTS_EVENT_LIST_PREPAID'); ?></strong> - <?php echo $this->escape($this->event->players_prepaid); ?> / <?php echo $this->escape($this->event->players_prepay); ?></p>
		
		<p><?php /*
				include('qrcode.php');
				QRcode::png('http://beta.shadowreaper.net/respawn', JPATH_COMPONENT . '/images/qrcodes/010_merged.png');
				
				include('barcode.php');  
				$im     = imagecreatetruecolor(200, 100);  
				$black  = ImageColorAllocate($im,0x00,0x00,0x00);  
				$white  = ImageColorAllocate($im,0xff,0xff,0xff);  
				imagefilledrectangle($im, 0, 0, 200, 100, $white);  
				$data 	= Barcode::gd($im, $black, 100, 50, 0, "code128", "12345678", 2, 50);

				// Output the image to browser
				header('Content-Type: image/gif');

				imagegif($im, JPATH_COMPONENT . '/images/qrcodes/010_merged.gif');
				imagedestroy($im);
				
				echo '<img src="' . JURI::root() . '/components/COM_EVENTS/images/qrcodes/010_merged.gif' . '">';
				
			// displaying
			echo '<img src="./components/COM_EVENTS/images/qrcodes/010_merged.png' . '" />'; */?><p>
		<!-- Need to have a restrict access cause here -->
		
		<?php if(JFactory::getUser()->guest) { 
			echo '<p><a href="' . JRoute::_('index.php?option=com_users&view=login') . '">';
			echo JText::_('COM_EVENTS_EVENT_SUMMARY_LOGIN', true);
		} else { 
			
			$app = JFactory::getApplication('site');
			$waitlist = $this->event->params->waitlist_override;
			
			if(isset($this->currentPlayer->status))
			{
				echo '<p><a href="' .  JRoute::_('index.php?option=com_events&view=event&layout=unregister&id=' . $this->event->id) . '">';
				echo JText::_('COM_EVENTS_EVENT_SUMMARY_UNREGISTER', true) . '</a> ';
				if($this->event->params->get('prepay') > 0 && $this->currentPlayer->status <= 2) 
				{
					echo '<a href="' .  JRoute::_('index.php?option=com_events&view=event&layout=prepay&id=' . $this->event->id) . '">';
					echo JText::_('COM_EVENTS_EVENT_SUMMARY_PREPAY', true) . '</a>';
				}	
				if($this->event->params->get('confirmations_override') > 0 && $this->currentPlayer->status == 1) 
				{
					echo '<a href="' .  JRoute::_('index.php?option=com_events&view=event&layout=confirm&id=' . $this->event->id) . '">';
					echo JText::_('COM_EVENTS_EVENT_SUMMARY_CONFIRM', true) . '</a>';
				}	 
			}
			else if((isset($waitlist) && $waitlist == 0 || (!(isset($waitlist)) && $app->getParams('com_events')->get('waitlist') == 0)) && ($this->event->players_current >= $this->event->players_max))
			{
				echo '<p>' . JText::_('COM_EVENTS_EVENT_SUMMARY_FULL', true);
			}
			else
			{
				echo '<p><a href="' .  JRoute::_('index.php?option=com_events&view=event&layout=register&id=' . $this->event->id) . '">';
				echo JText::_('COM_EVENTS_EVENT_SUMMARY_REGISTER', true) . '</a>';
			}
		} 
		echo ' <a href="' .  JRoute::_('index.php?option=com_events&view=event&layout=players&id=' . $this->event->id) . '">';
				echo JText::_('COM_EVENTS_EVENT_SUMMARY_PLAYERS', true) . '</a>';
		?></p>
		
		<div class="row-fluid">
			<div class="span8">
				<?php $tokens = explode('<hr id="system-readmore" />',$this->event->body);
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
	</div>
	<?php echo JHtml::_( 'form.token' ); ?>
</form>
