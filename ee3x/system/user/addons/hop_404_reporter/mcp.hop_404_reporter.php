<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use EllisLab\ExpressionEngine\Library\CP\Table;

require_once PATH_THIRD.'hop_404_reporter/helper.php';

class hop_404_reporter_mcp
{
	private $_base_url;
	private $_base_url_params;
	private $_perpage = 25;
	private $_page = 1;
	private $_offset = 0;
	private $_limit;
	private $_sort;
	private $_keywords;
	private $_date_range_filter;
	private $_referrer_url_filter;
	private $_interval_notification_filter;
	private $_filters;
	private $_filters_base_url;

	public function __construct()
	{
		$this->_base_url = ee('CP/URL')->make('addons/settings/hop_404_reporter');
		
		//Add our specific JS
		ee()->javascript->output(file_get_contents(PATH_THIRD.'hop_404_reporter/javascript/hop_404_reporter.js'));
	}

	/**
	 * Build the navigation menu for the module
	 */
	private function build_nav()
	{
		$sidebar = ee('CP/Sidebar')->make();

		$sd_div = $sidebar->addHeader(lang('nav_index'));
		$sd_div_list = $sd_div->addBasicList();
		$sd_div_list->addItem(lang('404_url_list_title'), ee('CP/URL', 'addons/settings/hop_404_reporter'));
		
		$sd_div = $sidebar->addHeader(lang('nav_emails'))
			->withButton(lang('new'), ee('CP/URL', 'addons/settings/hop_404_reporter/add_email'));
		$sd_div_list = $sd_div->addBasicList();
		$sd_div_list->addItem(lang('email_notifications_list'), ee('CP/URL', 'addons/settings/hop_404_reporter/display_emails'));
		
		$sd_div = $sidebar->addHeader(lang('nav_settings'));
		$sd_div_list = $sd_div->addBasicList();
		$sd_div_list->addItem(lang('settings'), ee('CP/URL', 'addons/settings/hop_404_reporter/settings'));
		$sd_div_list->addItem(lang('support_page_title'), ee('CP/URL', 'addons/manual/hop_404_reporter'));
		
	}

	//--------------------------------------------------------------------------
	//		  INDEX PAGE (URLs LIST)
	//--------------------------------------------------------------------------

