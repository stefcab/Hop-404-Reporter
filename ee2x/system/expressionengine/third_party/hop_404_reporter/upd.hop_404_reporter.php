<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD.'hop_404_reporter/helper.php';

class hop_404_reporter_upd
{
	var $version = HOP_404_REPORTER_VERSION;
	
	function install()
	{
		ee()->load->dbforge();
		
		//Add module to EE modules list
		$data = array(
		   'module_name' => 'Hop_404_reporter' ,
		   'module_version' => $this->version,
		   'has_cp_backend' => 'y',
		   'has_publish_fields' => 'n'
		);

		ee()->db->insert('modules', $data);
		
		//Create module tables
		//URLs table
		$fields = array(
			'url_id'			=> array('type' => 'INT', 'constraint' => '10', 'unsigned' => TRUE, 'auto_increment' => TRUE),
			'url' 				=> array('type' => 'VARCHAR', 'constraint' => '255'),
			'count'				=> array('type' => 'INT', 'constraint' => '8', 'default' => 0),
			'referrer_url'		=> array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE),
			'last_occurred'		=> array('type' => 'DATETIME'),
			'notification_to'	=> array('type' => 'MEDIUMTEXT')
		);

		ee()->dbforge->add_field($fields);
		ee()->dbforge->add_key('url_id', TRUE);
		ee()->dbforge->add_key(array('url', 'referrer_url')); //add a key on those two as they'll be unique

		ee()->dbforge->create_table('hop_404_reporter_urls');

		unset($fields);
		
		//emails table
		$fields = array(
			'email_id'		=> array('type' => 'INT', 'constraint' => '10', 'unsigned' => TRUE, 'auto_increment' => TRUE),
			'email_address' => array('type' => 'VARCHAR', 'constraint' => '255'),
			'url_to_match'	=> array('type' => 'VARCHAR', 'constraint' => '255'),
			'interval'		=> array('type' => 'VARCHAR', 'constraint' => '255'),
			'parameter'		=> array('type' => 'MEDIUMTEXT')
		);

		ee()->dbforge->add_field($fields);
		ee()->dbforge->add_key('email_id', TRUE);

		ee()->dbforge->create_table('hop_404_reporter_emails');

		unset($fields);
		
		$fields = array(
			'setting_name'		=> array('type' => 'VARCHAR', 'constraint' => '100'),
			'value' 			=> array('type' => 'TEXT')
		);

		ee()->dbforge->add_field($fields);
		ee()->dbforge->add_key('setting_name', TRUE);

		ee()->dbforge->create_table('hop_404_reporter_settings');

		//Save settings (the default ones will be stored)
		Hop_404_reporter_helper::save_settings();
		
		///ATTENTION : TEST
		// $this->generate_data();
		//// /TEST
		
		return TRUE;
	}
	
	function update($current = '')
	{
		ee()->load->dbforge();
		
		if (version_compare($current, '0.1', '='))
		{
			return FALSE;
		}


		if (version_compare($current, '1.0.3', '<'))
		{
			// Add new field to hop_404_reporter_urls
			ee()->load->dbforge();
			ee()->dbforge->add_column('hop_404_reporter_urls', array(
				'notification_to' => array(
						'type' => 'MEDIUMTEXT'
				)
			));

			// We should re-import all notifications sent and put them into that column
			// Is this really that important though ?
		}

		return TRUE;
	}
	
	function uninstall()
	{
		//Uninstall the module
		ee()->load->dbforge();

		ee()->db->select('module_id');
		$query = ee()->db->get_where('modules', array('module_name' => 'Hop_404_reporter'));

		ee()->db->where('module_id', $query->row('module_id'));
		ee()->db->delete('module_member_groups');

		ee()->db->where('module_id', $query->row('module_id'));
		ee()->db->delete('modules');
		
		//Remove the module tables from the database
		ee()->dbforge->drop_table('hop_404_reporter_urls');
		ee()->dbforge->drop_table('hop_404_reporter_emails');
    	ee()->dbforge->drop_table('hop_404_reporter_settings');
		
		return TRUE;
	}
	
	
	/**
	 * Generate fake data
	 */
	function generate_data()
	{
		
		$count = 0;
		while ($count < 100)
		{
			if ($count%4==0)
			{
				$referrer = 'referrer_not_specified';
			}
			else if ($count%6==0)
			{
				$referrer = 'referrer_not_tracked';
			}
			else
			{
				$referrer = '/this/is/referrer/url'.$count;
			}
			$data = array(
			   'url' => '/this_/is/url/'.$count ,
			   'count' => rand(1, 200),
			   'referrer_url' => $referrer,
			   'last_occurred' => date('Y-m-d H:i:s')
			);

			ee()->db->insert('hop_404_reporter_urls', $data);
			$count++;
		}
		
		$count = 0;
		while ($count < 100)
		{
			$interval = 'once';
			$url_to_match = 'ble';
			if ($count%2==0)
			{
				$interval = 'always';
			}
			if ($count%4==0)
			{
				$url_to_match = 'bla';
			}
			else if ($count%6==0)
			{
				$url_to_match = 'bli';
			}
			$data = array(
			   'email_address' => 'email'.$count.'@data.org' ,
			   'url_to_match' => $url_to_match,
			   'interval' => $interval
			);

			ee()->db->insert('hop_404_reporter_emails', $data);
			$count++;
		}
	}
}