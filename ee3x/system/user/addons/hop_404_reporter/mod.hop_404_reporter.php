<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD.'hop_404_reporter/helper.php';

class Hop_404_reporter
{
	
	/**
	 * The tag {exp:hop_404_reporter:process_url} is processing the current URL as a 404 one
	 * This tag is to be placed into the 404 template
	 **/
	function process_url()
	{
		$hop_settings = Hop_404_reporter_helper::get_settings();
		if ($hop_settings["enabled"] == 'y')
		{
			$current_url = ee()->uri->uri_string();
		
			$referrer_url = "";
			if ($hop_settings["referrer_tracking"] == 'y')
			{
				if (ee()->input->server('HTTP_REFERER') != "")
				{
					$referrer_url = ee()->input->server('HTTP_REFERER');
				}
				else
				{
					$referrer_url = "referrer_not_specified";
				}
			}
			else
			{
				$referrer_url = 'referrer_not_tracked';
			}

			// Get EE localized date time
			$datetime = new DateTime();
			$datetime->setTimestamp(ee()->localize->now);
			
			Hop_404_reporter_helper::save_404_url('/'.$current_url, $referrer_url, $datetime);
			
			if ($hop_settings["send_email_notifications"] == 'y')
			{
				Hop_404_reporter_helper::send_email_notifications('/'.$current_url, $referrer_url, $datetime);
			}
		}
		
		
		return "";
	}
}