	/*
	 * Because if no method found, this one will be returned
	 * We're making it the URLs list
	 */
	function index()
	{
		// If POST action, do that first
		if(ee()->input->post('action'))
		{
			$this->modify_urls();
		}
		
		$this->build_nav();
		$header = array(
			'title' 	=> lang('hop_404_reporter_module_name'),
			'form_url'	=> $this->_create_base_url_with_existing_parameters('sort_col', 'sort_dir', 'filter_by_ref_url', 'filter_by_date_range'),
			// 'toolbar_items' => array(
			// 	'settings' => array(
			//		 'href' => ee('CP/URL')->make('settings/template'),
			//		 'title' => lang('settings')
			//	 ),
			// ),
			'search_button_value' => lang('search_urls')
		);
		ee()->cp->load_package_css('hop_404');
		ee()->view->header = $header;

		ee()->load->library('table');
		ee()->load->library('pagination');
		ee()->load->library('javascript');
		ee()->load->helper('form');

		$table = ee('CP/Table', array('autosort' => FALSE, 'autosearch' => FALSE, 'limit' => $this->_perpage));
		$table->setColumns(
			array(
				'url',
				'referrer_url',
				'count',
				'last_occurred',
				array(
					'type'  => Table::COL_CHECKBOX
				)
			)
		);

		//--- Get Data ---
		
		//Setup query parameters (using GET/POST params...)
		$this->urls_query_setup();
		
		//Setup pagination params
		if (ee()->input->get('page') != NULL)
		{
			$this->_offset = $this->_perpage*(intval(ee()->input->get('page'))-1);
			$this->_page = intval(ee()->input->get('page'));
		}

		$url_query = ee()->db->get('hop_404_reporter_urls', $this->_perpage, $this->_offset);
		$urls = $url_query->result();

		// Process data and format it for Table
		$data = array();
		foreach ($urls as $url)
		{
			$data[] = array(
				$url->url,
				$url->referrer_url,
				$url->count,
				$url->last_occurred,
				array(
					'name' => 'urls[]',
					'value' => $url->url_id,
					'data'  => array(
						'confirm' => lang('url') . ': <b>' . htmlentities($url->url, ENT_QUOTES) . '</b>'
					)
				)
			);
		}
		$table->setData($data);

		$vars['table'] = $table->viewData($this->_create_base_url_with_existing_parameters(array('filter_by_date_range', 'filter_by_ref_url', 'search'), array('search')));

		// -- Pagination --
		// Get count
		ee()->db->select('count(*) AS count')
			->from('hop_404_reporter_urls');
		
		//Setup params (because we ran our first query, params need to be set again)
		$this->urls_query_setup();
		
		//Get results
		$query = ee()->db->get();
		$query_result_array = $query->result_array();
		$total_count = intval($query_result_array[0]['count']);

		$pagination = ee('CP/Pagination', $total_count);
		$pagination->perPage($this->_perpage);
		$pagination->currentPage($this->_page);

		$vars['pagination'] = $pagination->render(
			$this->_create_base_url_with_existing_parameters(
				array('sort_col', 'sort_dir', 'filter_by_ref_url', 'filter_by_date_range', 'search', 'filter_by_date', 'perpage'), 
				array('search', 'filter_by_date', 'perpage')
			)
		);

		// Default vars

		$vars['action_url'] = $this->_create_base_url_with_existing_parameters(
			array('sort_col', 'sort_dir', 'filter_by_ref_url', 'filter_by_date_range', 'search', 'filter_by_date', 'perpage'), 
			array('search', 'filter_by_date', 'perpage')
		);
		$vars['search_keywords'] = $this->_keywords;
		$vars['search_url'] = $this->_create_base_url_with_existing_parameters('sort_col', 'sort_dir', 'filter_by_ref_url', 'filter_by_date_range');
		$vars['form_hidden'] = NULL;

		$vars["filter_keywords"] = $this->_keywords;
		// $vars["filter_referrer_url_options"] = $this->_get_filter_referrer_url_options();
		// $vars["filter_referrer_url_selected"] = $this->_referrer_url_filter;
		// $vars["filter_date_range_options"] = $this->_get_filter_date_range_options();
		// $vars["filter_date_range_selected"] = $this->_date_range_filter;
		
		//Setup filters
		$this->setup_url_list_filters($total_count);
		$vars['filters'] = $this->_filters;
		$vars['filters_base_url'] = $this->_filters_base_url;

		// View related stuff
		ee()->cp->add_js_script(array(
			'file' 	=> 'cp/sort_helper',
			'plugin'=> 'ee_table_reorder',
			'file' 	=> array('cp/confirm_remove'),
		));
		ee()->javascript->compile();

		// return ee()->load->view('index', $vars, TRUE);
		return array(
			'heading'			=> lang('404_url_title'),
			'body'				=> ee('View')->make('hop_404_reporter:index')->render($vars),
			'breadcrumb'		=> array(
				ee('CP/URL', 'addons/settings/hop_404_reporter')->compile() => lang('hop_404_reporter_module_name')
			),
		);
	}
	
	/**
	 * Setup filters for the index page (URLs list)
	 * @return void
	 */
	private function setup_url_list_filters($total_count)
	{	
		//Build filters base url (keep some parameters)
		// In order to keep the search for later, we set it as a GET variable.
		$this->_filters_base_url = $this->_create_base_url_with_existing_parameters(array('sort_col', 'sort_dir', 'search'), array('search'));
		
		$referers = ee('CP/Filter')->make('filter_by_ref_url', 'filter_referrer_url', array(
			'referrer_saved'	=> lang('filter_referrer_saved'),
			'no_referrer' 		=> lang('filter_no_referrer_url'),
			'referrer_not_saved'=> lang('filter_referrer_url_not_saved')
		));
		$referers->disableCustomValue();
		
		$filters = ee('CP/Filter')
			->add($referers)
			// ->add($dates)
			->add('Date')
			->add('Perpage', $total_count)
			;
		
		// ee()->view->filters = $filters->render($this->_base_url);
		$this->_filters = $filters->render($this->_filters_base_url);
	}
	
