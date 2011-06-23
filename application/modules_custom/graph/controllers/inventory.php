<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Inventory extends Admin_Controller {

	function __construct() {

		parent::__construct(TRUE);

		$this->_post_handler();

		if (!$this->mdl_mcb_modules->check_enable('graph')) {

			redirect('mcb_modules');

		}

		$this->load->model('mdl_graph');

	}
	function index() {

		$yearselected = $this->mdl_mcb_data->get('graph_setting_year');
		$data['get'] = $this->mdl_graph->get_inventory_total_graph($yearselected);
		$rows = $data['get']->result();

		$full_data = array();
		$full_ticks = array();
		$data_num = 1;
		$ticks_num = 1;
		$num_rows = $data['get']->num_rows;

	//set data for ticks
		foreach($rows as $row){
			$val = $ticks_num;
				$data_ticks[] = array("$val",$row->inventory_name);
				$full_ticks = $data_ticks;
			$ticks_num++;
		}
		//$full_ticks['min'] = "1";
		//$full_ticks['max'] = ($num_rows + '1');

	//Set data for graph
		foreach($rows as $row){
			$val = $data_num;
				//set emty array
				$data = array();
				//set label name
				$data_label = $row->inventory_name;
				//set array data
				$data[] = array("$val",$row->sold_amount);
				//set label
				$array['label'] = $data_label;
				//set data
				$array['data'] = $data;
				//set type
				$array['pie'] = array('show'=>true,'fill'=>true);
				// set data in array
				$full_data[] = $array;
			$data_num++;
		}

		$data['ticks'] = json_encode($full_ticks);
		$data['full'] = json_encode($full_data);

		$data['next'] = $yearselected+1;
		$data['previous'] = $yearselected-1;

		$data['header_insert'] = 'header_insert_pie';
		$this->load->view('items', $data);
	}

	function _post_handler() {

		if ($this->input->post('btn_save_settings')) {

			$this->_custom_save();

			$this->mdl_mcb_data->set_session_data();

			$this->session->set_flashdata('custom_success', $this->lang->line('system_settings_saved'));

			redirect('graph');

		}
		if ($this->input->post('Line_Chart')) {
			redirect('graph/line');
		}
		if ($this->input->post('Bar_Chart')) {
			redirect('graph/bar');
		}
		if ($this->input->post('item_Chart')) {
			redirect('graph/items');
		}
		if ($this->input->post('inventory_Chart')) {
			redirect('graph/inventory');
		}

	}

	function _custom_save() {

		foreach ($this->mdl_mcb_modules->custom_modules as $module) {

			if ($module->module_enabled and isset($module->module_config['settings_save'])) {

				modules::run($module->module_config['settings_save']);

			}

		}

	}

	function ajax_custom_save() {

		foreach ($this->mdl_mcb_modules->custom_modules as $module) {

			if ($module->module_enabled and isset($module->module_config['settings_save'])) {

				modules::run($module->module_config['settings_save']);

			}

		}

				$this->mdl_mcb_data->set_session_data();

				$this->ajax_load();

	}

		function ajax_load(){

		$yearselected = $this->input->get_post('year');

				if(!$yearselected)
				$yearselected = $this->mdl_mcb_data->get('graph_setting_year');

		$data['get'] = $this->mdl_graph->get_items_total_graph($yearselected);
		$rows = $data['get']->result();


		$full_data = array();
		$full_ticks = array();
		$data_num = 1;
		$ticks_num = 1;
		$num_rows = $data['get']->num_rows;

	//set data for ticks
		foreach($rows as $row){
			$val = $ticks_num;
				$data_ticks[] = array("$val",$row->inventory_name);
				$full_ticks = $data_ticks;
			$ticks_num++;
		}
		$full_ticks['min'] = "0";
		$full_ticks['max'] = ($num_rows + '1');

	//Set data for graph
		foreach($rows as $row){
			$val = $data_num;
				//set emty array
				$data = array();
				//set label name
				$data_label = $row->inventory_name;
				//set array data
				$data[] = array("$val",$row->sold_amount);
				//set label
				$array['label'] = $data_label;
				//set data
				$array['data'] = $data;
				//set type
				$array['pie'] = array('show'=>true,'fill'=>true);
				// set data in array
				$full_data[] = $array;
			$data_num++;
		}

		//$data['ticks'] = json_encode($full_ticks);
		//$data['full'] = json_encode($full_data);

				echo json_encode($full_ticks);
				echo json_encode($full_data);


	}


}

?>