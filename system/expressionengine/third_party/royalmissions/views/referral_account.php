<?php 
	if ($total_count == 0) {
		echo "No information available.";
	}else{
		?>
<div> 
<h3><?php
	//States whether paid/unpaid for Initiator
	/**
		* How to Improve...
	**/
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
		//Initiator's Name and link to edit their profile
		echo $data['0']['initiator_name']."'s Profile - <a href=\"/".BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=profile_edit'.AMP.'id='.$data['0']['initiator_id']."\">Edit</a>";
	}
	?></h3> 
    <div> 
		<?php 
			//use table template
		$this->table->set_template($cp_pad_table_template); // $cp_table_template ?
		if($this->input->get('method') == "referral_account") { //View All
			//set table headers
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
		}
		else //if not referral_account method... View Unpaid or View Paid
		{
			//set table headers
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
		//Set $total_amount as a float
		$total_amount = 0.00;
		//Loop through $data to display
		foreach ($data as $subscription) {		
			// prep options dropdown selection for each $subscription
			$options = '<select class="sub_options">';
			
			$options .= '<option value="" selected="selected">Select an option</option>';
			
			$options .= '<option value="/'.BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_edit'.AMP.'id='.$subscription['royal_id'].'">Edit</option>';
			$options .= '<option value="/'.BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=delete_referral'.AMP.'id='.$subscription['royal_id'].'">Delete</option>';
			
			$options .= '</select>';
			if($this->input->get('method') == "referral_account") { //All results
				$this->table->add_row(
							$subscription['royal_id'], //RoyalMissions ID
							$subscription['initiator_id'], //Member ID of Initiator
							$subscription['referred_id'], //Member ID of Referred
							$subscription['percentage'], //Percentage assigned
							($subscription['created_date'] == '0000-00-00') ? '' : date('F j, Y', strtotime($subscription['created_date'])), //Force date to be 'December 15, 2015' format
							$subscription['brr_plan_name'], //Plan Name @@TODO should be linked to Membrr
							$subscription['amount'], //Amount for referral commission
							$subscription['paid'], //Paid status in 'Yes'/'No'
							$options //Set above to Edit/Delete
						);
			}
			else //Paid or Unpaid
			{
				$this->table->add_row(
							$subscription['royal_id'], //RoyalMissions ID
							$subscription['initiator_id'], //Member ID of Initiator
							$subscription['referred_id'], //Member ID of Referred
							$subscription['percentage'], //Percentage assigned
							($subscription['created_date'] == '0000-00-00') ? '' : date('F j, Y', strtotime($subscription['created_date'])), //Force date to be 'December 15, 2015' format
							$subscription['brr_plan_name'], //Plan Name @@TODO should be linked to Membrr
							$subscription['amount'], //Amount for referral commission
							$options //Set above to Edit/Delete
						);
			}
			//remove $ so addition can occur for tracking total of all commission
			$amount = ltrim($subscription['amount'], "$");
			//add each $amount result
			$total_amount += $amount;
		}
		//generate table
		echo $this->table->generate();
		//clear elements at bottom of table
		echo $this->table->clear();
		
		//table template
		$this->table->set_template($cp_pad_table_template);
		//set table headers
		$this->table->set_heading(
		    array('data' => 'Total', 'style' => 'width: 69%;'),
		    array('data' => 'Amount', 'style' => 'width: 31%;')
		);
		//Show total amount of all paid and unpaid
		//Not currently setup
		if($this->input->get('method') == "referral_account_paid")
		{
			$this->table->add_row(
				'Total Amount paid',
				//money_format for 2 digits after decimal
				"$".money_format('%(#10n', $total_amount)
			);
		}
		//Show total amount of unpaid
		elseif ($this->input->get('method') == "referral_account_unpaid")
		{
			$this->table->add_row(
				'Total Amount due',
				//money_format for 2 digits after decimal
				"$".money_format('%(#10n', $total_amount)
			);
		}
		//Show total amount of all paid
		else
		{
			$this->table->add_row(
				'Total Amount of Referrral Dues',
				//money_format for 2 digits after decimal
				"$".money_format('%(#10n', $total_amount)
			);
			
			//generate table
			echo $this->table->generate();
			//clear elements at bottom of table
			echo $this->table->clear();
			
			//table template
			$this->table->set_template($cp_pad_table_template);
			//set table headers
			$this->table->set_heading(
		    	array('data' => 'Profile', 'style' => 'width: 50%;'),
		    	array('data' => '', 'style' => 'width: 50%;')
		    );
		    $i = 0;
		    //@@TODO fix to be used with updated db scheme
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
		//generate table
		echo $this->table->generate();
		//clear elements at bottom of table
		echo $this->table->clear();
		?>
    </div>
</div>
<?php } ?>