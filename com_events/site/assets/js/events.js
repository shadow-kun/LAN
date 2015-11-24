
	
// register an attendee to an event
function addTeam()
{
	var details = {};
	jQuery("#details :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
	details["title"] = document.getElementById('title').value;
	details["body"] = document.getElementById('body').value;
    console.log(details);
	
	jQuery.ajax({
		url:'index.php?option=com_events&controller=team&format=raw&tmpl=component&type=addteam',
		type:'POST', 
		data:details,
		dataType:'JSON',
		success:function(data)
		{
			window.location.href = data.redirect;
		}
    });
}
// Stuff for getting internet data
	var localdata = {};
	var invocation = new XMLHttpRequest();
	var url = 'http://192.168.0.251/vawesome/ip.php';
	var invocationHistoryText;
	
	function handler(evtXHR)
	{
		if (invocation.readyState == 4)
		{
				if (invocation.status == 200)
				{
					var response = invocation.responseXML;
					var invocationHistory = response.getElementsByTagName('machine').item(0).firstChild.data;
					//invocationHistory = document.createTextNode(invocationHistory);
					
					var localdata = invocationHistory;
					
					jQuery.ajax({
						url:'index.php?option=com_events&controller=internet&format=raw&tmpl=component&view=addmachine&machine="' + invocationHistory + '"',
						type:'POST', 
						data:localdata,
						dataType:'text',
						success:function(data)
						{
							jQuery("#details").replaceWith(data.html);
						}
					});
				}
				else
					alert("Errors Occured");
		}
	}	
// register an attendee to an event
function addInternetToken()
{
	localdata = '';
			
	if(invocation)
	{    
		invocation.open('GET', url, true);
		invocation.onreadystatechange = handler;
		invocation.send(); 
	}
	else
	{
		invocationHistoryText = "No Invocation TookPlace At All";
		var textNode = document.createTextNode(invocationHistoryText);
		var textDiv = document.getElementById("details");
		textDiv.appendChild(textNode);
	}
	
}

// register an attendee to an event
function checkinUser(id)
{
	var attendeeInfo = {};
	jQuery("#sources :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=checkin&format=raw&tmpl=component&id=' + id,
		type:'POST', 
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#details").replaceWith(data.html);
		}
    });
}

// register an attendee to an event
function checkinUserPayment(id)
{
	var attendeeInfo = {};
	jQuery("#sources :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=payment&format=raw&tmpl=component&id=' + id + '&type=cash',
		type:'POST', 
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#details").replaceWith(data.html);
			jQuery("#payments").replaceWith(data.payments);
		}
    });
}

function checkinUserPaymentCheckin(id)
{
	var attendeeInfo = {};
	jQuery("#sources :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=payment&format=raw&tmpl=component&id=' + id + '&type=cash',
		type:'POST', 
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#payments").replaceWith(data.payments);
		}
    });
	
	checkinUser(id);
}

// register an attendee to an event
function checkinSearchEntry()
{
	var barcode = document.getElementById('checkin_barcode').value;
	var id = document.getElementById('checkin_registration_id').value;
	var username = document.getElementById('checkin_username').value;
	var eventid = document.getElementById('eventid').value;
	
	var attendeeInfo = {};
	jQuery("#sources :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=checkin&format=raw&tmpl=component&search=search&barcode=' + barcode + '&id=' + id + '&username=' + username + '&eventid=' + eventid,
		type:'POST', 
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#details").replaceWith(data.html);
			jQuery("#payments").replaceWith(data.payments);
		}
    });
}

// register an attendee to an event
function confirmEventUser()
{
	var eventid = document.getElementById('eventid').value;
	var attendeeInfo = {};
	jQuery("#bookForm :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=confirmation&format=raw&view=event&tmpl=component&id=' + eventid,
		type:'POST', 
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#details").replaceWith(data.html);
		}
    });
}

function orderStoreNew()
{
	var storeid = document.getElementById('storeid').value;
	var attendeeInfo = {};
	jQuery("#details :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
	
	jQuery.ajax({
		url:'index.php?option=com_events&controller=order&format=raw&tmpl=component&view=store&type=new&id=' + storeid,
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#details").replaceWith(data.html);
			jQuery("#buttons").replaceWith(data.buttons);
		}
    });
}

function orderStatusChange(orderid)
{
	var ordervalue = document.getElementById('order' + orderid).value;
	jQuery.ajax({
		url:'index.php?option=com_events&controller=order&format=raw&tmpl=component&view=adminstore&type=update&id=' + orderid + '&status=' + ordervalue,
		type:'POST',
		data:ordervalue,
		dataType:'JSON',
		success:function(data)
		{
			
		}
    });
}

