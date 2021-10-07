{{* Frame template for displaying admission data *}}
{{* Used by /modules/registration_admission/aufnahme_daten_zeigen.php *}}
{{* Elpidio Latorilla 2004-06-07 *}}

<table width="100%" cellspacing="0" cellpadding="0">
  <tbody>
 <div id="hello-win" class="x-hidden"></div>
    <tr>
      <td>{{include file="registration_admission/admit_tabs.tpl"}}</td>
    </tr>

    <tr>
      <td>
			<table cellspacing="0" cellpadding="0" width=800>
			<tbody>
				<tr valign="top">
					<td>{{include file="registration_admission/admit_form.tpl"}}</td>
					<td>{{$sAdmitOptions}}</td>
				</tr>
			</tbody>
			</table>
	  </td>
    </tr>

	<tr>
      <td valign="top">
	  	{{$sAdmitBottomControls}} {{$pbBottomClose}}
	</td>
    </tr>

    <tr>
      <td>
	  	&nbsp;
		<br>
	  	{{$sAdmitLink}}
		<br>
		{{$sSearchLink}}
		<br>
		{{$sArchiveLink}}
                <br>
		<input type="button" id="show-btn" value="Update Dates" />
               
	</td>
    </tr>

  </tbody>
</table>