	/**
	 * Will get parameters and add proper query parameters
	 * @return void
	 */
	private function urls_query_setup()
	{
		// Get parameters
		if (ee()->input->get('sort_col') != NULL)
		{
			if (ee()->input->get('sort_dir') != NULL && ee()->input->get('sort_dir') == "desc")
			{
				ee()->db->order_by(ee()->input->get('sort_col'), 'DESC');
			}
			else
			{
				ee()->db->order_by(ee()->input->get('sort_col', 'ASC'));
			}
		}

		$search_phrase = NULL;
		// We verify POST first, because it's what is sent by the search form
		if (ee()->input->post('search') != NULL && ee()->input->post('search') != "")
		{
			$search_phrase = ee()->input->post('search');
		}
		else if	(ee()->input->get('search') != NULL && ee()->input->get('search') != "")
		{
			$search_phrase = ee()->input->get('search');
		}

		if ($search_phrase)
		{
			$this->_keywords = $search_phrase;
			$sql_filter_where = "(`url` LIKE '%".ee()->db->escape_like_str($search_phrase)."%' OR `referrer_url` LIKE '%".ee()->db->escape_like_str($search_phrase)."%' )";
			ee()->db->where($sql_filter_where, NULL, TRUE);
		}
		
		if (ee()->input->get('filter_by_ref_url'))
		{
			$referrer_param = ee()->input->get('filter_by_ref_url');
			if ($referrer_param == "referrer_saved")
			{
				ee()->db->where('referrer_url !=', 'referrer_not_specified');
				ee()->db->where('referrer_url !=', 'referrer_not_tracked');
			}
			else if ($referrer_param == "no_referrer")
			{
				ee()->db->where('referrer_url', 'referrer_not_specified');
			}
			else if ($referrer_param == "referrer_not_saved")
			{
				ee()->db->where('referrer_url', 'referrer_not_tracked');
			}
		}
		
		if (ee()->input->get('filter_by_date_range'))
		{
			$days = intval(ee()->input->get('filter_by_date_range'));
			$datetime = new DateTime();
			$datetime->setTimestamp(ee()->localize->now);
			$datetime->sub(new DateInterval('P'.$days.'D'));
			ee()->db->where('last_occurred >', $datetime->format('Y-m-d H:i:s'));
		}
		
		// In case user clicked on a filter
		// The value is a number of seconds if user clicked on a default filter
		// OR the value is a custom date that was inputed by the user
		// if (ee()->input->get('filter_by_date') && !ee()->input->post('filter_by_date'))
		// {
		// 	if (ctype_digit(ee()->input->get('filter_by_date')))
		// 	{
		// 		$seconds = ee()->input->get('filter_by_date');
		// 		$days = $seconds/3600/24;
		// 		$datetime = new DateTime();
		// 		$datetime->setTimestamp(ee()->localize->now);
		// 		$datetime->sub(new DateInterval('P'.$days.'D'));
		// 		ee()->db->where('last_occurred >', $datetime->format('Y-m-d H:i:s'));
		// 	}
		// 	else
		// 	{
		// 		if (!is_array(ee()->input->get('filter_by_date')))
		// 		{
		// 			// var_dump(ee()->input->get('filter_by_date'));
		// 			$datetime = new DateTime(ee()->input->get('filter_by_date'));
		// 			ee()->db->where('last_occurred >', $datetime->format('Y-m-d H:i:s'));
		// 		}
		// 		
		// 	}
		// 	
		// }
		// 
		// // In case user input a custom value
		// // The value is a date M/D/Y (I hate that format)
		// // Note : we will pass this along as a GET var for pagination
		// if (ee()->input->post('filter_by_date'))
		// {
		// 	$datetime = new DateTime(ee()->input->post('filter_by_date'));
		// 	ee()->db->where('last_occurred >', $datetime->format('Y-m-d H:i:s'));
		// }
		
		// Setup temporary filters to automatically retrieve good values
		$filters = ee('CP/Filter')->add('Date');
		$values = $filters->values();
		$date_value = $values['filter_by_date'];
		
		if ($date_value)
		{
			if (is_array($date_value))
			{
				$dt_from = new DateTime();
				$dt_from->setTimestamp(($date_value[0] + 0));
				$dt_to = new DateTime();
				$dt_to->setTimestamp(($date_value[1] + 0));
				ee()->db->where('last_occurred >=', $dt_from->format('Y-m-d H:i:s'));
				ee()->db->where('last_occurred <', $dt_to->format('Y-m-d H:i:s'));
			}
			else
			{
				$days = $date_value/3600/24;
				$datetime = new DateTime();
				$datetime->setTimestamp(ee()->localize->now);
				$datetime->sub(new DateInterval('P'.$days.'D'));
				ee()->db->where('last_occurred >', $datetime->format('Y-m-d H:i:s'));
			}
		}
		
		
		if (ee()->input->get('perpage') && !ee()->input->post('perpage'))
		{
			$this->_perpage = intval(ee()->input->get('perpage'));
		}
		// In case user input a custom value
		// Note : we will pass this along as a GET var for pagination
		if (ee()->input->post('perpage'))
		{
			$this->_perpage = intval(ee()->input->post('perpage'));
		}
	}

