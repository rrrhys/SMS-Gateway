<h1 id=""><?=$title?></h1>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Billing Period</th>
			<th>Phone Number</th>
			<th>SMS Text</th>
			<th>Credits Used</th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?foreach($billing_summary['details'] as $line_item){?>
		<tr>
			<td><?=$line_item['period_start']?> to <?=$line_item['period_end']?></td>
			<td><?=$line_item['phone']?></td>
			<td><?=$line_item['message_text']?></td>
			<td><?=$line_item['credit_used']?></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>

		<?}?>	
	</tbody>

	
</table>
<!--
<?=json_encode($billing_summary)?> -->

<br />Page rendered in {elapsed_time} seconds</p>
