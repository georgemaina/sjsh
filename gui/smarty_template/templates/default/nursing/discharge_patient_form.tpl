{{* discharge_patient_form.tpl : Discharge form 2004-06-12 Elpidio Latorilla *}}
{{* Note: never rename the input when redimensioning or repositioning it *}}

<ul>

<div class="prompt">{{$sPrompt}}</div>
    <div class="balance" id="balance">{{$sBalance}}</div>

<form action="{{$thisfile}}" name="discform" method="post" onSubmit="return pruf(this)">

	<table border=0 cellspacing="1">
		<tr>
			<td colspan=2 class="adm_input">
				{{$sBarcodeLabel}} {{$img_source}}
			</td>
            <td{{$LDHasBalance}}></td>
		</tr>
		<tr>
			<td class="adm_item">{{$LDLocation}}:</td>
			<td class="adm_input">{{$sLocation}}</td>
            <td></td>
		</tr>
			<td class="adm_item">{{$LDDate}}:</td>
			<td class="adm_input">
				{{if $released}}
					{{$x_date}}
				{{else}}
					{{$sDateInput}} {{$sDateMiniCalendar}}
				{{/if}}
			</td>
            <td></td>
		</tr>
		<tr>
			<td class="adm_item">{{$LDClockTime}}:</td>
			<td class="adm_input">
				{{if $released}}
					{{$x_time}}
				{{else}}
					{{$sTimeInput}}
				{{/if}}
			</td>
            <td></td>
		</tr>
		<tr>
			<td class="adm_item">{{$LDReleaseType}}:</td>
			<td class="adm_input">
				{{$sDischargeTypes}}
			</td>
            <td></td>
		</tr>
		<tr>
			<td class="adm_item">{{$LDNotes}}:</td>
			<td class="adm_input">
				<textarea rows="5" cols="40" name="info"></textarea>

            </td>
            <td></td>
		</tr>
		<tr>
			<td class="adm_item">{{$LDNurse}}:</td>
			<td class="adm_input">
				{{if $released}}
					{{$encoder}}
				{{else}}
					<input type="text" name="encoder" size=25 maxlength=30 value="{{$encoder}}">
				{{/if}}
			</td>
            <td></td>
		</tr>

	{{if $bShowValidator}}
		<tr>
			<td class="adm_item">{{$pbSubmit}}</td>
			<td class="adm_input">{{$sValidatorCheckBox}} {{$LDYesSure}}</td>
            <td></td>
		</tr>
	{{/if}}

	</table>

	{{$sHiddenInputs}}

</form>

{{$pbCancel}}
{{$pbSummary}}

</ul>
