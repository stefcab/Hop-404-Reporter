<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD.'hop_404_reporter/helper.php';

class hop_404_reporter_mcp
{
	private $_perpage = 25;
	private $_offset;
	private $_limit;
	private $_sort;
	private $_keywords;
	private $_date_range_filter;
	private $_referrer_url_filter;
	private $_interval_notification_filter;
	
	public function __construct()
	{
		if (REQ == 'CP')
		{
			//Add our specific JS
			ee()->javascript->output(file_get_contents(PATH_THIRD.'hop_404_reporter/javascript/hop_404_reporter.js'));
		}
	}
	
	/**
	 * Build the navigation menu for the module
	 */
	function build_nav()
	{
		ee()->cp->set_right_nav(array(
			lang('nav_index')		=> BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter',
			lang('nav_emails')		=> BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter'.AMP.'method=display_emails',
			lang('nav_settings')	=> BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter'.AMP.'method=settings',
			lang('nav_support')		=> BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter'.AMP.'method=support'
		));
	}
	
	/**
	 * Build pagination configuration
	 */
	function pagination_config($method, $total_rows)
	{
		// Pass the relevant data to the paginate class
		$config['base_url'] = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter'.AMP.'method='.$method;
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $this->_perpage;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'rownum';
		$config['full_tag_open'] = '<p id="paginationLinks">';
		$config['full_tag_close'] = '</p>';
		$config['prev_link'] = '<img src="'.ee()->cp->cp_theme_url.'images/pagination_prev_button.gif" width="13" height="13" alt="<" />';
		$config['next_link'] = '<img src="'.ee()->cp->cp_theme_url.'images/pagination_next_button.gif" width="13" height="13" alt=">" />';
		$config['first_link'] = '<img src="'.ee()->cp->cp_theme_url.'images/pagination_first_button.gif" width="13" height="13" alt="< <" />';
		$config['last_link'] = '<img src="'.ee()->cp->cp_theme_url.'images/pagination_last_button.gif" width="13" height="13" alt="> >" />';

		return $config;
	}
	
	/*
	 * Because if no method found, this one will be returned
	 * We're making it the URLs list
	 */
	function index()
	{
		$this->build_nav();
		ee()->view->cp_page_title = lang('hop_404_reporter_module_name');
		ee()->cp->load_package_css('hop_404');
		
		ee()->load->library('pagination');
		ee()->load->library('javascript');
		ee()->load->library('table');
		ee()->load->helper('form');
		
		
		
		$columns = array(
			'url' 			=> array('header' => lang('url')),
			'referrer_url'	=> array('header' => lang('referrer')),
			'count'			=> array('header' => lang('count')),
			'last_occurred'	=> array('header' => lang('last_occurred_date')),
			'_check'		=> array(
				'header' => form_checkbox('select_all', 'true', FALSE, 'class="toggle_all" id="select_all"'),
				'sort' => FALSE
			)
		);

		$filter_base_url = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter';

		if ($entry_id = ee()->input->get('entry_id'))
		{
			$filter_base_url .= AMP.'entry_id='.$entry_id;
		}

		ee()->table->set_base_url($filter_base_url);
		ee()->table->set_columns($columns);

		$params = array('perpage' => $this->_perpage);
		$defaults = array('sort' => array('last_occurred' => 'desc'));

		$vars = ee()->table->datasource('_url_data', $defaults, $params);
		
		$vars['action_url'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter'.AMP.'method=modify_urls';
    	$vars['form_hidden'] = NULL;
		
		$vars['options'] = array(
			//'edit'  => lang('edit_selected'),
			'delete'    => lang('delete_selected')
		);
		
		$vars["filter_keywords"] = $this->_keywords;
		$vars["filter_referrer_url_options"] = $this->_get_filter_referrer_url_options();
		$vars["filter_referrer_url_selected"] = $this->_referrer_url_filter;
		$vars["filter_date_range_options"] = $this->_get_filter_date_range_options();
		$vars["filter_date_range_selected"] = $this->_date_range_filter;
		
		// View related stuff
		ee()->javascript->output(array(
			'$(".toggle_all").toggle(
				function(){
					$("input.toggle").each(function() {
						this.checked = true;
					});
				}, function (){
					var checked_status = this.checked;
					$("input.toggle").each(function() {
						this.checked = false;
					});
				}
			);
			$(function() {
				//Setting css stuff so the table is correctly displayed even with very long URLs
				$(".pageContents").css("overflow", "auto");
				$("#mainContent table.mainTable").css("width", $(".pageContents").width()+"px");
				//Setup the dynamic filtering
				Hop404Reporter_cp.setup_tables();
			});
			'
		));
		ee()->cp->add_js_script(array('plugin' => 'dataTables'));
		ee()->javascript->compile();
		
		return ee()->load->view('index', $vars, TRUE);
	}
	