	/**
	 * Receive and process POST data from list page
	 **/
	function modify_urls()
	{
		$urls_to_modify = ee()->input->post('urls');

		if ($urls_to_modify == NULL || !is_array($urls_to_modify))
		{
			ee()->functions->redirect(ee('CP/URL')->make('addons/settings/hop_404_reporter'));
		}

		$count = 0;
		foreach($urls_to_modify as $url_id)
		{

			if (ee()->input->post('bulk_action') == "delete")
			{
				ee()->db->delete('hop_404_reporter_urls', array('url_id' => $url_id));
				$count++;
			}
		}

		if (ee()->input->post('bulk_action') == "delete")
		{
			ee('CP/Alert')
				->makeInline('url_deleted_success')
				->asSuccess()
				->withTitle(lang('url_deleted_success'))
				->addToBody(sprintf(lang('url_deleted_message'), $count))
				->defer();
		}
		ee()->functions->redirect(ee('CP/URL')->make('addons/settings/hop_404_reporter'));
	}
	
	
	//--------------------------------------------------------------------------
	//		  DISPLAY EMAILS LIST (email notifications)
	//		  DISPLAY ADD EMAIL PAGE
	//--------------------------------------------------------------------------

	/**
	 * Displays list of email to be notified when 404 occurs
	 */
	function display_emails()
	{
		$this->build_nav();
		$this->_base_url = ee('CP/URL')->make('addons/settings/hop_404_reporter/display_emails');
		$header = array(
			'title' 	=> lang('hop_404_reporter_module_name'),
			'form_url'	=> $this->_create_base_url_with_existing_parameters('sort_col', 'sort_dir', 'filter_by_ref_url', 'filter_by_date_range'),
			// 'toolbar_items' => array(
			//	 'settings' => array(
			//		 'href' => ee('CP/URL')->make('settings/template'),
			//		 'title' => lang('settings')
			//	 ),
			// ),
			'search_button_value' => lang('search_emails_notif')
		);
		ee()->view->header = $header;

		ee()->cp->load_package_css('hop_404');

		ee()->load->library('pagination');
		ee()->load->library('javascript');
		ee()->load->helper('form');
		
		$table = ee('CP/Table', array('autosort' => FALSE, 'autosearch' => FALSE, 'limit' => $this->_perpage));
		$table->setColumns(
			array(
				'email_address',
				'url_to_match',
				'interval',
				array(
					'type'  => Table::COL_CHECKBOX
				)
			)
		);
		$table->setNoResultsText(sprintf(lang('no_found'), lang('email_notifications')), 'create_new_one', ee('CP/URL')->make('addons/settings/hop_404_reporter/add_email'));
		
		//--- Get Data ---

		// Get parameters
		$this->email_notification_query_setup();

		if (ee()->input->get('page') != NULL)
		{
			$this->_offset = $this->_perpage*(intval(ee()->input->get('page'))-1);
			$this->_page = intval(ee()->input->get('page'));
		}

		$emails_query = ee()->db->get('hop_404_reporter_emails', $this->_perpage, $this->_offset);
		$emails = $emails_query->result();
		
		// Process data and format it for Table
		$data = array();
		foreach ($emails as $email)
		{
			$data[] = array(
				$email->email_address,
				$email->url_to_match,
				$email->interval,
				array(
					'name' => 'emails[]',
					'value' => $email->email_id,
					'data'  => array(
						'confirm' => lang('email') . ': <b>' . htmlentities($email->email_address, ENT_QUOTES) . '</b>'
					)
				)
			);
		}
		$table->setData($data);

		$vars['table'] = $table->viewData($this->_create_base_url_with_existing_parameters(array('filter_by_interval', 'search'), array('search')));

		$vars['search_keywords'] = $this->_keywords;
		$vars['search_url'] = $this->_create_base_url_with_existing_parameters('sort_col', 'sort_dir', 'filter_by_ref_url', 'filter_by_date_range');
		
		//Setup pagination
		ee()->db->select('count(*) AS count')
			->from('hop_404_reporter_emails');
		// Setup query params
		$this->email_notification_query_setup();
		$query = ee()->db->get();
		$query_result_array = $query->result_array();
		$total_count = intval($query_result_array[0]['count']);

		$pagination = ee('CP/Pagination', $total_count);
		$pagination->perPage($this->_perpage);
		$pagination->currentPage($this->_page);

		$vars['pagination'] = $pagination->render($this->_create_base_url_with_existing_parameters(array('filter_by_interval', 'search'), array('search')));

		$vars['action_url'] = ee('CP/URL', 'addons/settings/hop_404_reporter/modify_emails');
		$vars['form_hidden'] = NULL;

		$vars['options'] = array(
			'reset'  	=> lang('email_reset_selected'),
			'delete'	=> lang('delete_selected')
		);

		$vars["filter_keywords"] = $this->_keywords;
		//$vars["filter_interval_selected"] = $this->_interval_notification_filter;
		//$vars["filter_interval_options"] = $this->_get_filter_email_notification_interval_options();
		
		//Setup filters
		$this->setup_email_list_filters();
		$vars['filters'] = $this->_filters;

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

		// return ee()->load->view('emails', $vars, TRUE);
		return array(
			'heading'		=> lang('email_list_pagetitle'),
			'body'			=> ee('View')->make('hop_404_reporter:emails')->render($vars),
			'breadcrumb'	=> array(
				ee('CP/URL', 'addons/settings/hop_404_reporter')->compile() => lang('hop_404_reporter_module_name')
			),
		);
	}
	
