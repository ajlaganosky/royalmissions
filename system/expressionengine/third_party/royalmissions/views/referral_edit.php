<?=form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=save_referral');?>

<?php 

foreach ($data as $key=>$arr)
{

?> 

<div class="editAccordion open<?php if ($arr['show']==false) echo ' collapsed';?>"> 
<h3<?php if ($arr['show']==false) echo ' class="collapsed"';?>><?=lang("$key")?></h3> 
    <div<?php if ($arr['show']==false) echo ' style="display: none;"';?>> 

<?php 
$this->table->set_template($cp_pad_table_template);
foreach ($arr as $key => $val)
{
	if ($key!='show')
	{
		$this->table->add_row(
			array('data' => lang($key, $key), 'style' => 'width:50%;'), $val
		);
	}
}
echo $this->table->generate();
$this->table->clear();
?>
</div>
</div>
<?php
}
?>


<p><?=form_submit('submit', 'Save', 'class="submit"')?></p>

<?php if ($this->input->post('id')!=''):?>

<p>&nbsp;</p>

<p><a class="rule_delete_warning" href="<?=BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=delete_referral'.AMP.'id='.$this->input->post('id')?>"><?=lang('delete_referral')?></a> </p>

<?php endif;?>

<p>&nbsp;</p>

<?php
form_close();