	/**
	 * Get the data from database and expose it as JSON for CP table
	 */
	function _url_data($state, $params)
	{
		//print_r($params);
		//$this->_setup_query_filters($state, $params);
		
		//Do the sorting 
		$this->_sort = $state['sort'];
		$this->_offset = $state['offset'];
		
		foreach ($this->_sort as $col => $dir)
		{
			ee()->db->order_by($col, $dir);
		}

		//Filtering
		$this->_keywords = ee()->input->get_post('keywords');
		$this->_date_range_filter = ee()->input->get_post('date_range');
		$this->_referrer_url_filter = ee()->input->get_post('referrer_url_f');
		
		$sql_filter_where = "(`url` LIKE '%".ee()->db->escape_like_str($this->_keywords)."%' OR `referrer_url` LIKE '%".ee()->db->escape_like_str($this->_keywords)."%' )";
		ee()->db->where($sql_filter_where, NULL, TRUE);
		
		if ($this->_date_range_filter)
		{
			$datetime = new DateTime();
			$datetime->setTimestamp(ee()->localize->now);
			$datetime->sub(new DateInterval('P'.$this->_date_range_filter.'D'));
			ee()->db->where('last_occurred >', $datetime->format('Y-m-d H:i:s'));
		}
		
		if ($this->_referrer_url_filter)
		{
			if ($this->_referrer_url_filter == "referrer_saved")
			{
				ee()->db->where('referrer_url !=', 'referrer_not_specified');
				ee()->db->where('referrer_url !=', 'referrer_not_tracked');
			}
			else if ($this->_referrer_url_filter == "no_referrer")
			{
				ee()->db->where('referrer_url', 'referrer_not_specified');
			}
			else if ($this->_referrer_url_filter == "referrer_not_saved")
			{
				ee()->db->where('referrer_url', 'referrer_not_tracked');
			}
		}

		$url_query = ee()->db->get('hop_404_reporter_urls', $this->_perpage, $this->_offset);

		$url = $url_query->result_array();
		
		//Count all possible results
		ee()->db->select('count(*) AS count')
			->from('hop_404_reporter_urls')
			->where($sql_filter_where, NULL, TRUE);
		if ($this->_date_range_filter)
		{
			$datetime = new DateTime();
			$datetime->setTimestamp(ee()->localize->now);
			$datetime->sub(new DateInterval('P'.$this->_date_range_filter.'D'));
			ee()->db->where('last_occurred >', $datetime->format('Y-m-d H:i:s'));
		}
		if ($this->_referrer_url_filter)
		{
			if ($this->_referrer_url_filter == "referrer_saved")
			{
				ee()->db->where('referrer_url !=', 'referrer_not_specified');
				ee()->db->where('referrer_url !=', 'referrer_not_tracked');
			}
			else if ($this->_referrer_url_filter == "no_referrer")
			{
				ee()->db->where('referrer_url', 'referrer_not_specified');
			}
			else if ($this->_referrer_url_filter == "referrer_not_saved")
			{
				ee()->db->where('referrer_url', 'referrer_not_tracked');
			}
		}
		$query = ee()->db->get();
		$query_result_array = $query->result_array();
		$count = intval($query_result_array[0]['count']);
		
		//Additional stuff on the results
		$rows = array();
		while ($c = array_shift($url))
		{
			//print_r($c);
			$c["_check"] = form_checkbox('toggle[]', $c["url_id"], FALSE, 'class="toggle"');
			if ($c["last_occurred"])
			{
				$last_occurred = new DateTime($c["last_occurred"]);
				
				$c["last_occurred"] = $last_occurred->format(lang('datetime_format'));
			}
			else
			{
				$c["last_occurred"] = lang('no_last_occur');
			}
			if (in_array($c["referrer_url"], Hop_404_reporter_helper::get_referrer_url_globals()) )
			{
				$c["referrer_url"] = lang($c["referrer_url"]);
			}
			else
			{
				$c["referrer_url"] = '<a target="_blank" href="'.ee()->cp->masked_url($c["referrer_url"]).'">'.$c["referrer_url"].'</a>';
			}
			$rows[] = (array) $c;
		}

		return array(
			'rows' => (array) $rows,
			'no_results' => lang('no_results'),
			'pagination' => array(
				'per_page' => $this->_perpage,
				'total_rows' => $count
			)
		);
	}
	
