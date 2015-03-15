
<?php $this->events = $this->model->listEvents(); ?>
<div id="sources">
	<h2><?php echo JText::_('COM_EVENTS_CHECKIN_SOURCE_LABEL', true) ?></h2>
	<?php echo JText::_('COM_EVENTS_CHECKIN_SOURCE_BARCODE_LABEL', true) . ':'; ?><input id="checkin_barcode" name="barcode" ></input><br />
	<?php echo JText::_('COM_EVENTS_CHECKIN_SOURCE_REGISTRATION_ID_LABEL', true) . ':'; ?><input id="checkin_registration_id" name="registration_id" ></input><br />
	<?php echo JText::_('COM_EVENTS_CHECKIN_SOURCE_USERNAME_EVENT_LABEL', true) . ':'; ?><input id="checkin_username" name="username" type="user"></input>
	<select id="eventid" >
		<?php foreach ($this->events as $e => $event) : 
			echo '<option value=' . $event->id . ' >' . $event->title . '</option>';
		endforeach; ?>
	<select>
	<p><a href="javascript:void(0);" onclick="checkinSearchEntry()" class="btn btn-primary button-dd"><?php echo JText::_('COM_EVENTS_CHECKIN_SOURCE_SEARCH_LABEL', true); ?></a></p>
</div>