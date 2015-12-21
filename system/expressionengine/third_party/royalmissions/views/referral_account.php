<?php 
	if ($total_count == 0) {
		echo "No information available.";
	}else{
		?>
<div> 
<h3><?php
	if($this->input->get('method') == "referral_account_paid")
	{
		echo "Paid Commissions for ".$data['0']['initiator_name'];
	}
	elseif ($this->input->get('method') == "referral_account_unpaid")
	{
		echo "Unpaid Commissions for ".$data['0']['initiator_name'];
	}
	else
	{
		echo $data['0']['initiator_name']."'s Profile - <a href=\"/".BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=profile_edit'.AMP.'id='.$data['0']['initiator_id']."\">Edit</a>";
	}
	?></h3> 
    <div> 
		<?php 
		$this->table->set_template($cp_pad_table_template); // $cp_table_template ?
		if($this->input->get('method') == "referral_account") {		
			$this->table->set_heading(
			    array('data' => 'Royal ID', 'style' => 'width: 7%;'),
			    array('data' => 'Initiator ID', 'style' => 'width: 10%;'),
			    array('data' => 'Referred ID', 'style' => 'width: 15%;'),
			    array('data' => 'Percentage', 'style' => 'width: 10%;'),
			    array('data' => 'Created Date', 'style' => 'width: 17%;'),
			    array('data' => 'Subscription', 'style' => 'width:10%;'),
			    array('data' => 'Amount', 'style' => 'width: 10%;'),
			    array('data' => 'Paid?', 'style' => 'width: 10%;'),
			    array('data' => '', 'style' => 'width:16%')
			);
		} else {
			$this->table->set_heading(
			    array('data' => 'Royal ID', 'style' => 'width: 7%;'),
			    array('data' => 'Initiator ID', 'style' => 'width: 10%;'),
			    array('data' => 'Referred ID', 'style' => 'width: 15%;'),
			    array('data' => 'Percentage', 'style' => 'width: 10%;'),
			    array('data' => 'Created Date', 'style' => 'width: 17%;'),
			    array('data' => 'Subscription', 'style' => 'width:10%;'),
			    array('data' => 'Amount', 'style' => 'width: 10%;'),
			    array('data' => '', 'style' => 'width:16%')
			);
		}
		$total_amount = 0.00;
		foreach ($data as $subscription) {		
			// prep options dropdown
			$options = '<select class="sub_options">';
			
			$options .= '<option value="" selected="selected">Select an option</option>';
			
			$options .= '<option value="/'.BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_edit'.AMP.'id='.$subscription['royal_id'].'">Edit</option>';
			$options .= '<option value="/'.BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=delete_referral'.AMP.'id='.$subscription['royal_id'].'">Delete</option>';
			
			$options .= '</optgroup></select>';
			if($this->input->get('method') == "referral_account") {
				$this->table->add_row(
							$subscription['royal_id'],
							$subscription['initiator_id'],
							$subscription['referred_id'],
							$subscription['percentage'],
							($subscription['created_date'] == '0000-00-00') ? '' : date('F j, Y', strtotime($subscription['created_date'])),
							$subscription['brr_plan_name'],
							$subscription['amount'],
							$subscription['paid'],
							$options
						);
			} else {
				$this->table->add_row(
							$subscription['royal_id'],
							$subscription['initiator_id'],
							$subscription['referred_id'],
							$subscription['percentage'],
							($subscription['created_date'] == '0000-00-00') ? '' : date('F j, Y', strtotime($subscription['created_date'])),
							$subscription['brr_plan_name'],
							$subscription['amount'],
							$options
						);
			}
			$amount = ltrim($subscription['amount'], "$");
		
			$total_amount += $amount;
		}
		echo $this->table->generate();
		echo $this->table->clear();
		
		$this->table->set_template($cp_pad_table_template);
		$this->table->set_heading(
		    array('data' => 'Total', 'style' => 'width: 69%;'),
		    array('data' => 'Amount', 'style' => 'width: 31%;')
		);
		if($this->input->get('method') == "referral_account_paid")
		{
			$this->table->add_row(
				'Total Amount paid',
				"$".money_format('%(#10n', $total_amount)
			);
		}
		elseif ($this->input->get('method') == "referral_account_unpaid")
		{
			$this->table->add_row(
				'Total Amount due',
				"$".money_format('%(#10n', $total_amount)
			);
		}
		else
		{
			$this->table->add_row(
				'Total Amount of Referrral Dues',
				"$".money_format('%(#10n', $total_amount)
			);
			
			echo $this->table->generate();
			echo $this->table->clear();
			
			$this->table->set_template($cp_pad_table_template);
			
			$this->table->set_heading(
		    	array('data' => 'Profile', 'style' => 'width: 50%;'),
		    	array('data' => '', 'style' => 'width: 50%;')
		    );
		    $i = 0;
			foreach ($data as $row) {
				if($i <1) {
					$this->table->add_row(
						"Profile ID",
						$row['profile_id']    
			        );
			        if($row['taxform_2014'] == 1)
			        {
						$this->table->add_row(
							"Filled out tax form for 2014?",
							"Yes"
				        );
			        }
			        elseif ($row['taxform_2014'] == NULL)
			        {
			        }
			        elseif ($row['taxform_2014'] == 0)
			        {
						$this->table->add_row(
							"Filled out tax form for 2014?",
							"No"
				        );
			        }
			        if($row['taxform_2015'] == 1)
			        {
						$this->table->add_row(
							"Filled out tax form for 2015?",
							"Yes"
				        );
			        }
			        elseif ($row['taxform_2015'] == NULL)
			        {
			        }
			        elseif ($row['taxform_2015'] == 0)
			        {
						$this->table->add_row(
							"Filled out tax form for 2015?",
							"No"
				        );
			        }
			        if($row['taxform_2016'] == 1)
			        {
						$this->table->add_row(
							"Filled out tax form for 2016?",
							"Yes"
				        );
			        }
			        elseif ($row['taxform_2016'] == NULL)
			        {
			        }
			        elseif ($row['taxform_2016'] == 0)
			        {
						$this->table->add_row(
							"Filled out tax form for 2016?",
							"No"
				        );
			        }
			        if($row['taxform_2017'] == 1)
			        {
						$this->table->add_row(
							"Filled out tax form for 2017?",
							"Yes"
				        );
			        }
			        elseif ($row['taxform_2017'] == NULL)
			        {
			        }
			        elseif ($row['taxform_2017'] == 0)
			        {
						$this->table->add_row(
							"Filled out tax form for 2017?",
							"No"
				        );
			        }
			        if($row['taxform_2018'] == 1)
			        {
						$this->table->add_row(
							"Filled out tax form for 2018?",
							"Yes"
				        );
			        }
			        elseif ($row['taxform_2018'] == NULL)
			        {
			        }
			        elseif ($row['taxform_2018'] == 0)
			        {
						$this->table->add_row(
							"Filled out tax form for 2018?",
							"No"
				        );
			        }
			        if($row['taxform_2019'] == 1)
			        {
						$this->table->add_row(
							"Filled out tax form for 2019?",
							"Yes"
				        );
			        }
			        elseif ($row['taxform_2019'] == NULL)
			        {
			        }
			        elseif ($row['taxform_2019'] == 0)
			        {
						$this->table->add_row(
							"Filled out tax form for 2019?",
							"No"
				        );
			        }
			        if($row['taxform_2020'] == 1)
			        {
						$this->table->add_row(
							"Filled out tax form for 2020?",
							"Yes"
				        );
			        }
			        elseif ($row['taxform_2020'] == NULL)
			        {
			        }
			        elseif ($row['taxform_2020'] == 0)
			        {
						$this->table->add_row(
							"Filled out tax form for 2020?",
							"No"
				        );
			        }
					$this->table->add_row(
						"Screen Name",
			            $row['zoo']    
			        );
					if($row['active'] == 1) {
						$this->table->add_row(
							"Active?",
				            "Yes"
				        );
				    }
				    else {
					    $this->table->add_row(
							"Active?",
				            "No"
				        );
				    }
		        }
		        $i++;
		    }   
		}
		echo $this->table->generate();
		echo $this->table->clear();
		?>
    </div>
</div>
<?php } ?>