	/**
	 * Receive and process POST data from list page
	 **/
	function modify_urls()
	{
		$urls_to_modify = ee()->input->post('toggle');
		
		//print_r($urls_to_modify);
		
		$count = 0;
		foreach($urls_to_modify as $url_id)
		{
			if (ee()->input->post('action') == "delete")
			{
				ee()->db->delete('hop_404_reporter_urls', array('url_id' => $url_id));
				$count++;
			}
		}
		
		if (ee()->input->post('action') == "delete")
		{
			ee()->session->set_flashdata('message_success', sprintf(lang('url_deleted_message'), $count));
		}
		ee()->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter');
	}
	
	/**
	 * Displays list of email to be notified when 404 occurs
	 */
	function display_emails()
	{
		$this->build_nav();
		ee()->view->cp_page_title = lang('email_list_pagetitle');
		
		ee()->load->library('pagination');
		ee()->load->library('javascript');
		ee()->load->library('table');
		ee()->load->helper('form');
		
		$columns = array(
			'email_address' 	=> array('header' => lang('email_address')),
			'url_to_match'		=> array('header' => lang('url_to_match')),
			'interval'			=> array('header' => lang('interval')),
			'_check'			=> array(
				'header' => form_checkbox('select_all', 'true', FALSE, 'class="toggle_all" id="select_all"'),
				'sort' => FALSE
			)
		);
		
		$filter_base_url = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter'.AMP.'method=display_emails';

		ee()->table->set_base_url($filter_base_url);
		ee()->table->set_columns($columns);

		$params = array('perpage' => $this->_perpage);
		$defaults = array('sort' => array('email_address' => 'desc'));

		$vars = ee()->table->datasource('_email_data', $defaults, $params);
		
		$vars['action_url'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter'.AMP.'method=modify_emails';
    	$vars['form_hidden'] = NULL;
		
		$vars['options'] = array(
			'reset'  	=> lang('email_reset_selected'),
			'delete'    => lang('delete_selected')
		);
		$vars["add_email_notif_action"] = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter'.AMP.'method=add_email';
		
		$vars["filter_keywords"] = $this->_keywords;
		$vars["filter_interval_selected"] = $this->_interval_notification_filter;
		$vars["filter_interval_options"] = $this->_get_filter_email_notification_interval_options();
		
		// View related stuff
		ee()->javascript->output(array(
			'$(".toggle_all").toggle(
				function(){
					$("input.toggle").each(function() {
						this.checked = true;
					});
				}, function (){
					var checked_status = this.checked;
					$("input.toggle").each(function() {
						this.checked = false;
					});
				}
			);
			$(function() {
				$(".pageContents").css("overflow", "auto");
				//Setup dynamic table filtering
				Hop404Reporter_cp.setup_tables();
			});
			'
		));
		ee()->cp->add_js_script(array('plugin' => 'dataTables'));
		ee()->javascript->compile();
		
		return ee()->load->view('emails', $vars, TRUE);
	}
	