	/**
	 * Create filter options for the email notifications list
	 * @return void
	 */
	private function setup_email_list_filters()
	{	
		//Build filters base url (keep some parameters)
		$this->_filters_base_url = $this->_create_base_url_with_existing_parameters(array('sort_col', 'sort_dir', 'search'), array('search'));
		
		$intervals = ee('CP/Filter')->make('filter_by_interval', 'filter_interval', array(
			'interval_always'	=> lang('email_notification_interval_always'),
			'interval_once'		=> lang('email_notification_interval_once')
		));
		$intervals->disableCustomValue();
		
		$filters = ee('CP/Filter')
			->add($intervals);
		
		// ee()->view->filters = $filters->render($this->_base_url);
		$this->_filters = $filters->render($this->_filters_base_url);
	}
	
	/**
	 * Setup query parameters for email notifications list
	 * @return void
	 */
	private function email_notification_query_setup()
	{
		// Get parameters
		if (ee()->input->get('sort_col') != NULL)
		{
			if (ee()->input->get('sort_dir') != NULL && ee()->input->get('sort_dir') == "desc")
			{
				ee()->db->order_by(ee()->input->get('sort_col'), 'DESC');
			}
			else
			{
				ee()->db->order_by(ee()->input->get('sort_col', 'ASC'));
			}
		}

		$search_phrase = NULL;
		// We verify POST first, because it's what is sent by the search form
		if (ee()->input->post('search') != NULL && ee()->input->post('search') != "")
		{
			$search_phrase = ee()->input->post('search');
		}
		else if	(ee()->input->get('search') != NULL && ee()->input->get('search') != "")
		{
			$search_phrase = ee()->input->get('search');
		}
		
		if ($search_phrase)
		{
			$this->_keywords = $search_phrase;
			$sql_filter_where = "(`email_address` LIKE '%".ee()->db->escape_like_str($search_phrase)."%' OR `url_to_match` LIKE '%".ee()->db->escape_like_str($search_phrase)."%' )";
			ee()->db->where($sql_filter_where, NULL, TRUE);
		}
		
		if (ee()->input->get('filter_by_interval'))
		{
			$interval = ee()->input->get('filter_by_interval');
			if ($interval == "interval_always")
			{
				ee()->db->where('interval', 'always');
			}
			else if ($interval == "interval_once")
			{
				ee()->db->where('interval', 'once');
			}
		}
		
		
	}

