<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * RoyalMissions Module Control Panel File
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Module
 * @author		AJ Laganosky
 * @link		https://www.higherinfogroup.com
 */

class Royalmissions_mcp {
	
	public $return_data;
	
	private $_base_url;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
		
		$this->_base_url = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions';
		
        $this->EE->view->cp_page_title = 'RoyalMissions';

		$this->EE->cp->set_right_nav(array(
			'module_home'	=> $this->_base_url,
			// Add more right nav items here.
		));

	}
	
	// ----------------------------------------------------------------

	/**
	 * Index Function
	 *
	 * @return 	void
	 */
	public function index()
	{
		$this->EE->cp->cp_page_title = 'RoyalMissions';
		
		/**
		 * This is the addons home page, add more code here!
		 */
		
		$this->EE->load->library('table');
		$this->sync_no_redirect();
		$vars = array();
        
        $query = $this->EE->db->select('royal_id, royalmissions.initiator_id, referred_id, percentage, created_date, amount, brr_plan_name, paid, screen_name')
				->from('royalmissions')
				->join('royalmissions_user', 'royalmissions_user.initiator_id = royalmissions.initiator_id')
				->order_by('royal_id','asc')
				->get();
				
		if($query==false) show_error('Nothing Exists');

		$vars['total_count'] = $query->num_rows();
		
	
		$i = 0;
        foreach ($query->result_array() as $row)
        {
           $vars['data'][$i]['royal_id'] = $row['royal_id'];
           $vars['data'][$i]['initiator_id'] = "<a href=\"".BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_account'.AMP.'id='.$row['initiator_id']."\" title=\"view account\">".$row['screen_name']."</a>";
           $vars['data'][$i]['referred_id'] = "<a href=\"".BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_account'.AMP.'id='.$row['referred_id']."\" title=\"view account\">".$row['referred_id']."</a>";           
           $vars['data'][$i]['percentage'] = $row['percentage']; 
           $vars['data'][$i]['percentage'] .= '%';
           $vars['data'][$i]['created_date'] = $row['created_date'];    
           $vars['data'][$i]['amount'] = '$';
           $vars['data'][$i]['amount'] .= $row['amount'];
           $vars['data'][$i]['brr_plan_name'] = $row['brr_plan_name'];
           if ($row['paid'] == 0)
           {
            	$vars['data'][$i]['paid'] = 'No';
           }
           else
           {
	            $vars['data'][$i]['paid'] = 'Yes';
	       }
           $vars['data'][$i]['edit'] = "<a href=\"".BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_edit'.AMP.'id='.$row['royal_id']."\" title=\"edit\"><img src=\"".$this->EE->cp->cp_theme_url."images/icon-edit.png\" alt=\"edit\"></a>";
           $vars['data'][$i]['delete'] = "<a href=\"".BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=delete_referral'.AMP.'id='.$row['royal_id']."\" class=\"referral_delete_warning\" title=\"delete\"><img src=\"".$this->EE->cp->cp_theme_url."images/icon-delete.png\" alt=\"delete\"></a>";
           
           $i++;
 			
        }
        
        $js = '
				var draft_target = "";

				$("<div id=\"referral_delete_warning\">Warning of Deleting Referral</div>").dialog({
					autoOpen: false,
					resizable: false,
					title: "Are you sure you want to delete?",
					modal: true,
					position: "center",
					minHeight: "0px", 
					buttons: {
						Cancel: function() {
						$(this).dialog("close");
						},
					"Delete Referral": function() {
						location=draft_target;
					}
					}});
	
				$(".referral_delete_warning").click( function (){
					$("#referral_delete_warning").dialog("open");
					draft_target = $(this).attr("href");
					$(".ui-dialog-buttonpane button:eq(2)").focus();	
					return false;
			});';
        $DQ = $this->EE->db->select('sync_date')
				->from('royalmissions_sync')
				->order_by('sync_id','desc')
				->limit(1)
				->get();
		
		$row9 = $DQ->row(); 
		$SD = $row9->sync_date;
		
		$twoDaysAgo = date('Y-m-d H:i:s', strtotime("-2 days"));
		
		if($SD <= $twoDaysAgo)
		{
	        $js .= '
					var draft_target = "'.BASE.AMP.'method=membrr_sub_sync";
	
					$("<div id=\"referral_delete_warning\">It has been awhile since you have Sync\'d the data for your referrals.</div>").dialog({
						autoOpen: true,
						resizable: false,
						title: "Please Sync Your Data",
						modal: true,
						position: "center",
						minHeight: "0px", 
						buttons: {
							Cancel: function() {
							$(this).dialog("close");
							},
						"Sync Data": function() {
							location=draft_target;
						}
						}});';

		}
			
        $q = $this->EE->db->select('initiator_id, screen_name, zoo_id')
				->from('royalmissions_user')
				->order_by('zoo_id','asc')
				->get();
				
		if($q==false) show_error('Nothing Exists');
		
		$t = 0;
        foreach ($q->result_array() as $row)
        {
           $vars['data2'][$t]['profile_link'] = "<a href=\"".BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_account'.AMP.'id='.$row['initiator_id']."\" title=\"view account\">[".$row['zoo_id']." - ".$row['initiator_id']."] ".$row['screen_name']."</a>";           
           $t++;
        }
                
		$this->EE->javascript->output($js);
	
		$this->EE->cp->set_right_nav(array(
	            	'Create Referral' => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_edit',
	            	'Sync All Data' => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=membrr_sub_sync')
				);
        
    	return $this->EE->load->view('referrals', $vars, TRUE);

	}
	
	function referral_edit()
	{
		$this->EE->load->helper('form');
    	$this->EE->load->library('table');  
    	    	
    	$js = '';
    	    	
		$theme_folder_url = trim($this->EE->config->item('theme_folder_url'), '/').'/third_party/royalmissions/';
        $this->EE->cp->add_to_foot('<link type="text/css" href="'.$theme_folder_url.'multiselect/ui.multiselect.css" rel="stylesheet" />');
        $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$theme_folder_url.'multiselect/plugins/localisation/jquery.localisation-min.js"></script>');
        $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$theme_folder_url.'multiselect/plugins/blockUI/jquery.blockUI.js"></script>');
        $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$theme_folder_url.'multiselect/ui.multiselect.js"></script>');

       	$values = array(
    		'royal_id' => false,
			'initiator_id' => '',	
			'referred_id' => '',
			
			'created_date' => date('Y-m-d H:i:s'),
			'percentage' => '25',
			'amount' => '0.00',

			'brr_plan_id' => 'Select a Plan',
			
			'paid' => 0,			
			'date_paid' => '',
			'paid_check_id' => '',
						
			'referred_id_active' => 1
			
		);
		
		if ($this->EE->input->get('id')!==false)
		{
			$q = $this->EE->db->select()
					->from('royalmissions')
					->where('royal_id', $this->EE->input->get('id'))
					->get();
			if ($q->num_rows()==0)
			{
				show_error('Unauthorized Access');
			}
			
			foreach ($values as $field_name=>$default_field_val)
			{
				if (is_array($default_field_val))
				{
					$values["$field_name"] = ($q->row("$field_name")!='')?unserialize($q->row("$field_name")):array();
				}
				else
				{
					$values["$field_name"] = $q->row("$field_name");
				}
			}
		}
		
		$this->EE->cp->add_js_script(
			array('ui' => array(
				'core', 'datepicker'
			)
		));
		
		//get all plans
        if (!class_exists('Membrr_EE')) {
			require(PATH_THIRD.'membrr/class.membrr_ee.php');
		}
		$this->membrr = new Membrr_EE;
        $plans = $this->membrr->GetPlans();
        if ($plans==false) show_error('Need subscriptions in the store.');
        
        $product_ids_list_items = array();
		$product_ids_list_items[''] = '';
        foreach ($plans as $plan)
        {
            $product_ids_list_items[$plan['id']] = $plan['name'];
        }
        
        //ksort($product_ids_list_items);
        
        $js .= "
        $('#referral_product_ids').multiselect({ droppable: 'none', sortable: 'none' });
        ";
		// Join on member_id and author_id due to the fact that Zoo Visitor does NOT auto populate the member_id in the field until the entry is resaved
		$list_of_members = array();
        $this->EE->db->select('member_id, username, entry_id');
        $this->EE->db->from('members');
        $this->EE->db->join('channel_titles', 'channel_titles.author_id = members.member_id');
        $this->EE->db->where('channel_titles.channel_id', '6');
        $q = $this->EE->db->get();
        foreach ($q->result_array() as $row)
        {
            $list_of_members[$row['member_id']] = '['.$row['entry_id'].' - '.$row['member_id'].'] '.$row['username'];
        }
        
        ksort($list_of_members);
        
        $paid = array(
	      0 => 'No',
	      1 => 'Yes'  
        );

		$data['Referral Editing'] = array();
		$data['Referral Editing']['show'] = true;
		$data['Referral Editing']['Royal ID'] = $values['royal_id'];
		$data['Referral Editing']['Initiator ([Unique Identifier - EEMember] Username)'] = form_dropdown('initiator_id', $list_of_members, $values['initiator_id'], 'style="width: 95%"').form_hidden('royal_id', $values['royal_id']);
		$data['Referral Editing']['Referred ([Unique Identifier - EEMember] Username)'] = form_dropdown('referred_id', $list_of_members, $values['referred_id'], 'style="width: 95%"');           
		$data['Referral Editing']['Membrr Subscription Plan'] = form_dropdown('brr_plan_id', $product_ids_list_items, $values['brr_plan_id'], 'style="width: 95%"');  
		$data['Referral Editing']['Percentage'] = form_input('percentage', $values['percentage']); 
		$data['Referral Editing']['Created Date'] = form_input('created_date', $values['created_date'], 'class="datepicker"');    
		$data['Referral Editing']['Amount'] = form_input('amount', $values['amount']); 
		$data['Referral Editing']['Paid'] = form_dropdown('paid', $paid, $values['paid']).form_hidden('date_paid', $values['date_paid']);
		$data['Referral Editing']['Check Number'] = form_input('paid_check_id', $values['paid_check_id']);

        $js .= '
				var draft_target = "";

			$("<div id=\"referral_delete_warning\">referral delete warning</div>").dialog({
				autoOpen: false,
				resizable: false,
				title: "confirm deleting",
				modal: true,
				position: "center",
				minHeight: "0px", 
				buttons: {
					Cancel: function() {
					$(this).dialog("close");
					},
				"delete": function() {
					location=draft_target;
				}
				}});

			$(".referral_delete_warning").click( function (){
				$("#referral_delete_warning").dialog("open");
				draft_target = $(this).attr("href");
				$(".ui-dialog-buttonpane button:eq(2)").focus();	
				return false;
		});';
		
		$js .= "
            $(\".editAccordion\").css(\"borderTop\", $(\".editAccordion\").css(\"borderBottom\")); 
            $(\".editAccordion h3\").click(function() {
                if ($(this).hasClass(\"collapsed\")) { 
                    $(this).siblings().slideDown(\"fast\"); 
                    $(this).removeClass(\"collapsed\").parent().removeClass(\"collapsed\"); 
                } else { 
                    $(this).siblings().slideUp(\"fast\"); 
                    $(this).addClass(\"collapsed\").parent().addClass(\"collapsed\"); 
                }
            }); 
        ";
      
        $js .= "
            $(function() {
				$(\"input.datepicker\").datepicker({ dateFormat: \"yy-mm-dd\" });
			});
        ";

        $this->EE->javascript->output($js);
                
        $vars['data'] = $data;
        
    	return $this->EE->load->view('referral_edit', $vars, TRUE);

	}
	
    function save_referral()
    {
    	if (empty($_POST))
    	{
    		show_error('unauthorized_access');
    	}
    	
        unset($_POST['submit']);
        $data = array();

		foreach ($_POST as $key=>$val)
        {
        	if (is_array($val))
        	{
        		$data[$key] = serialize($val);
        	}
        	else
        	{
        		$data[$key] = $val;
        	}
        }
        
        $db_fields = $this->EE->db->list_fields('royalmissions');
        foreach ($db_fields as $id=>$field)
        {
        	if (!isset($data[$field])) $data[$field] = '';
        }
      	
		if ($this->EE->input->post('royal_id')!='')
        {
	        if ($this->EE->input->post('paid')==1)
	        {
	            $this->EE->db->where('royal_id', $this->EE->input->post('royal_id'));
				$this->EE->db->update('royalmissions', $data);
				
				$data2 = array(
					'date_paid' => date('Y-m-d H:i:s')
				);
				
	            $this->EE->db->where('royal_id', $this->EE->input->post('royal_id'));
		    	$this->EE->db->update('royalmissions', $data2);
		    			    	
		    	$checkcheck = $this->EE->db->select('exp_membrr_subscriptions.next_charge_date as charge','exp_royalmissions.referred_id as rID')
		    		->from('membrr_subscriptions')
		    		->join('royalmissions','exp_membrr_subscriptions.member_id = exp_royalmissions.referred_id')
		    		->where('exp_membrr_subscriptions.member_id', $data['referred_id'])
		    		->where('exp_membrr_subscriptions.next_charge_date >=', $data['brr_plan_paid_date'])
		    		->where('exp_membrr_subscriptions.active', 1)
		    		->where('exp_membrr_subscriptions.cancelled', 0)
		    		->get();
		    		
		    	$total_count = $checkcheck->num_rows();
		    	
		    	if ($total_count >= 1)
		    	{
			    	$plusYear = date('Y-m-d', strtotime('+1 year', strtotime($data['created_date'])));
			    	
			    	$data3 = array(
				    	'royal_id' => false,
				    	'initiator_id' => $data['initiator_id'],	
				    	'referred_id' => $data['referred_id'],
				    	'created_date' => $plusYear,	
				    	'percentage' => $data['percentage'],
				    	'amount' => $this->get_amount($data4['amount']),
				    	'paid' => 0,
				    	'brr_plan_id' => $data['brr_plan_id'],
				    	'date_paid' => date('Y-m-d H:i:s'),
				    	'paid_check_id' => ''
			    	);		    	
			    	$this->EE->db->insert('royalmissions', $data3);
		    	}
	        }
            else
            {
	            $this->EE->db->where('royal_id', $this->EE->input->post('royal_id'));
				$this->EE->db->update('royalmissions', $data);
				$this->get_amount($data4['amount']);
			}            
            
        }
        else
        {
            $this->EE->db->insert('royalmissions', $data);
            $this->get_amount($data4['amount']);
        }
                        
        $this->EE->session->set_flashdata('message_success', 'updated');
        
        $this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=index');
    }

	function get_amount()
	{
    	$subscriptions = $this->EE->db->select('subscription_price as SP')
			->from('membrr_subscriptions')
			->where('member_id', $this->EE->input->post('referred_id'))
			->where('plan_id', $this->EE->input->post('brr_plan_id'))
			->where('active', 1)
			->get();
		
		if($subscriptions->num_rows() > 0)
		{
	        foreach ($subscriptions->result_array() as $row)
	        {
			echo '<br>if<br>';
			var_dump($row);
		       $percent = ($this->EE->input->post('percentage')/100);
		       $amount = ($row['SP']*$percent);
		       $data4 = array(
			   'amount' => $amount
	           );
			   $this->EE->db->where('referred_id', $this->EE->input->post('referred_id'));
			   $this->EE->db->where('brr_plan_id', $this->EE->input->post('brr_plan_id'));
			   $this->EE->db->update('royalmissions', $data4);
			   
			   return $data4['amount'];

	        }
	    }

	}
        
    function delete_referral()
    {
		$success = false;
        if ($this->EE->input->get_post('id')!='')
        {
            $this->EE->db->where('royal_id', $this->EE->input->get_post('id'));
            $this->EE->db->delete('royalmissions');
            
            $success = $this->EE->db->affected_rows();
        }
        
        if ($success != false)
        {
            $this->EE->session->set_flashdata('message_success', 'success'); 
        }
        else
        {
            $this->EE->session->set_flashdata('message_failure', 'error');  
        }

        $this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=index');
        
    }
    
    function referral_account_unpaid()
    {
    	$this->EE->load->library('table');
		$theme_folder_url = trim($this->EE->config->item('theme_folder_url'), '/').'/third_party/royalmissions/';
        $this->EE->cp->add_to_foot('<link type="text/css" href="'.$theme_folder_url.'multiselect/ui.multiselect.css" rel="stylesheet" />');
        $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$theme_folder_url.'multiselect/plugins/localisation/jquery.localisation-min.js"></script>');
        $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$theme_folder_url.'multiselect/plugins/blockUI/jquery.blockUI.js"></script>');
        $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$theme_folder_url.'multiselect/ui.multiselect.js"></script>');
    	
    	$js = "";
    	
        $js .= "
			$('select.sub_options').change(function () {
				if ($(this).val() != '') {
					window.location.href = $(this).val();
				}
			});
		";
        							
		if ($this->EE->input->get('id')!==false)
		{

			$this->EE->db->distinct('*'); 
			$this->EE->db->from('royalmissions');
			$this->EE->db->join('royalmissions_user', 'royalmissions_user.initiator_id = royalmissions.initiator_id');
			$this->EE->db->where('royalmissions.initiator_id', $this->EE->input->get('id'));   
			$this->EE->db->where('paid', '0');   
		    $q = $this->EE->db->get();

			$data = array();
			$vars['total_count'] = $q->num_rows();
	
			$i = 0;
	        foreach ($q->result_array() as $row)
	        {
		       $data[$i] = array();
		       $data[$i]['show'] = true;
	           $data[$i]['royal_id'] = $row['royal_id'];
	           $data[$i]['initiator_id'] = $row['initiator_id'];
	           $data[$i]['initiator_name'] = $row['screen_name'];
	           $data[$i]['referred_id'] = $row['referred_id'];           
	           $data[$i]['percentage'] = $row['percentage']; 
	           $data[$i]['percentage'] .= '%';
	           $data[$i]['created_date'] = $row['created_date'];    
	           $data[$i]['amount'] = '$';
	           $data[$i]['amount'] .= $row['amount']; 
	           $data[$i]['brr_plan_name'] = $row['brr_plan_name'];
	           $data[$i]['edit'] = '<a href="'.BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_edit'.AMP.'id='.$row['royal_id'].'" title="edit"><img src="'.$this->EE->cp->cp_theme_url.'images/icon-edit.png" alt="edit"></a>';
	           $data[$i]['delete'] = '<a href="'.BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=delete_referral'.AMP.'id='.$row['royal_id'].'" class="referral_delete_warning" title="delete"><img src="'.$this->EE->cp->cp_theme_url.'images/icon-delete.png" alt="delete"></a>';
	           
	           $i++;
	 			
	        }
	        
	        $vars['data'] = $data;
	        
	        $this->EE->javascript->output($js);
	        
        	$this->EE->cp->set_right_nav(array(
            	'Home RoyalMissions' => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=index',
            	'Profile for '.$data['0']['initiator_name'] => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_account&id='.$this->EE->input->get('id'),
            	'Paid Commissions for '.$data['0']['initiator_name'] => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_account_paid&id='.$this->EE->input->get('id')
			));

	        if ($vars['total_count']==0)
			{
				$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_account'.AMP.'id='.$this->EE->input->get('id'));
			}

	    	return $this->EE->load->view('referral_account', $vars, TRUE);
	    }
    }

    function referral_account_paid()
    {
    	$this->EE->load->library('table');
		$theme_folder_url = trim($this->EE->config->item('theme_folder_url'), '/').'/third_party/royalmissions/';
        $this->EE->cp->add_to_foot('<link type="text/css" href="'.$theme_folder_url.'multiselect/ui.multiselect.css" rel="stylesheet" />');
        $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$theme_folder_url.'multiselect/plugins/localisation/jquery.localisation-min.js"></script>');
        $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$theme_folder_url.'multiselect/plugins/blockUI/jquery.blockUI.js"></script>');
        $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$theme_folder_url.'multiselect/ui.multiselect.js"></script>');
    	
    	$js = "";
    	
        $js .= "
			$('select.sub_options').change(function () {
				if ($(this).val() != '') {
					window.location.href = $(this).val();
				}
			});
		";
        							
		if ($this->EE->input->get('id')!==false)
		{
			$this->EE->db->distinct('*'); 
			$this->EE->db->from('royalmissions');
			$this->EE->db->join('royalmissions_user', 'royalmissions_user.initiator_id = royalmissions.initiator_id');
			$this->EE->db->where('royalmissions.initiator_id', $this->EE->input->get('id'));   
			$this->EE->db->where('paid', '1');   
		    $q = $this->EE->db->get();

			$data = array();
			$vars['total_count'] = $q->num_rows();
	
			$i = 0;
	        foreach ($q->result_array() as $row)
	        {
		       $data[$i] = array();
		       $data[$i]['show'] = true;
	           $data[$i]['royal_id'] = $row['royal_id'];
	           $data[$i]['initiator_id'] = $row['initiator_id'];
	           $data[$i]['initiator_name'] = $row['screen_name'];
	           $data[$i]['referred_id'] = $row['referred_id'];           
	           $data[$i]['percentage'] = $row['percentage']; 
	           $data[$i]['percentage'] .= '%';
	           $data[$i]['created_date'] = $row['created_date'];    
	           $data[$i]['amount'] = '$';
	           $data[$i]['amount'] .= $row['amount']; 
	           $data[$i]['brr_plan_name'] = $row['brr_plan_name'];
	           $data[$i]['edit'] = '<a href="'.BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_edit'.AMP.'id='.$row['royal_id'].'" title="edit"><img src="'.$this->EE->cp->cp_theme_url.'images/icon-edit.png" alt="edit"></a>';
	           $data[$i]['delete'] = '<a href="'.BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=delete_referral'.AMP.'id='.$row['royal_id'].'" class="referral_delete_warning" title="delete"><img src="'.$this->EE->cp->cp_theme_url.'images/icon-delete.png" alt="delete"></a>';
	           
	           $i++;
	 			
	        }
	        
	        $vars['data'] = $data;
	        
	        $this->EE->javascript->output($js);
	        
        	$this->EE->cp->set_right_nav(array(
            	'Home RoyalMissions' => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=index',
            	'Profile for '.$data['0']['initiator_name'] => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_account&id='.$this->EE->input->get('id'),
            	'Unpaid Commissions for '.$data['0']['initiator_name'] => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_account_unpaid&id='.$this->EE->input->get('id')
			));

	        if ($vars['total_count']==0)
			{
				$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_account'.AMP.'id='.$this->EE->input->get('id'));
			}

	    	return $this->EE->load->view('referral_account', $vars, TRUE);
	    }
    }

    function referral_account()
    {
    	$this->EE->load->library('table');
		$theme_folder_url = trim($this->EE->config->item('theme_folder_url'), '/').'/third_party/royalmissions/';
        $this->EE->cp->add_to_foot('<link type="text/css" href="'.$theme_folder_url.'multiselect/ui.multiselect.css" rel="stylesheet" />');
        $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$theme_folder_url.'multiselect/plugins/localisation/jquery.localisation-min.js"></script>');
        $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$theme_folder_url.'multiselect/plugins/blockUI/jquery.blockUI.js"></script>');
        $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$theme_folder_url.'multiselect/ui.multiselect.js"></script>');
    	
    	$js = "";
    	
        $js .= "
			$('select.sub_options').change(function () {
				if ($(this).val() != '') {
					window.location.href = $(this).val();
				}
			});
		";
        							
		if ($this->EE->input->get('id')!==false)
		{

			$this->EE->db->select('*'); 
			$this->EE->db->from('royalmissions_user');
			$this->EE->db->join('royalmissions', 'royalmissions_user.initiator_id = royalmissions.initiator_id');
			$this->EE->db->where('royalmissions.initiator_id', $this->EE->input->get('id'));   
		    $q = $this->EE->db->get();

			$data = array();
			$vars['total_count'] = $q->num_rows();
	
			$i = 0;
	        foreach ($q->result_array() as $row)
	        {
		       $data[$i] = array();
		       $data[$i]['show'] = true;
	           $data[$i]['royal_id'] = $row['royal_id'];
	           $data[$i]['initiator_id'] = $row['initiator_id'];
	           $data[$i]['initiator_name'] = $row['screen_name'];
	           $data[$i]['referred_id'] = $row['referred_id'];           
	           $data[$i]['percentage'] = $row['percentage']; 
	           $data[$i]['percentage'] .= '%';
	           $data[$i]['created_date'] = $row['created_date'];    
	           $data[$i]['amount'] = '$';
	           $data[$i]['amount'] .= $row['amount'];
	           $data[$i]['brr_plan_name'] = $row['brr_plan_name'];
	           if($row['paid'] == 1)
	           {
	           		$data[$i]['paid'] = "Yes";
	           }
	           else
	           {
		           	$data[$i]['paid'] = "No";
	           }
	           $data[$i]['profile_id'] = $row['profile_id'];    
	           $data[$i]['taxform_2014'] = $row['taxform_2014'];    
	           $data[$i]['taxform_2015'] = $row['taxform_2015'];    
	           $data[$i]['taxform_2016'] = $row['taxform_2016'];    
	           $data[$i]['taxform_2017'] = $row['taxform_2017'];    
	           $data[$i]['taxform_2018'] = $row['taxform_2018'];    
	           $data[$i]['taxform_2019'] = $row['taxform_2019'];    
	           $data[$i]['taxform_2020'] = $row['taxform_2020'];    
	           $data[$i]['zoo'] = $row['zoo_id'];    
	           $data[$i]['active'] = $row['active'];    
	           $data[$i]['edit'] = '<a href="'.BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_edit'.AMP.'id='.$row['royal_id'].'" title="edit"><img src="'.$this->EE->cp->cp_theme_url.'images/icon-edit.png" alt="edit"></a>';
	           $data[$i]['delete'] = '<a href="'.BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=delete_referral'.AMP.'id='.$row['royal_id'].'" class="referral_delete_warning" title="delete"><img src="'.$this->EE->cp->cp_theme_url.'images/icon-delete.png" alt="delete"></a>';
	           
	           $i++;
	 			
	        }
	        
	        $vars['data'] = $data;
	        
	        $this->EE->javascript->output($js);
	        
        	$this->EE->cp->set_right_nav(array(
            	'Home RoyalMissions' => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=index',
            	'Paid Commissions for '.$data['0']['initiator_name'] => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_account_paid'.AMP.'id='.$this->EE->input->get('id'),
            	'Unpaid Commissions for '.$data['0']['initiator_name'] => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_account_unpaid'.AMP.'id='.$this->EE->input->get('id')
			));
	        
	        if ($vars['total_count']==0)
			{
				$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=index');
			}
	        
	    	return $this->EE->load->view('referral_account', $vars, TRUE);
	    }
    }
    
    function sync_no_redirect()
	{
		//Checks the DB tables... Members, Channel_Data, Royalmissions_User based off Members.member_id
		$q = $this->EE->db->select('member_id, screen_name, field_id_12, field_id_13')
			->from('members')
			->join('channel_data', 'members.screen_name = channel_data.entry_id')
			->where('channel_data.channel_id =', '6')
			->get();
				
		if($q==false) show_error('Nothing Exists');
		
		$i = 0;

   		foreach ($q->result_array() as $row)
   		{
	   		$members = array(
	   			'initiator_id' => $row['member_id'],
	   			'screen_name' => $row['field_id_12'].' '.$row['field_id_13'],
	   			'zoo_id' => $row['screen_name']
   			);
   			
   			$check = $this->EE->db->select('initiator_id')
		   				->from('royalmissions_user')
		   				->where('initiator_id', $members['initiator_id'])
		   				->get();
		   	if ( $check->num_rows() >= 1 )
		   	{
				$this->EE->db->where('initiator_id', $members['initiator_id'])
					->update('royalmissions_user', $members);
		    }
		    else
		    {
				$this->EE->db->insert('royalmissions_user', $members);
		    }
			
			$i++;
   		}
   		//Inserts the plan information from membrr into royalmissions
		$qu = $this->EE->db->select('membrr_payments.recurring_id as rID, membrr_payments.date as date, membrr_plans.plan_name as name, membrr_plans.plan_id as planID, membrr_subscriptions.member_id as member, exp_membrr_subscriptions.next_charge_date AS charge')
			->from('membrr_payments')
			->join('membrr_subscriptions', 'membrr_subscriptions.recurring_id = membrr_payments.recurring_id')
			->join('membrr_plans', 'membrr_subscriptions.plan_id = membrr_plans.plan_id')
			->get();
				
		if($qu==false) show_error('Nothing Exists');
		
		$t = 0;

   		foreach ($qu->result_array() as $row)
   		{
	   		$membrr_items = array(
	   			'brr_plan_name' => $row['name'],
	   			'brr_plan_paid_date' => $row['date'],
	   			'brr_plan_recurring_id' => $row['rID'],
	   			'referred_id' => $row['member'],
	   			'brr_plan_id' => $row['planID'],
	   			'brr_plan_next_date' => $row['charge']
   			);
   			
   			$check = $this->EE->db->select('royal_id')
		   				->from('royalmissions')
		   				->where('referred_id', $membrr_items['referred_id'])
		   				->where('brr_plan_id', $membrr_items['brr_plan_id'])
		   				->get();
		   				
		   	if ( $check->num_rows() >= 1 )
		   	{
			   	foreach ($check->result_array() as $row1)
			   	{
					$this->EE->db->where('royal_id', $row1['royal_id'])
						->update('royalmissions', $membrr_items);
				}
		    }
			
			$t++;
   		}
		$vars['total_count'] = ($i+$t);
	}

	function profile_sync()
	{
		//Checks the DB tables... Members, Channel_Data, Royalmissions_User based off Members.member_id
		$q = $this->EE->db->select('member_id, screen_name, field_id_12, field_id_13')
			->from('members')
			->join('channel_data', 'members.screen_name = channel_data.entry_id')
			->where('channel_data.channel_id =', '6')
			->get();
				
		if($q==false) show_error('Nothing Exists');
		
		$i = 0;

   		foreach ($q->result_array() as $row)
   		{
	   		$members = array(
	   			'initiator_id' => $row['member_id'],
	   			'screen_name' => $row['field_id_12'].' '.$row['field_id_13'],
	   			'zoo_id' => $row['screen_name']
   			);
   			
   			$check = $this->EE->db->select('initiator_id')
		   				->from('royalmissions_user')
		   				->where('initiator_id', $members['initiator_id'])
		   				->get();
		   	if ( $check->num_rows() >= 1 )
		   	{
				$this->EE->db->where('initiator_id', $members['initiator_id'])
					->update('royalmissions_user', $members);
		    }
		    else
		    {
				$this->EE->db->insert('royalmissions_user', $members);
		    }
			
			$i++;
   		}

		$vars['total_count'] = $i;
    	
    	return $this->EE->load->view('profile_sync', $vars, TRUE);
	}
	
	function test_sync()
	{
		//Grabs all data from CD and Membrr tables to populate the royalmissions table
		$rm_sql = $this->EE->db->select('cd.entry_id as ZooID, cd.field_id_11 as MemberID, cd.field_id_12 as FirstName, cd.field_id_13 as LastName, cd.field_id_69 as ReferredBy, mp.recurring_id as RecurringID, mp.date as PlanPaidDate, mpl.plan_name as PlanName, mpl.plan_id as PlanID, ms.next_charge_date as NextCharge, ms.subscription_price as SubPrice, ms.date_created as CreatedDate, ms.cancelled as Cancelled')
			->from('channel_data cd')
			->join('membrr_subscriptions ms', 'cd.field_id_11 = ms.member_id')
			->join('membrr_payments mp', 'ms.recurring_id = mp.recurring_id')
			->join('membrr_plans mpl', 'ms.plan_id = mpl.plan_id')
			->where('cd.channel_id =', '6')
			->where('mpl.plan_id !=', '')
			->get();
		//loop through each entry	
		if ( $rm_sql->num_rows() > 1 )
		{
			foreach ($rm_sql->result_array() as $row)
			{
				$rm = '';
				$price = $row['SubPrice'];
				$referralfeepercentage = 25;	       
				$percent = ($referralfeepercentage/100);
				$amount = ($price*$percent);
	
				//assigned to the royalmissions table
				$rm = array(
					'royal_id' => false,
					
					'initiator_id' => $row['ReferredBy'],
					'referred_id' => $row['MemberID'],
						    		
					'created_date' => $row['CreatedDate'],
		
					'brr_plan_id' => $row['PlanID'],
					
					'percentage' => $referralfeepercentage,
					'amount' => $amount,
					
					'brr_plan_name' => $row['PlanName'],
					'brr_plan_paid_date' => $row['PlanPaidDate'],
					'brr_plan_recurring_id' => $row['RecurringID'],
					'brr_plan_next_date' => $row['NextCharge'],
								
					'referred_id_active' => 1,
					'cancelled' => $row['Cancelled']
				);
				//assigned to the royalmissions_users table
				$rmUsers = array (
					'initiator_id' => $row['MemberID'],
					'screen_name' => $row['FirstName'].' '.$row['LastName'],
					'zoo_id' => $row['ZooID']
				);
				
				//NEED TO UPDATE DB WITH VALUES
	   			$checkRM = $this->EE->db->select('royal_id, brr_plan_recurring_id')
					->from('royalmissions')
					->where('brr_plan_recurring_id =', $row['RecurringID'])
					->get();
					
			   	if ( $checkRM->num_rows() == 0 )
			   	{
					$this->EE->db->insert('royalmissions', $rm);
				}
				else
				{
					$this->EE->db->where('royal_id =', $row['ZooID']);
					$this->EE->db->update('royalmissions', $rm);
				}
						
	   			$checkRMU = $this->EE->db->select('zoo_id')
					->from('royalmissions_user')
					->where('zoo_id', $row['ZooID'])
					->get();
					
			   	if ( $checkRMU->num_rows() == 0 )
			   	{
					$this->EE->db->insert('royalmissions_user', $rmUsers);
				}
			}
		}
	}
	
	function save_profile()
    {
    	if (empty($_POST))
    	{
    		show_error('unauthorized_access');
    	}
    	
        unset($_POST['submit']);
        $data = array();

		foreach ($_POST as $key=>$val)
        {
        	if (is_array($val))
        	{
        		$data[$key] = serialize($val);
        	}
        	else
        	{
        		$data[$key] = $val;
        	}
        }
        
        $db_fields = $this->EE->db->list_fields('royalmissions_user');
        foreach ($db_fields as $id=>$field)
        {
        	if (!isset($data[$field])) $data[$field] = '';
        }
      	//var_dump($data);
        //$this->EE->db->update('royalmissions_user', $data, 'initiator_id = '.$data['initiator_id']);
        
		if ($this->EE->input->post('initiator_id')!='')
        {
            $this->EE->db->where('initiator_id', $this->EE->input->post('initiator_id'));
            $this->EE->db->update('royalmissions_user', $data);
        }

        $vars['data'] = $data;
        $this->EE->session->set_flashdata('message_success', 'updated');
        
        $this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=royalmissions'.AMP.'method=referral_account'.AMP.'id='.$data['initiator_id']);
		//return $this->EE->load->view('testing', $vars, TRUE);
    }

	function profile_edit()
	{
		$this->EE->load->helper('form');
    	$this->EE->load->library('table');  
    	    	
    	$js = '';
    	    	
		$theme_folder_url = trim($this->EE->config->item('theme_folder_url'), '/').'/third_party/royalmissions/';
        $this->EE->cp->add_to_foot('<link type="text/css" href="'.$theme_folder_url.'multiselect/ui.multiselect.css" rel="stylesheet" />');
        $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$theme_folder_url.'multiselect/plugins/localisation/jquery.localisation-min.js"></script>');
        $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$theme_folder_url.'multiselect/plugins/blockUI/jquery.blockUI.js"></script>');
        $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$theme_folder_url.'multiselect/ui.multiselect.js"></script>');
        
       	$values = array(
    		'profile_id' => false,
			'initiator_id' => '',	
			'screen_name' => '',
			
			'taxform_2014' => NULL,
			'taxform_2015' => NULL,
			'taxform_2016' => NULL,
			'taxform_2017' => NULL,
			'taxform_2018' => NULL,
			'taxform_2019' => NULL,
			'taxform_2020' => NULL,
			
			'zoo_id' => '',
						
			'active' => 1
			
		);

		
		if ($this->EE->input->get('id')!==false)
		{
			$q = $this->EE->db->select()
					->from('royalmissions_user')
					->where('initiator_id', $this->EE->input->get('id'))
					->get();
			if ($q->num_rows()==0)
			{
				show_error('Unauthorized Access');
			}
			
			foreach ($values as $field_name=>$default_field_val)
			{
				if (is_array($default_field_val))
				{
					$values["$field_name"] = ($q->row("$field_name")!='')?unserialize($q->row("$field_name")):array();
				}
				else
				{
					$values["$field_name"] = $q->row("$field_name");
				}
			}
		}
		
		$this->EE->cp->add_js_script(
			array('ui' => array(
				'core', 'datepicker'
			)
		));
		
		$option = array(
			'0' => 'No',
			'1' => 'Yes'
		);

		$data['Profile Editing'] = array();
		$data['Profile Editing']['show'] = true;
		$data['Profile Editing']['Profile ID'] = $values['profile_id'].form_hidden('profile_id', $values['profile_id']);
		$data['Profile Editing']['Initiator ID'] = $values['initiator_id'].form_hidden('initiator_id', $values['initiator_id']);
		$data['Profile Editing']['Initiator Name'] = $values['screen_name'].form_hidden('screen_name', $values['screen_name']);           
		$data['Profile Editing']['Tax Form 2014'] = form_dropdown('taxform_2014', $option, $values['taxform_2014']); 
		$data['Profile Editing']['Tax Form 2015'] = form_dropdown('taxform_2015', $option, $values['taxform_2015']); 
		$data['Profile Editing']['Tax Form 2016'] = form_dropdown('taxform_2016', $option, $values['taxform_2016']); 
		$data['Profile Editing']['Tax Form 2017'] = form_dropdown('taxform_2017', $option, $values['taxform_2017']); 
		$data['Profile Editing']['Tax Form 2018'] = form_dropdown('taxform_2018', $option, $values['taxform_2018']); 
		$data['Profile Editing']['Tax Form 2019'] = form_dropdown('taxform_2019', $option, $values['taxform_2019']); 
		$data['Profile Editing']['Tax Form 2020'] = form_dropdown('taxform_2020', $option, $values['taxform_2020']); 
		$data['Profile Editing']['Unique Identifier'] = $values['zoo_id'].form_hidden('zoo_id', $values['zoo_id']);    
		$data['Profile Editing']['Active'] = form_dropdown('active', $option, $values['active']); 

        $js .= '
				var draft_target = "";

			$("<div id=\"referral_delete_warning\">referral delete warning</div>").dialog({
				autoOpen: false,
				resizable: false,
				title: "confirm deleting",
				modal: true,
				position: "center",
				minHeight: "0px", 
				buttons: {
					Cancel: function() {
					$(this).dialog("close");
					},
				"delete": function() {
					location=draft_target;
				}
				}});

			$(".referral_delete_warning").click( function (){
				$("#referral_delete_warning").dialog("open");
				draft_target = $(this).attr("href");
				$(".ui-dialog-buttonpane button:eq(2)").focus();	
				return false;
		});';
		
		$js .= "
            $(\".editAccordion\").css(\"borderTop\", $(\".editAccordion\").css(\"borderBottom\")); 
            $(\".editAccordion h3\").click(function() {
                if ($(this).hasClass(\"collapsed\")) { 
                    $(this).siblings().slideDown(\"fast\"); 
                    $(this).removeClass(\"collapsed\").parent().removeClass(\"collapsed\"); 
                } else { 
                    $(this).siblings().slideUp(\"fast\"); 
                    $(this).addClass(\"collapsed\").parent().addClass(\"collapsed\"); 
                }
            }); 
        ";
      
        $js .= "
            $(function() {
				$(\"input.datepicker\").datepicker({ dateFormat: \"yy-mm-dd\" });
			});
        ";

        $this->EE->javascript->output($js);
        
        $vars['data'] = $data;
        
    	return $this->EE->load->view('profile_edit', $vars, TRUE);

	}
	
	function membrr_sub_sync()
	{
		$this->sync_no_redirect();
		
   		//Adds entry in royalmissions if new payment is generated... part of automation for subscription purchases with paypal
		$dateQuery = $this->EE->db->select('sync_date')
			->from('royalmissions_sync')
			->order_by('sync_id','desc')
			->limit(1)
			->get();
				
		if ($dateQuery->num_rows() > 0)
		{
/*			//set $SD for sync_date
			$row = $dateQuery->row(); 
			$SD = $row->sync_date;
			
			$membrr_sync = $this->EE->db->select('subscription_price as SP, plan_id as planID, recurring_id as recurringID, member_id as memID, date_created as DC')
				->from('membrr_subscriptions')
				->where('date_created >=',$SD)
//				->where('date_created >=','2014-09-17 00:00:00')
				->where('active', 1)
				->get();
			if ($membrr_sync->num_rows() > 0)
			{
				$i =0;
		   		foreach ($membrr_sync->result_array() as $row0)
		   		{
			   		$membrr_items[$i] = array(
			   			'recurID' => $row0['recurringID'],
			   			'member' => $row0['memID'],
			   			'planID' => $row0['planID'],
			   			'price' => $row0['SP'],
			   			'dc' => $row0['DC']
		   			);

		   			$check = $this->EE->db->select()
		   				->from('royalmissions')
		   				->where('referred_id !=', $membrr_items[$i]['member'])
		   				->where('brr_plan_id !=', $membrr_items[$i]['planID'])
		   				->where('brr_plan_recurring_id !=', $membrr_items[$i]['recurID'])
		   				->get();
		   				
		   			$row1 = $check->row(); 
		   			$brr_plan_recurring_id = $row1->brr_plan_recurring_id;

					if ($brr_plan_recurring_id != $membrr_items[$i]['recurID'])
					{
			   			$check2 = $this->EE->db->select('zoo_id')
			   				->from('royalmissions_user')
			   				->where('initiator_id', $membrr_items[$i]['member'])
			   				->get();
			   			
			   			$row2 = $check2->row(); 
			   			$zoo_id = $row2->zoo_id;
			   			
			   			//field_id_69 is the referred field
						$zooI = $this->EE->db->select('field_id_69 as ReferredBy')
			   				->from('channel_data')
			   				->where('channel_id', 6)
			   				->where('entry_id', $zoo_id)
			   				->get();
			   								   				
			   			$row3 = $zooI->row(); 
			   			$zooI_ID = $row3->ReferredBy;
			   			
						if ($zooI_ID != NULL)
						{
							$zooM = $this->EE->db->select('initiator_id as IiD')
				   				->from('royalmissions_user')
				   				->where('zoo_id', $zooI_ID)
				   				->get();
	
				   			$row4 = $zooM->row(); 
				   			$I_ID = $row4->Iid;
							
	
							$values = array(
					    		'royal_id' => false,
								'initiator_id' => $I_ID,	
								'referred_id' => $membrr_items[$i]['member'],
								
								'created_date' => $membrr_items[$i]['dc'],
								'percentage' => '25',
								'amount' => '0.00',
					
								'brr_plan_id' => $membrr_items[$i]['planID'],
								
								'paid' => 0,			
								'date_paid' => '',
								'paid_check_id' => '',
											
								'referred_id_active' => 1
							);
							$this->EE->db->insert('royalmissions', $values);
							
					    	$subscriptions = $this->EE->db->select('subscription_price as SubPrice')
								->from('membrr_subscriptions')
								->where('member_id', $values['referred_id'])
								->where('plan_id', $values['brr_plan_id'])
								->where('active', 1)
								->get();
							
							if($subscriptions->num_rows() > 0)
							{
						        foreach ($subscriptions->result_array() as $row5)
						        {
							       $percent = ($values['percentage']/100);
							       $amount = ($row5['SubPrice']*$percent);
							       $data4 = array(
								   'amount' => $amount
						           );
								   $this->EE->db->where('referred_id', $values['referred_id']);
								   $this->EE->db->where('brr_plan_id', $values['brr_plan_id']);
								   $this->EE->db->update('royalmissions', $data4);					
						        }
						    }
						}
					}
		   			$i++;
		   		}
		   		
			}
		}
*/

			$this->test_sync();
		}
		$this->sync_tracker();
		
		$vars['total_count'] = 1;
    	
    	return $this->EE->load->view('profile_sync', $vars, TRUE);
	}
	
	function sync_tracker()
	{
		$syncDB = array(
			'sync_id' => FALSE,
			'sync_date' => date('Y-m-d H:i:s')
		);
		$this->EE->db->insert('royalmissions_sync', $syncDB);
		
        $this->EE->session->set_flashdata('message_success', 'updated');

		$vars['total_count'] = 1;
    	
    	return $this->EE->load->view('profile_sync', $vars, TRUE);
	}


	/**
	 * Start on your custom code here...
	 */
	
}
/* End of file mcp.royalmissions.php */
/* Location: /system/expressionengine/third_party/royalmissions/mcp.royalmissions.php */