function registerEventUser()
{
	var eventid = document.getElementById('eventid').value;
	var attendeeInfo = {};
	
	jQuery("#bookForm :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=register&format=raw&tmpl=component&view=event&id=' + eventid,
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
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
			jQuery("#details").replaceWith(data.html);
			jQuery("#buttons").replaceWith(data.buttons);
		}
    });
}

// unregister an attendee from an event
function registerCompetitionUser()
{
	var competitionid = document.getElementById('competitionid').value;
	var attendeeInfo = {};
	jQuery("#bookForm :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=register&format=raw&tmpl=component&view=competition&type=user&id=' + competitionid,
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#buttons").replaceWith(data.buttons);
			jQuery("#details").replaceWith(data.html);
		}
    });
}

function registerCompetitionTeam()
{
	var competitionid = document.getElementById('competitionid').value;
	var teamid = document.getElementById('registerTeamID').value;
	var attendeeInfo = {};
	jQuery("#bookForm :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=register&format=raw&tmpl=component&view=competition&type=team&id=' + competitionid + '&team=' + teamid,
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#buttons").replaceWith(data.buttons);
			jQuery("#details").replaceWith(data.html);
		}
    });
}

function showCompetitionEntrants()
{
	var competitionid = document.getElementById('competitionid').value;
	var attendeeInfo = {};
	jQuery("#bookForm :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
	
	jQuery.ajax({
		url:'index.php?option=com_events&controller=competition&format=raw&tmpl=component&type=showentrants&id=' + competitionid,
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#details").replaceWith(data.html);
			jQuery("#buttons").replaceWith(data.buttons);
		}
    });
}


function showOptionTeamLeader()
{
	var team = document.getElementById('teamid').value;
	var attendeeInfo = {};
	jQuery("#bookForm :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=team&format=raw&tmpl=component&type=showteamleader&id=' + team,
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#details").replaceWith(data.html);
			jQuery("#buttons").replaceWith(data.buttons);
		}
    });
}

function showOptionTeamDetails()
{
	var team = document.getElementById('teamid').value;
	var attendeeInfo = {};
	jQuery("#bookForm :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=team&format=raw&tmpl=component&type=showteamdetails&id=' + team,
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#details").replaceWith(data.html);
			jQuery("#buttons").replaceWith(data.buttons);
		}
    });
}

function showOptionTeamDelete()
{
	var team = document.getElementById('teamid').value;
	var attendeeInfo = {};
	jQuery("#bookForm :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=team&format=raw&tmpl=component&type=showteamdelete&id=' + team,
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#details").replaceWith(data.html);
			jQuery("#buttons").replaceWith(data.buttons);
		}
    });
}

function unregisterTeamMember()
{
	var team = document.getElementById('teamid').value;
	var attendeeInfo = {};
	jQuery("#bookForm :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=unregister&format=raw&tmpl=component&view=team&id=' + team,
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
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
		url:'index.php?option=com_events&controller=unregister&view=event&format=raw&tmpl=component&id=' + eventid,
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#details").replaceWith(data.html);
		}
    });
}

// unregister an attendee from an event
function unregisterCompetitionUser()
{
	var competitionid = document.getElementById('competitionid').value;
	var attendeeInfo = {};
	jQuery("#bookForm :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=unregister&format=raw&tmpl=component&view=competition&type=user&id=' + competitionid,
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#buttons").replaceWith(data.buttons);
			jQuery("#details").replaceWith(data.html);
		}
    });
}

function unregisterCompetitionTeam()
{
	var competitionid = document.getElementById('competitionid').value;
	var teamid = document.getElementById('unregisterTeamID').value;
	var attendeeInfo = {};
	jQuery("#bookForm :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=unregister&format=raw&tmpl=component&view=competition&type=team&id=' + competitionid + '&team=' + teamid,
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#buttons").replaceWith(data.buttons);
			jQuery("#details").replaceWith(data.html);
		}
    });
}

function updateOptionTeamLeader()
{
	var team = document.getElementById('teamid').value;
	var user = document.getElementById('teamleader').value;
	var attendeeInfo = {};
	jQuery("#bookForm :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=team&format=raw&tmpl=component&type=updateteamleader&id=' + team + '&user=' + user, 
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#details").replaceWith(data.html);
			jQuery("#buttons").replaceWith(data.buttons);
		}
    });
}

function deleteTeam()
{
	var team = document.getElementById('teamid').value;
	var attendeeInfo = {};
	jQuery("#bookForm :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
    
	jQuery.ajax({
		url:'index.php?option=com_events&controller=delete&format=raw&tmpl=component&type=team&id=' + team, 
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			window.location.replace(data.html);
			//jQuery("#details").replaceWith(data.html);
			//jQuery("#buttons").replaceWith(data.buttons);
		}
    });
}