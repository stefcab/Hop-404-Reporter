<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$lang = array(

	//Required for MODULES page

	'hop_404_reporter_module_name'			=> 'Hop 404 Reporter',
	'hop_404_reporter_module_description'	=> 'Manage all 404 errors happening on your website',

	//Additional Key => Value pairs
	'referrer'						=> 'Referrer URL',
	'referrer_not_tracked'	=> 'Referrer not tracked',
	'referrer_not_specified'=> 'Referrer not specified',
	'count'								=> 'Count',
	'datetime_format'			=> 'Y-m-d h:i:sA',
	'last_occurred_date' 	=> 'Date of last occurrence',


	//Index view
	'404_url_title'				=> '404 URLs',
	'404_url_list_description'=> '<p>This list contains all 404 URLs that occured on your website.</p>',
	'url'									=> 'URL',
	'no_results'					=> 'No URLs found',
	'no_matching_urls'		=> 'Sorry, we didn\'t find any matching URLs.',
	'no_last_occur'				=> 'Sorry, no date saved',
	'delete_selected'			=> 'Delete selected',
	'url_deleted_message'	=> '%d URL(s) have been deleted',
	'filter_urls'					=> 'Filter URLs',
	'keywords'						=> 'Keywords',
	'filter_date_range'		=> 'Date range',
	'filter_last_day'			=> 'Last day',
	'filter_last_week'		=> 'Last week',
	'filter_last_month'		=> 'Last month',
	'filter_last_3months'	=> 'Last 3 months',
	'filter_last_6months'	=> 'Last 6 months',
	'filter_last_year'		=> 'Last year',
	'filter_referrer_url'	=> 'Filter by referrer URL',
	'filter_no_referrer_url' => 'Referrer not specified',
	'filter_referrer_url_not_saved' => 'Referrer not tracked',
	'filter_referrer_saved' => 'Referrer specified',
	'--with_selected--'		=> '-- with selected --',

	//Email list view
	'email_list_pagetitle'	=> 'Hop 404 Reporter > Email notifications',
	'email_notifications'		=> 'Email Notifications',
	'email_page_title'			=> 'Email to notify',
	'email_page_description'=> '<p>Hop 404 reporter can send notifications to email addresses each time a 404 occurs.</p>',
	'filter_email_notifications'=> 'Filter Email Notifications',
	'filter_interval'				=> 'Filter by interval',
	'email_deleted_message'	=> '%d email notification(s) have been deleted',
	'email_reset_message' 	=> '%d email notification(s) have been reset',
	'email_add_notification'=> 'Add new email notification',
	'email_reset_selected'	=> 'Reset selected',
	'no_emails_results'			=> 'No email notification found',
	'email_address'					=> 'Email Address',
	'url_to_match'					=> 'URL to match',

	//Add email view
	'preference'							=> 'Preference',
	'setting'									=> 'Setting',
	'email_notif_page_title'	=> 'Add an email to send notifications to',
	'email_notif_email_label' => 'Email Address',
	'email_notif_email_desc'	=> 'The email address to send the notification to',
	'email_notif_url_label'		=> 'URL Filter',
	'email_notif_url_desc'		=> 'The notification will be sent to this email only if the 404 URL matches this filter. It is a regular expression. See the documentation for examples.',
	'email_notif_interval_label'=> 'Notification Interval',
	'email_notif_interval_desc' => 'Select how often we should send email notifications.',
	'email_notif_submit'				=> 'Save',
	'email_notif_interval_once' => 'Once',
	'email_notif_interval_always' => 'Every Time',
	'email_notif_interval_invalid' => 'Invalid interval parameter',

	//Settings view
	'settings'						=> 'Settings',
	'settings_pagetitle'	=> 'Hop 404 Reporter > Settings',
	'settings_save'				=> 'Save',
	'settings_form_error_email_address_sender'		=> 'It seems that this email address is not valid',
	'settings_form_error_email_notification_subject'=> 'The email subject cannot be empty',
	'settings_form_error_no_template'							=> "You don't want to send en empty email, do you ? ;)",
	'settings_saved_success'											=> 'Hop 404 Reporter settings have been saved',

	//Support and Help View
	'support_page_title'	=> 'Support and Help',

	//Email Defaults
	'email_notification_subject'	=> 'Notification of a 404 not found URL',
	'email_template'				=>
'
Hi,
You are receiving this email from {site_url}.

A 404 error occurred on {404_url} (from {referrer_url}), on {404_date} at {404_time}.
',


	//NAV Headers
	'nav_index'				=> '404 URLs',
	'nav_emails'			=> 'Email notifications',
	'nav_settings'		=> 'Settings',
	'nav_support'			=> 'Help and Support',

	//END
);
