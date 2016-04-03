
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
			
			location.reload();
		}
		else
		{
			alert("Errors Occured");
		}
	}
}	

// register an attendee to an event
function addInternetToken()
{
	
	localdata = '';
	
	jQuery.ajax({
		url:'index.php?option=com_events&controller=internet&format=raw&tmpl=component&view=inprogress',
		type:'POST', 
		data:localdata,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#details").replaceWith(data.html);
		}
    });
			
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

function registerCompetitionTeam()
{
	var competitionid = document.getElementById('competitionid').value;
	var teamid = document.getElementById('registerTeamID').value;
	
	window.location.assign(window.location.href + "/register/confirmteam/" + teamid);
	
	
}

function unregisterCompetitionTeam()
{
	var competitionid = document.getElementById('competitionid').value;
	var teamid = document.getElementById('unregisterTeamID').value;
	
	window.location.assign(window.location.href + "/unregister/confirmteam/" + teamid);
}

function updateOptionTeamLeader()
{
	
	var user = document.getElementById('teamleader').value;
	
	window.location.assign(window.location.href + "/update/" + user);
	//url:'index.php?option=com_events&controller=edit&tmpl=component&view=team&layout=leader&action=update&id=' + team + '&user=' + user, 
	
}


