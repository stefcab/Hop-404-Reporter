<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD.'hop_404_reporter/config.php';

class Hop_404_reporter_helper
{
	private static $_settings;
	
	/**
	 * Get the possible values of url referrer.
	 * Used when no url referrer is saved into the DB
	 **/
	public static function get_referrer_url_globals()
	{
		return array('referrer_not_specified', 'referrer_not_tracked');
	}
	
	/**
	 * Get the possible values of an email notification interval parameter
	 */
	public static function get_email_notification_globals()
	{
		return array('once', 'always');
	}
	
	private static function _get_default_settings()
	{
		return array(
			'enabled'					=> 'y',
			'referrer_tracking'			=> 'y',
			'send_email_notifications' 	=> 'y',
			'email_address_sender'		=> '404_report@example.com',
			'email_notification_subject'=> lang('email_notification_subject'),
			'email_template'			=> lang('email_template')
		);
	}
	
	public static function get_settings()
	{
		if (! isset(self::$_settings))
		{
			$settings = array();

			//Get the actual saved settings
			$query = ee()->db->get('hop_404_reporter_settings');
			
			foreach ($query->result_array() as $row)
			{
				$settings[$row["setting_name"]] = $row["value"];
			}

			self::$_settings = array_merge(self::_get_default_settings(), $settings);
		}

		return self::$_settings;
	}
	
	public static function save_settings($settings = array())
	{
		//be sure to save all settings possible
		$_tmp_settings = array_merge(self::_get_default_settings(), $settings);
		
		//TODO : improve the saving settings script
		
		//No way to do INSERT IF NOT EXISTS so...
		foreach ($_tmp_settings as $setting_name => $setting_value)
		{
			$query = ee()->db->get_where('hop_404_reporter_settings', array('setting_name'=>$setting_name), 1, 0);
			if ($query->num_rows() == 0) {
			  // A record does not exist, insert one.
			  $query = ee()->db->insert('hop_404_reporter_settings', array('setting_name' => $setting_name, 'value' => $setting_value));
			} else {
			  // A record does exist, update it.
			  $query = ee()->db->update('hop_404_reporter_settings', array('value' => $setting_value), array('setting_name'=>$setting_name));
			}
		}
		
		self::$_settings = $_tmp_settings;
	}
	
	public static function save_404_url($url_404, $referrer_url, $date)
	{
		//Verify if combo url->referrer exists into DB
		$query = ee()->db->get_where('hop_404_reporter_urls', array('url' => $url_404, 'referrer_url' => $referrer_url), 1, 0);
		if ($query->num_rows() == 0) 
		{
			$query = ee()->db->insert('hop_404_reporter_urls', array('url' => $url_404, 'count' => 1, 'referrer_url' => $referrer_url, 'last_occurred' => $date->format('Y-m-d H:i:s')));
		}
		else
		{
			$result_array = $query->result_array();
			$count = intval($result_array[0]["count"]);
			$query = ee()->db->update('hop_404_reporter_urls', array('count' => $count+1, 'last_occurred' => $date->format('Y-m-d H:i:s')), array('url_id' => $result_array[0]["url_id"]));
		}
	}
	
	public static function send_email_notifications($url = "", $referrer_url, $datetime)
	{
		if ($url == "")
		{
			return;
		}
		$notif_query = ee()->db->query('SELECT * FROM `'.ee()->db->dbprefix.'hop_404_reporter_emails` WHERE ? REGEXP `url_to_match` OR `url_to_match` = "" ', array($url));
		
		$hop_settings = Hop_404_reporter_helper::get_settings();
		$email_template = $hop_settings["email_template"];
		
		if ($email_template == "") return; // useless to send an empty email...
		$email_txt = str_replace(array('{site_url}', '{404_url}', '{referrer_url}', '{404_date}', '{404_time}'), array(ee()->functions->create_url(''), $url, $referrer_url, $datetime->format('Y-m-d'), $datetime->format('H:i:s')), $email_template);
		//echo $email_txt;
		
		$email_sender = $hop_settings["email_address_sender"];
		$email_subject = $hop_settings["email_notification_subject"];
		ee()->load->library('email');
		
		
		foreach ($notif_query->result_array() as $row)
		{
			$send_email = true;
			$update_parameters = false;
			//verify email address (should be OK, but if someone messed-up the DB data...)
			if (!filter_var($row["email_address"], FILTER_VALIDATE_EMAIL))
			{
				$send_email = false;
			}
			if ($row["interval"]=="once" && $send_email)
			{
				//Verify that we didn't already send an email for this 404 url
				if ($row["parameter"] != '')
				{
					$parameters = unserialize($row["parameter"]);
					if (array_key_exists($url.'@'.$referrer_url, $parameters))
					{
						$send_email = false;
					}
					else
					{
						$parameters[$url.'@'.$referrer_url] = 'sent';
						$update_parameters = true;
					}
				}
				else
				{
					$parameters = array();
					$parameters[$url.'@'.$referrer_url] = 'sent';
					$update_parameters = true;
				}
			}
			
			//echo 'sending email to ' . $row["email_address"].'<br>';
			if ($send_email)
			{
				ee()->email->mailtype = 'text';
				ee()->email->from($email_sender);
				ee()->email->to($row["email_address"]);
				ee()->email->subject($email_subject);
				ee()->email->message($email_txt);
				if (ee()->email->Send() && $update_parameters)
				{
					//Update the DB
					if (is_array($parameters))
					{
						ee()->db->update('hop_404_reporter_emails', 
										 array('parameter' => serialize($parameters)), 
										 array('email_id' => $row["email_id"])
										);
					}
				}
			}
			
			
		}
	}
}