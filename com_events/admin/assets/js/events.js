
function filterOrders()
{
	var details = {};
	jQuery("#details :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
	details["startDate"] = document.getElementById('jform_params_filter_start_date').value;
	details["endDate"] = document.getElementById('jform_params_filter_end_date').value;
	details["status"] = document.getElementById('jform_params_filter_status').value;
	details["id"] = document.getElementById('storeid').value;
	//window.location.href = 'index.php?option=com_events&view=store&layout=edit&id=' + details["id"] + '&startdate=' + details["startDate"] + '&enddate=' + details["endDate"] + 
	//	'&status=' + details["status"];
		
	jQuery.ajax({
		url:'index.php?option=com_events&controller=store&format=html&tmpl=component&id=' + id,
		type:'POST', 
		data:details,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#form-orders-details").replaceWith(data.html);
		}
    });
}