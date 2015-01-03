
// register an attendee to an event
function confirmEventUser()
{
	var eventid = document.getElementById('eventid').value;
	var attendeeInfo = {};
	jQuery("#bookForm :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=confirmation&format=raw&tmpl=component&id=' + eventid,
		type:'POST', 
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			console.log(data.view);
			jQuery("#details").replaceWith(data.html);
		}
    });
}

// register an attendee to an event
function registerEventUser()
{
	var eventid = document.getElementById('eventid').value;
	var attendeeInfo = {};
	jQuery("#bookForm :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=register&format=raw&tmpl=component&id=' + eventid,
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			console.log(data.view);
			jQuery("#details").replaceWith(data.html);
		}
    });
}

function registerTeamMember()
{
	var team = document.getElementById('teamid').value;
	var attendeeInfo = {};
	jQuery("#bookForm :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=register&format=raw&tmpl=component&view=team&id=' + team,
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			console.log(data.success);
			jQuery("#details").replaceWith(data.html);
			jQuery("#buttons").replaceWith(data.buttons);
		}
    });
}

// unregister an attendee from an event
function unregisterEventUser()
{
	var eventid = document.getElementById('eventid').value;
	var attendeeInfo = {};
	jQuery("#bookForm :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=unregister&format=raw&tmpl=component&id=' + eventid,
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			console.log(data.view);
			jQuery("#details").replaceWith(data.html);
		}
    });
}