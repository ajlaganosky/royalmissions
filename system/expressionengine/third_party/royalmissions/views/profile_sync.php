<?php 

if ($total_count == 0) {
	
	?>
	<div class="tableFooter">
		<p class="notice"><?='No Sync'?></p>
	</div>
<?php 

}
else
{ ?>
	<div class="tableFooter">
		<p class="notice"><?='Sync\'d'?></p>
	</div>
<?php
}
?>
<meta http-equiv="refresh" content="1; url=<?=BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=index'?>" />