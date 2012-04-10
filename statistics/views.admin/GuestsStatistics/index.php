<?php new AjaxContentView('Guests Statistics') ?>

<table class="content float_left w200 mr10">
	<tr><td>Mobile</td><td class="align_right">{=$mobile['mobile']}</td></tr>
	<tr><td>Others</td><td class="align_right">{=$mobile['others']}</td></tr>
</table>

<div class="content float_left w600 mr10">
	<div class="align_right">
		<? HHtml::select($scriptnames,array(
			'onchange'=>"S.redirect('".HHtml::url('/guestsStatistics')."?scriptname='+\$(this).find(':selected').text());",
			'selectedText'=>$scriptname
		)) ?>
	</div>
	<table>
	{f $most_requests as $req}
		<tr><td>{$req['resource']}</td><td class="align_right">{=$req['count']}</td></tr>
	{/f}
	</table>
</div>