	/**
	 * Receive and process POST data from email list page
	 **/
	function modify_emails()
	{
		$emails_to_modify = ee()->input->post('emails');

		$count = 0;
		foreach($emails_to_modify as $email_id)
		{
			if (ee()->input->post('bulk_action') == "delete")
			{
				ee()->db->delete('hop_404_reporter_emails', array('email_id' => $email_id));
				$count++;
			}
			else if (ee()->input->post('bulk_action') == "reset")
			{
				// ee()->db->update('hop_404_reporter_emails', array('parameter' => ''), array('email_id' => $email_id));
				// We have to remove the email from all URLs where a notification have been sent
				$query = ee()->db->select(array('url_id', 'notification_to'))
					->from('hop_404_reporter_urls')
					->like('notification_to', 'i:'.$email_id.'')
					->get();

				$results_urls = $query->result_array();

				foreach ($results_urls as $result_url)
				{
					$serialized_notification_to = $result_url['notification_to'];
					$notification_to = unserialize($serialized_notification_to);
					if ($notification_to && array_key_exists($email_id, $notification_to))
					{
						unset($notification_to[$email_id]);
						ee()->db->update('hop_404_reporter_urls', 
							array('notification_to' => serialize($notification_to)), 
							array('url_id' => $result_url['url_id'])
						);
					}
				}
				$count++;
			}
		}

		if (ee()->input->post('bulk_action') == "delete")
		{
			ee('CP/Alert')
				->makeInline('email_deleted_success')
				->asSuccess()
				->withTitle(lang('email_deleted_success'))
				->addToBody(sprintf(lang('email_deleted_message'), $count))
				->defer();
		}
		else if (ee()->input->post('bulk_action') == "reset")
		{
			ee('CP/Alert')
				->makeInline('email_reseted_success')
				->asSuccess()
				->withTitle(lang('email_reseted_success'))
				->addToBody(sprintf(lang('email_reset_message'), $count))
				->defer();
		}
		ee()->functions->redirect(ee('CP/URL')->make('addons/settings/hop_404_reporter/display_emails'));
	}

