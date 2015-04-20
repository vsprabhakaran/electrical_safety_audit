function addNewObservation(parentID)
{
	var observationCount = parseInt($('input[name=observationCountHidden]').val()) + 1;
	$("#"+parentID).append(
		'<tr class="pure-table-odd">' +
			'<td><p>&nbsp;</p></td>' +
			'<td>'+observationCount+'. <input type="text" name="otherComp_text_'+observationCount+'" id="otherComp_text_'+observationCount+'" style="width:90%" class="otherComp_text"/></td>' +
			'<td><input type="radio" name="otherComp_'+observationCount+'_r" id="otherComp_'+observationCount+'_r" value="Yes"/></td>' +
			'<td><input type="radio" name="otherComp_'+observationCount+'_r" id="otherComp_'+observationCount+'_r" value="No"/></td>' +
			'<td></td>' +
			'<td><input type="text" name="otherComp_'+observationCount+'_d" id="otherComp_'+observationCount+'_d" class="date_picker" readOnly /></td>' +
	'</tr>');
	$("#otherComp_"+observationCount+"_d").datepicker({ autoSize: true,
		dateFormat: "dd/mm/yy",
		beforeShow: function(){    
		$(".ui-datepicker").css('font-size', 12) 
		}	
	});
	$('input[name=observationCountHidden]').val(observationCount);
}
function deleteLastObservation(parentID)
{
	var observationCount = parseInt($('input[name=observationCountHidden]').val());
	if(observationCount > 0)
	{
		$("#"+parentID+" tr:last").remove();
		observationCount--;
		$('input[name=observationCountHidden]').val(observationCount);
	}	
}