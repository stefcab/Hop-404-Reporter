<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$lang = array(

	//Required for MODULES page

	'hop_404_reporter_module_name'			=> 'Hop 404 Reporter',
	'hop_404_reporter_module_description'	=> 'Manage all 404 errors happening on your website',

	//Additional Key => Value pairs
	'referrer'				=> 'Referrer URL',
	'referrer_url'			=> 'Referrer URL',
	'referrer_not_tracked'	=> 'Referrer not tracked',
	'referrer_not_specified'=> 'Referrer not specified',
	'count'					=> 'Count',
	'datetime_format'		=> 'Y-m-d h:i:sA',
	'last_occurred_date' 	=> 'Date of last occurrence',
	'last_occurred'			=> 'Date of last occurrence',


	//Index view
	'404_url_title'				=> '404 URLs',
	'404_url_list_title'		=> '404 URLs list',
	'404_url_list_description'	=> '<p>This list contains all 404 URLs that occured on your website.</p>',
	'url'						=> 'URL',
	'no_results'				=> 'No URLs found',
	'no_matching_urls'			=> 'Sorry, we didn\'t find any matching URLs.',
	'no_last_occur'				=> 'Sorry, no date saved',
	'delete_selected'			=> 'Delete selected',
	'url_deleted_message'		=> '%d URL(s) have been deleted',
	'filter_urls'				=> 'Filter URLs',
	'keywords'					=> 'Keywords',
	'filter_date_range'			=> 'Date range',
	'filter_last_day'			=> 'Last day',
	'filter_last_week'			=> 'Last week',
	'filter_last_month'			=> 'Last month',
	'filter_last_3months'		=> 'Last 3 months',
	'filter_last_6months'		=> 'Last 6 months',
	'filter_last_year'			=> 'Last year',
	'filter_referrer_url'		=> 'Referrer URL',
	'filter_no_referrer_url' 	=> 'Referrer not specified',
	'filter_referrer_url_not_saved' => 'Referrer not tracked',
	'filter_referrer_saved' 	=> 'Referrer specified',
	'--with_selected--'			=> '-- with selected --',
	'search_urls'				=> 'Search URLs',
	'url_deleted_success'		=> 'URL(s) deleted',

	//Email list view
	'email_list_pagetitle'		=> 'Email notifications',
	'email_notifications'		=> 'Email Notifications',
	'email_notifications_list'	=> 'Notifications list',
	'email_page_title'			=> 'Email to notify',
	'email_page_description'	=> '<p>Hop 404 reporter can send notifications to email addresses each time a 404 occurs.</p>',
	'filter_email_notifications'=> 'Filter Email Notifications',
	'filter_interval'			=> 'Filter by interval',
	'email_deleted_message'		=> '%d email notification(s) have been deleted',
	'email_reset_message' 		=> '%d email notification(s) have been reset',
	'email_add_notification'	=> 'Add new email notification',
	'email_reset_selected'		=> 'Reset selected',
	'no_emails_results'			=> 'No email notification found',
	'email_address'				=> 'Email Address',
	'url_to_match'				=> 'URL to match',
	'create_new_one'			=> 'Create a new one',
	'search_emails_notif'		=> 'Search email notifications',
	'email_deleted_success'		=> 'Email(s) deleted',
	'email_reseted_success'		=> 'Email(s) reseted',

	//Add email view
	'preference'							=> 'Preference',
	'setting'								=> 'Setting',
	'email_create_notification' 			=> 'Create email notification',
	'email_notification_page_title'			=> 'Add an email to send notifications to',
	'email_notification_email_label' 		=> 'Email Address',
	'email_notification_email_desc'			=> 'The email address to send the notification to',
	'email_notification_url_label'			=> 'URL Filter',
	'email_notification_url_desc'			=> 'The notification will be sent to this email only if the 404 URL matches this filter.<br /> It is a regular expression. See the documentation for examples.<br /> You can leave it empty, the notification will be send everytime.',
	'email_notification_interval_label'		=> 'Notification Interval',
	'email_notification_interval_desc' 		=> 'Select how often we should send email notifications.',
	'email_notification_submit'				=> 'Save',
	'email_notification_interval_once' 		=> 'Once',
	'email_notification_interval_always' 	=> 'Every Time',
	'email_notificaiton_interval_invalid'	=> 'Invalid interval parameter',
	'emaill_notification_add_success'		=> 'Email notification added',
	'emaill_notification_add_success_desc'	=> 'The new email notification has been saved.',

	//Settings view
	'settings'				=> 'Settings',
	'settings_pagetitle'	=> 'Settings',
	'settings_save'			=> 'Save',
	'settings_save_working'	=> 'Saving...',
	'set_enabled'			=> 'Is Hop 404 reporter on?',
	'set_enabled_desc'		=> 'If Hop 404 reporter is off, no URL will be recorded, no emails will be sent.',
	'set_send_email_notifications'		=> 'Do we send email notifications ?',
	'set_send_email_notifications_desc'	=> 'If not, the 404 urls will be recorded but no email will be send.',
	'set_referrer_tracking'				=> 'Is Hop 404 reporter referrer tracking on ?',
	'set_referrer_tracking_desc'		=> 'Referrer tracking will save the referrer URL for each 404 URL occurring.',
	'set_email_address_sender'			=> 'Sender email address',
	'set_email_address_sender_desc'		=> 'This email address will be used as the sender for each notification sent.',
	'set_email_notification_subject'	=> 'Notification email subject',
	'set_email_notification_subject_desc'			=> 'Subject of the email sent when a 404 occurs.',
	'set_404_email_template'						=> 'Notification email template',
	'set_404_email_template_desc'					=> 'You can modify the email people will receive when a 404 occurs. <br />Tags available {site_url}, {404_url}, {referrer_url}, {404_date}, {404_time}',
	'settings_form_error_email_address_sender'		=> 'It seems that this email address is not valid',
	'settings_form_error_email_notification_subject'=> 'The email subject cannot be empty',
	'settings_form_error_no_template'				=> "You don't want to send en empty email, do you ? ;)",
	'settings_saved_success'						=> 'Hop 404 Reporter settings have been saved',

	//Support and Help View
	'support_page_title'			=> 'Support and Help',

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
	'nav_settings'			=> 'Settings',
	'nav_support'			=> 'Help and Support',

	//END
);
