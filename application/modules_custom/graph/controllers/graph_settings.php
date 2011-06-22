<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Graph_Settings extends Admin_Controller {

	function display() {

		$this->load->view('settings');

	}

	function save() {

		/*
		 * As per the config file, this function will
		 * execute when the system settings are saved.
		 */

	$this->mdl_mcb_data->save('graph_setting_year', $this->input->post('graph_setting_year'));
	
	$this->mdl_mcb_data->save('graph_setting_total', $this->input->post('graph_setting_total'));
	
	$this->mdl_mcb_data->save('graph_setting_paid', $this->input->post('graph_setting_paid'));
	
	$this->mdl_mcb_data->save('graph_setting_discount', $this->input->post('graph_setting_discount'));

	$this->mdl_mcb_data->save('graph_setting_shipping', $this->input->post('graph_setting_shipping'));

	$this->mdl_mcb_data->save('graph_setting_payment', $this->input->post('graph_setting_payment'));

	$this->mdl_mcb_data->save('graph_setting_expense', $this->input->post('graph_setting_expense'));

	$this->mdl_mcb_data->save('graph_setting_contract', $this->input->post('graph_setting_contract'));
	
	}

}

?>