	/**
	 * Get the email notifications data
	 */
	function _email_data($state, $params)
	{
		//Do the sorting 
		$this->_sort = $state['sort'];
		$this->_offset = $state['offset'];
		
		foreach ($this->_sort as $col => $dir)
		{
			ee()->db->order_by($col, $dir);
		}

		//Filtering
		$this->_keywords = ee()->input->get_post('keywords');
		$this->_interval_notification_filter = ee()->input->get_post('interval_f');
		$sql_filter_where = "(`email_address` LIKE '%".ee()->db->escape_like_str($this->_keywords)."%' OR `url_to_match` LIKE '%".ee()->db->escape_like_str($this->_keywords)."%' )";
		ee()->db->where($sql_filter_where, NULL, TRUE);
		
		if ($this->_interval_notification_filter)
		{
			if ($this->_interval_notification_filter == "interval_always")
			{
				ee()->db->where('interval', 'always');
			}
			else if ($this->_interval_notification_filter == "interval_once")
			{
				ee()->db->where('interval', 'once');
			}
		}

		$email_query = ee()->db->get('hop_404_reporter_emails', $this->_perpage, $this->_offset);

		$emails = $email_query->result_array();
		
		//Count all possible results
		ee()->db->select('count(*) AS count')
			->from('hop_404_reporter_emails')
			->where($sql_filter_where, NULL, TRUE);
		if ($this->_interval_notification_filter)
		{
			if ($this->_interval_notification_filter == "interval_always")
			{
				ee()->db->where('interval', 'always');
			}
			else if ($this->_interval_notification_filter == "interval_once")
			{
				ee()->db->where('interval', 'once');
			}
		}
		$query = ee()->db->get();
		$query_result_array = $query->result_array();
		$count = intval($query_result_array[0]['count']);
		
		//Additional stuff on the results
		$rows = array();
		while ($c = array_shift($emails))
		{
			//print_r($c);
			$c["_check"] = form_checkbox('toggle[]', $c["email_id"], FALSE, 'class="toggle"');
			if (in_array($c["interval"], Hop_404_reporter_helper::get_email_notification_globals()) )
			{
				$c["interval"] = lang('email_notif_interval_'.$c["interval"]);
			}
			else
			{
				//Invalid interval
				$c["interval"] = lang('email_notif_interval_invalid');
			}
			$rows[] = (array) $c;
		}

		return array(
			'rows' => (array) $rows,
			'no_results' => lang('no_emails_results'),
			'pagination' => array(
				'per_page' => $this->_perpage,
				'total_rows' => $count
			)
		);
	}
	
	/**
	 * Receive and process POST data from email list page
	 **/
	function modify_emails()
	{
		$emails_to_modify = ee()->input->post('toggle');
		
		//print_r($emails_to_modify);
		
		$count = 0;
		foreach($emails_to_modify as $email_id)
		{
			if (ee()->input->post('action') == "delete")
			{
				ee()->db->delete('hop_404_reporter_emails', array('email_id' => $email_id));
				$count++;
			}
			else if (ee()->input->post('action') == "reset")
			{
				ee()->db->update('hop_404_reporter_emails', array('parameter' => ''), array('email_id' => $email_id));
				$count++;
			}
		}
		
		if (ee()->input->post('action') == "delete")
		{
			ee()->session->set_flashdata('message_success', sprintf(lang('email_deleted_message'), $count));
		}
		else if (ee()->input->post('action') == "reset")
		{
			ee()->session->set_flashdata('message_success', sprintf(lang('email_reset_message'), $count));
		}
		ee()->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter'.AMP.'method=display_emails');
	}
	
