<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	jimport('joomla.application.component.helper');
	
	?>

<form action="<?php echo JRoute::_('index.php?option=com_events&view=store&id='.(int) $this->store->id); ?>"
	method="post" name="adminForm" id="store-form" class="form-validate">
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=store&id=' . $this->store->id); ?>"><?php echo $this->escape($this->store->title); ?></a></h2>
					
	<div class="form-horizontal">
		<div id="details">	
			<div class="row-fluid">
				<div class="span8">
					<?php $tokens = explode('<hr id="system-readmore" />', $this->store->body);
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
	</div>
	
	<input type="hidden" id="storeid" name="store" value="<?php echo $this->store->id; ?>" />
	<input id="task" type="hidden" name="task" value="" />
</form>