	/**
	 * Create an email notification
	 * @return Page cotent or page redirection
	 */
	function add_email()
	{
		$this->build_nav();
		$header = array(
			'title' 	=> lang('hop_404_reporter_module_name'),
		);
		ee()->view->header = $header;
		
		$vars = array(
			'cp_page_title' => lang('email_create_notification'),
			'base_url' => ee('CP/URL', 'addons/settings/hop_404_reporter/add_email')->compile(),
			'save_btn_text' => lang('settings_save'),
			'save_btn_text_working' => lang('settings_save_working'),
		);
		
		// Using EE3 API to create config form
		$vars['sections'] = array(
			array(
				array(
					'title' => 'email_notification_email_label',
					'desc' => 'email_notification_email_desc',
					'fields' => array(
						'email_address' => array('type' => 'text', 'required' => 'true', 'value' => '')
					)
				),
				array(
					'title' => 'email_notification_url_label',
					'desc' => 'email_notification_url_desc',
					'fields' => array(
						'url_to_match' => array('type' => 'text', 'value' => '')
					)
				),
				array(
					'title' => 'email_notification_interval_label',
					'desc' => 'email_notification_interval_desc',
					'fields' => array(
						'interval' => array('type' => 'select', 'required' => 'true', 'choices' => array('once' => lang('email_notification_interval_once'), 'always' => lang('email_notification_interval_always')))
					)
				),
				array(
					'title' => '',
					'fields' => array(
						'action' => array('type' => 'hidden', 'value' => 'add_email')
					)
				),
			)
		);
		
		//If we have POST data, try to save the new email notification
		if (ee()->input->post('action') == 'add_email')
		{
			// Validation
			$validator = ee('Validation')->make();
			
			$validator->defineRule('notif_interval', function($key, $value, $parameters)
			{
				if (!in_array($value, Hop_404_reporter_helper::get_email_notification_globals()))
				{
					return lang('email_notificaiton_interval_invalid');
				}
				return TRUE;
			});
			
			$validator->setRules(array(
				'email_address' => 'required|email',
				'interval' => 'required|notif_interval'
			));
			$result = $validator->validate($_POST);
			
			if ($result->isValid())
			{
				// Get back all values, store them in array and save them
				$fields = array();
				foreach ($vars['sections'] as $settings)
				{
					foreach ($settings as $setting)
					{
						foreach ($setting['fields'] as $field_name => $field)
						{
							$fields[$field_name] = ee()->input->post($field_name);
						}
					}
				}
				// We don't want to save that field, it's not a setting
				unset($fields['action']);
				
				ee()->db->insert('hop_404_reporter_emails', $fields);
				ee('CP/Alert')
					->makeInline('shared-form')
					->asSuccess()
					->withTitle(lang('emaill_notification_add_success'))
					->addToBody(lang('emaill_notification_add_success_desc'))
					->defer();

				ee()->functions->redirect(ee('CP/URL', 'addons/settings/hop_404_reporter/display_emails')->compile());
			}
			else
			{
				$vars['errors'] = $result;
				ee('CP/Alert')
					->makeInline('shared-form')
					->asIssue()
					->withTitle(lang('settings_save_error'))
					->addToBody(lang('settings_save_error_desc'))
					->now();
			}
		}

		// return ee()->load->view('add_email', $vars, TRUE);
		return array(
			'heading'		=> lang('email_create_notification'),
			'body'			=> ee('View')->make('hop_404_reporter:add_email')->render($vars),
			'breadcrumb'	=> array(
				ee('CP/URL', 'addons/settings/hop_404_reporter')->compile() => lang('hop_404_reporter_module_name')
			),
		);
	}
	
	//--------------------------------------------------------------------------
	//
	//		  DISPLAY & SAVE SETTINGS PAGE
	//
	//--------------------------------------------------------------------------

