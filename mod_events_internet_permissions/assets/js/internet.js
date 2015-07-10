function orderStoreNew()
{
	var attendeeInfo = {};
	jQuery("#details :input").each(function(idx,ele){
		attendeeInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
	});
	
	jQuery.ajax({
		url:'index.php?option=mod_events_internet_permissions&controller=add&format=raw&tmpl=module',
		type:'POST',
		data:attendeeInfo,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#details").replaceWith(data.html);
		}
    });
}