	function add_email()
	{
		$this->build_nav();
		ee()->view->cp_page_title = lang('email_list_pagetitle');
		
		$vars = array();
		
		//If we have POST data, try to save the new email notification
		if (ee()->input->post('action') == 'add_email')
		{
			$form_is_valid = TRUE;
			$email_str = ee()->input->post('email_address', TRUE);
			if (!filter_var($email_str, FILTER_VALIDATE_EMAIL)) {
				$form_is_valid = FALSE;
				$vars["form_error_email"] = "email error";
				$vars["form_value_email"] = $email_str;
			}
			
			$regex_str = ee()->input->post('url_filter', TRUE);
			//No real tests to do...
			$vars["form_value_url_filter"] = $regex_str;
			
			$interval_str = ee()->input->post('notification_interval', TRUE);
			if ( !in_array($interval_str, Hop_404_reporter_helper::get_email_notification_globals()) )
			{
				$form_is_valid = FALSE;
				$vars["form_error_interval"] = "interval error";
				
			}
			
			if ($form_is_valid)
			{
				$data = array (
					"email_address" => $email_str,
					"url_to_match"	=> $regex_str,
					"interval"		=> $interval_str
				);
				ee()->db->insert('hop_404_reporter_emails', $data);
				
				ee()->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter'.AMP.'method=display_emails');
			}
		}
		
		$vars['action_url'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter'.AMP.'method=add_email';
    	$vars['form_hidden'] = array('action' => 'add_email');
		
		return ee()->load->view('add_email', $vars, TRUE);
	}
	
	/**
	 * Displays configuration panel
	 */
	function settings()
	{
		$this->build_nav();
		ee()->view->cp_page_title = lang('settings_pagetitle');
		
		$vars = array();
		
		if (ee()->input->post('action') == "save_settings")
		{
			$settings = array();
			$form_is_valid = TRUE;
			if (ee()->input->post('enabled') == 'y')
			{
				$settings["enabled"] = 'y';
			}
			else
			{
				$settings["enabled"] = 'n';
			}
			
			if (ee()->input->post('referrer_tracking') == 'y')
			{
				$settings["referrer_tracking"] = 'y';
			}
			else
			{
				$settings["referrer_tracking"] = 'n';
			}
			
			if (ee()->input->post('send_email_notifications') == 'y')
			{
				$settings["send_email_notifications"] = 'y';
			}
			else
			{
				$settings["send_email_notifications"] = 'n';
			}
			
			if ( filter_var(ee()->input->post('email_address_sender'), FILTER_VALIDATE_EMAIL) )
			{
				$settings["email_address_sender"] = ee()->input->post('email_address_sender');
			}
			else
			{
				$form_is_valid = FALSE;
				$settings["email_address_sender"] = ee()->input->post('email_address_sender');
				$vars["form_error_email_address_sender"] = lang('settings_form_error_email_address_sender');
			}
			
			if ( ee()->input->post('email_notification_subject') != "" )
			{
				$settings["email_notification_subject"] = ee()->input->post('email_notification_subject');
			}
			else
			{
				$form_is_valid = FALSE;
				$settings["email_notification_subject"] = ee()->input->post('email_notification_subject');
				$vars["form_error_email_notification_subject"] = lang('settings_form_error_email_notification_subject');
			}
			
			if (ee()->input->post('404_email_template') == '')
			{
				//We can't save an empty email template
				$form_is_valid = FALSE;
				$vars["form_error_email_template"] = lang('settings_form_error_no_template');
			}
			else
			{
				$settings["email_template"] = ee()->input->post('404_email_template');
			}
			
			if ($form_is_valid)
			{
				Hop_404_reporter_helper::save_settings($settings);
				ee()->session->set_flashdata('message_success', lang('settings_saved_success'));
				ee()->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter'.AMP.'method=settings');
			}
			else
			{
				$vars["settings"] = $settings;
			}
			
		}
		
		// No data received, means we'll load saved settings
		if (!isset($form_is_valid))
		{
			$vars["settings"] = Hop_404_reporter_helper::get_settings();
		}
		
		$vars['action_url'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter'.AMP.'method=settings';
    	$vars['form_hidden'] = array('action' => 'save_settings');
		
		//TODO : generate table using ee()->load->library('table'); Useful ? or not ?
		
		return ee()->load->view('settings', $vars, TRUE);
	}
	
	/**
	 * Displays the Support page with help and stuff
	 */
	public function support()
	{
		$this->build_nav();
		ee()->view->cp_page_title = lang('support_page_title');
		$vars = array();
		return ee()->load->view('support', $vars, TRUE);
	}
	
	/**
	 * Get the list of options to filter the URLs by date range
	 */
	protected function _get_filter_date_range_options()
	{
		return array(
			''	=> lang('filter_date_range'),
			1 	=> lang('filter_last_day'),
			7	=> lang('filter_last_week'),
			31	=> lang('filter_last_month'),
			92	=> lang('filter_last_3months'),
			182	=> lang('filter_last_6months'),
			365	=> lang('filter_last_year')
		);
	}
	
	/**
	 * Get the list of options to filter the URLs by Referrer
	 */
	protected function _get_filter_referrer_url_options()
	{
		return array(
			'*'					=> lang('filter_referrer_url'),
			'referrer_saved'	=> lang('filter_referrer_saved'),
			'no_referrer' 		=> lang('filter_no_referrer_url'),
			'referrer_not_saved'	=> lang('filter_referrer_url_not_saved')
		);
	}
	
	/**
	 * Get the list of options to filter the email notifications by Interval
	 */
	protected function _get_filter_email_notification_interval_options()
	{
		return array(
			'*'					=> lang('filter_interval'),
			'interval_always'	=> lang('email_notif_interval_always'),
			'interval_once'		=> lang('email_notif_interval_once')
		);
	}
}
// END CLASS