	/**
	 * Displays configuration panel
	 */
	function settings()
	{
		$this->build_nav();
		$header = array(
			'title' 	=> lang('hop_404_reporter_module_name'),
		);
		ee()->view->header = $header;
		
		$settings = Hop_404_reporter_helper::get_settings();
		
		$vars = array(
			'cp_page_title' => lang('settings_pagetitle'),
			'base_url' => ee('CP/URL', 'addons/settings/hop_404_reporter/settings')->compile(),
			'save_btn_text' => lang('settings_save'),
			'save_btn_text_working' => lang('settings_save_working'),
		);
		
		// Using EE3 API to create config form
		$vars['sections'] = array(
			array(
				array(
					'title' => 'set_enabled',
					'desc' => 'set_enabled_desc',
					'fields' => array(
						'enabled' => array('type' => 'yes_no', 'value' => $settings["enabled"])
					)
				),
				array(
					'title' => 'set_send_email_notifications',
					'desc' => 'set_send_email_notifications_desc',
					'fields' => array(
						'send_email_notifications' => array('type' => 'yes_no', 'value' => $settings["send_email_notifications"])
					)
				),
				array(
					'title' => 'set_referrer_tracking',
					'desc' => 'set_referrer_tracking_desc',
					'fields' => array(
						'referrer_tracking' => array('type' => 'yes_no', 'value' => $settings["referrer_tracking"])
					)
				),
				array(
					'title' => 'set_email_address_sender',
					'desc' => 'set_email_address_sender_desc',
					'fields' => array(
						'email_address_sender' => array('type' => 'text', 'value' => $settings["email_address_sender"])
					)
				),
				array(
					'title' => 'set_email_notification_subject',
					'desc' => 'set_email_notification_subject_desc',
					'fields' => array(
						'email_notification_subject' => array('type' => 'text', 'value' => $settings["email_notification_subject"], 'required' => true)
					)
				),
				array(
					'title' => 'set_404_email_template',
					'desc' => 'set_404_email_template_desc',
					'fields' => array(
						'email_template' => array('type' => 'textarea', 'value' => $settings["email_template"], 'required' => true)
					)
				),
				array(
					'title' => '',
					'fields' => array(
						'action' => array('type' => 'hidden', 'value' => 'save_settings')
					)
				),
			)
		);
		

		if (ee()->input->post('action') == "save_settings")
		{
			$settings = array();
			$form_is_valid = TRUE;
			
			// Validation
			$validator = ee('Validation')->make();
			
			$validator->setRules(array(
				'enabled' => 'enum[y,n]',
				'send_email_notifications' => 'enum[y,n]',
				'referrer_tracking' => 'enum[y,n]',
				'email_address_sender' => 'email',
				'email_notification_subject' => 'required',
				'email_template' => 'required'
			));
			$result = $validator->validate($_POST);
			
			if ($result->isValid())
			{
				// Get back all values, store them in array and save them
				$fields = array();
				foreach ($vars['sections'] as $settings)
				{
					foreach ($settings as $setting)
					{
						foreach ($setting['fields'] as $field_name => $field)
						{
							$fields[$field_name] = ee()->input->post($field_name);
						}
					}
				}
				// We don't want to save that field, it's not a setting
				unset($fields['action']);
				
				Hop_404_reporter_helper::save_settings($fields);
				ee('CP/Alert')->makeInline('shared-form')
					->asSuccess()
					->withTitle(lang('preferences_updated'))
					->addToBody(lang('preferences_updated_desc'))
					->defer();

				ee()->functions->redirect(ee('CP/URL', 'addons/settings/hop_404_reporter/settings')->compile());
			}
			else
			{
				$vars['errors'] = $result;
				ee('CP/Alert')->makeInline('shared-form')
					->asIssue()
					->withTitle(lang('settings_save_error'))
					->addToBody(lang('settings_save_error_desc'))
					->now();
				$vars["settings"] = $settings;
			}

		} // ENDIF action = save_settings

		return array(
			'heading'			=> lang('settings'),
			'body'				=> ee('View')->make('hop_404_reporter:settings')->render($vars),
			'breadcrumb'	=> array(
			  ee('CP/URL', 'addons/settings/hop_404_reporter')->compile() => lang('hop_404_reporter_module_name')
			),
		);
	}
	
	//--------------------------------------------------------------------------
	//		  GLOBAL METHODS
	//--------------------------------------------------------------------------
	
	/**
	 * This is building a base url including already existing parameters.
	 * @param  array	$parameters	Array of names of parameters to keep in the url
	 * @return string			 [description]
	 */
	protected function _create_base_url_with_existing_parameters($parameters, $post_parameters = array())
	{
		$base_url_with_parameters = clone $this->_base_url;
		if (!is_array($parameters))
		{
			return $base_url_with_parameters;
		}
		
		// We prioritize POST data
		if ($post_parameters != NULL && count($post_parameters) != 0)
		{
			foreach ($post_parameters as $parameter)
			{
				if (ee()->input->post($parameter))
				{
					$base_url_with_parameters->setQueryStringVariable($parameter, ee()->input->post($parameter));
					
					// Remove it from GET data
					unset($parameters[$parameter]);
				}
			}
		}
		
		foreach ($parameters as $parameter)
		{
			if (ee()->input->get($parameter))
			{
				$base_url_with_parameters->setQueryStringVariable($parameter, ee()->input->get($parameter));
			}
		}
		
		return $base_url_with_parameters;
	}
}
// END CLASS
