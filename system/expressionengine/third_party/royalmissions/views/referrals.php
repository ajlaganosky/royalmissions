<?php 

if ($total_count == 0) {
	
	?>
	<div class="tableFooter">
		<p class="notice"><?='No Referrals'?></p>
		<p><a href="<?=BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_edit'?>"><?='Create Referral'?></a></p>
	</div>
<?php 

}
else
{

$this->table->set_template($cp_pad_table_template);
$this->table->set_heading(
    array('data' => 'ID', 'style' => 'width:5%;'),
    array('data' => 'Initiator', 'style' => 'width:10%;'),
    array('data' => 'Referred ID', 'style' => 'width:10%;'),
    array('data' => 'Percent of Commission', 'style' => 'width:10%;'),
    array('data' => 'Created Date', 'style' => 'width:15%;'),
    array('data' => 'Amount', 'style' => 'width:10%;'),
    array('data' => 'Subscription', 'style' => 'width:10%;'),
    array('data' => 'Paid', 'style' => 'width:10%;'),
    array('data' => 'Edit', 'style' => 'width:5%;'),
    array('data' => 'Delete', 'style' => 'width:5%;')
);


foreach ($data as $item)
{
	$this->table->add_row($item['royal_id'], $item['initiator_id'] , $item['referred_id'], $item['percentage'], $item['created_date'], $item['amount'], $item['brr_plan_name'], $item['paid'], $item['edit'], $item['delete']);
}

echo $this->table->generate();


$this->table->clear();

}
$this->table->set_template($cp_pad_table_template);
$this->table->set_heading(
   'Users'
);

foreach ($data2 as $item)
{
	$this->table->add_row(
		$item['profile_link']
	);
}

echo $this->table->generate();


$this->table->clear();
?>