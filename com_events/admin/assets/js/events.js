function filterOrders()
{
	var details = {};
	jQuery("#details :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
	details["startDate"] = document.getElementById('jform_params_filter_start_date').value;
	details["endDate"] = document.getElementById('jform_params_filter_end_date').value;
	console.log(details);
	
	jQuery.ajax({
		url:'index.php?option=com_events&controller=store&format=raw&tmpl=component&type=filterorder',
		type:'POST', 
		data:details,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#form-order-details").replaceWith(data.html);
		}
    });
}