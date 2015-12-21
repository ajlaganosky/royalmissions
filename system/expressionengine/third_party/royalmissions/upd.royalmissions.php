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
 * RoyalMissions Module Install/Update File
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Module
 * @author		AJ Laganosky
 * @link		https://www.higherinfogroup.com
 */

class Royalmissions_upd {
	
	public $version = '0.01';
	
	private $EE;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Installation Method
	 *
	 * @return 	boolean 	TRUE
	 */
	public function install()
	{
		$mod_data = array(
			'module_name'			=> 'RoyalMissions',
			'module_version'		=> $this->version,
			'has_cp_backend'		=> "y",
			'has_publish_fields'	=> 'n'
		);
		
		$this->EE->db->insert('modules', $mod_data);
		
        $data = array( 
        	'class' => 'Royalmissions',
        	'method' => 'profile_sync'
        );
         
        $this->EE->db->insert('actions', $data); 

		$this->EE->load->dbforge();
		$this->EE->load->dbutil();

		if ($this->EE->dbutil->database_exists('royalmissions'))
		{
			$fields = array(
			    'royal_id' => array(
			    	'type' => 'int',
			    	'constraint' => '15',
			    	'unsigned' => TRUE,
			    	'auto_increment' => TRUE
			    ),
			    'referred_by_id' => array(
				    'type' => 'int',
				    'constraint'  => '15'
			    ),
			    'alt_member_id' => array(
			    	'type' => 'int',
			    	'constraint'  => '15'
			    ),
			    'created_date' => array(
			    	'type' => 'datetime'
			    ),
			    'percentage' => array(
			    	'type' => 'int',
			    	'constraint'  => '2'
			    ),
			    'amount' => array(
					'type' => 'decimal',
					'constraint' => '7,2',
					'default' => '0.00',
					'null' => FALSE
				),
			    'referred_id_active' => array(
			    	'type' => 'tinyint',
			    	'constraint' => '1',
			    	'default' => '1'
			    ),
			    'paid' => array(
			    	'type' => 'tinyint',
			    	'constraint' => '1',
			    	'default' => '0'
			    ),
			    'date_paid' => array(
			    	'type' => 'datetime'
			    ),
			    'paid_check_id' => array(
			    	'type' => 'varchar',
			    	'constraint' => '250'
			    ),
			    'brr_info' => array(
					'type' => 'longtext'
				)
		    );
		    
		    ee()->dbforge->add_field($fields);
			ee()->dbforge->add_key('royal_id', TRUE);
			ee()->dbforge->create_table('royalmissions');
		}
		else
		{
		}
		if ($this->EE->dbutil->database_exists('royalmissions_user'))
		{
			$fields = array(
			    'alt_member_id' => array(
				    'type' => 'int',
				    'constraint'  => '15',
				    'unique' => TRUE
			    ),
			    'zoo_member_id' => array(
			    	'type' => 'int',
			    	'constraint' => '15',
			    	'unique' => TRUE
			    ),
			    'screen_name' => array(
				    'type' => 'varchar',
				    'constraint'  => '250'
			    ),
			    'taxform' => array(
			    	'type' => 'longtext',
			    ),
			    'active' => array(
			    	'type' => 'tinyint',
			    	'constraint'  => '1',
			    	'default' => '1'
			    )
		    );
		    
		    ee()->dbforge->add_field($fields);
			ee()->dbforge->add_key('alt_member_id', TRUE);
			
			ee()->dbforge->create_table('royalmissions_user');
		}
		else
		{			
		}
		if ($this->EE->dbutil->database_exists('royalmissions_sync'))
		{
			$fields = array(
			    'sync_id' => array(
			    	'type' => 'int',
			    	'constraint'  => '15',
			    	'unsigned' => TRUE,
			    	'auto_increment' => TRUE
			    ),
			    'sync_date' => array(
			    	'type' => 'datetime'
			    )
			);
			
		    ee()->dbforge->add_field($fields);
			ee()->dbforge->add_key('sync_id', TRUE);
			
			ee()->dbforge->create_table('royalmissions_sync');
		}
		else
		{
			
		}
		
		//$this->EE->load->dbforge();
		/**
		 * In order to setup your custom tables, uncomment the line above, and 
		 * start adding them below!
		 */
		
		return TRUE;
	}

	// ----------------------------------------------------------------
	
	/**
	 * Uninstall
	 *
	 * @return 	boolean 	TRUE
	 */	
	public function uninstall()
	{
		$mod_id = $this->EE->db->select('module_id')
								->get_where('modules', array(
									'module_name'	=> 'RoyalMissions'
								))->row('module_id');
				
		$this->EE->db->where('module_name', 'RoyalMissions')
					 ->delete('modules');

		//$this->EE->load->dbforge();
		//$this->EE->dbforge->drop_table('royalmissions');
		//$this->EE->dbforge->drop_table('royalmissions_user');
		//$this->EE->dbforge->drop_table('royalmissions_sync');
				
		// $this->EE->load->dbforge();
		// Delete your custom tables & any ACT rows 
		// you have in the actions table
		
		return TRUE;
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Module Updater
	 *
	 * @return 	boolean 	TRUE
	 */	
	public function update($current = '')
	{			
		// If you have updates, drop 'em in here.
		return TRUE;
	}
	
}
/* End of file upd.royalmissions.php */
/* Location: /system/expressionengine/third_party/royalmissions/upd.royalmissions.php */