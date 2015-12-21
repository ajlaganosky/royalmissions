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
 * RoyalMissions Module Front End File
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Module
 * @author		AJ Laganosky
 * @link		https://www.higherinfogroup.com
 */

class Royalmissions {
	
	public $return_data;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
	}
		
	// ----------------------------------------------------------------

	/**
	 * Start on your custom code here...
	 * /
	public function summary()
	{
	    // Find the entry_id of the auction to display
	    $entry_id = $this->EE->TMPL->fetch_param('entry_id');
	    if( $entry_id === FALSE ) {
	        return "";
	    }
	    
	    $tagdata = $this->EE->TMPL->tagdata;
	    
	    // Build array of our variables
	    $data = array(
	        "current_bid" => "0.00",
	        "total_bids" => 0
	    );
	    
	    // Construct $variables array for use in parse_variables method
	    $variables = array();
	    $variables[] = $data;
	
	    return $this->EE->TMPL->parse_variables( $tagdata, $variables );
	} */	
}
/* End of file mod.royalmissions.php */
/* Location: /system/expressionengine/third_party/royalmissions/mod.royalmissions.php */