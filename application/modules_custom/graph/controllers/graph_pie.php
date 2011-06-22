<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Graph_Pie extends Admin_Controller {

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
				//reset data
		$data['reset'] = $this->mdl_graph->reset_graph_query();
				//update data
		$data['update'] = $this->mdl_graph->update_graph_query($yearselected);
				// get new data
				$data['get'] = $this->mdl_graph->get_graph_query();
		$rows = $data['get']->result();

				$d1_data = array();
		$d2_data = array();
		$d3_data = array();
		$d4_data = array();
		$d5_data = array();
		$d6_data = array();
		$d7_data = array();

				foreach($rows as $row){
			$val =  intval($row->month_id);
						$d1_data[] = array($row->month_id,$row->invoice_total);
						$d2_data[] = array($row->month_id,$row->invoice_paid);
						$d3_data[] = array($row->month_id,$row->invoice_discount);
						$d4_data[] = array($row->month_id,$row->invoice_shipping);
						$d5_data[] = array($row->month_id,$row->payment_amount);
						$d6_data[] = array($row->month_id,$row->expense_amount);
						$d7_data[] = array($row->month_id,$row->contract_total);
		}

				$data['next'] = $yearselected+1;
				$data['previous'] = $yearselected-1;

				$d1_array['label'] = 'invoice Total';
				$d1_array['data'] = $d1_data;
				$d1_array['lines'] = array('show'=>true,'fill'=>true);
				$d1_array['points'] = array('show'=>true);
				//$d1_array['color'] = array('rgb(255,165,0)');

				$d2_array['label'] = 'invoice Paid';
				$d2_array['data'] = $d2_data;
				$d2_array['lines'] = array('show'=>true,'fill'=>true);
				$d2_array['points'] = array('show'=>true);
				//$d2_array['color'] = array('rgb(173,216,230)');

				$d3_array['label'] = 'invoice Discount';
				$d3_array['data'] = $d3_data;
				$d3_array['lines'] = array('show'=>true,'fill'=>true);
				$d3_array['points'] = array('show'=>true);
				//$d3_array['color'] = array('rgb(139,0,139)');

				$d4_array['label'] = 'invoice Shipping';
				$d4_array['data'] = $d4_data;
				$d4_array['lines'] = array('show'=>true,'fill'=>true);
				$d4_array['points'] = array('show'=>true);
				//$d4_array['color'] = array('rgb(0,100,0)');

				$d5_array['label'] = 'invoice Payment';
				$d5_array['data'] = $d5_data;
				$d5_array['lines'] = array('show'=>true,'fill'=>true);
				$d5_array['points'] = array('show'=>true);
				//$d5_array['color'] = array('rgb(128,0,128)');

				$d6_array['label'] = 'accounts-expense Module';
				$d6_array['data'] = $d6_data;
				$d6_array['lines'] = array('show'=>true,'fill'=>true);
				$d6_array['points'] = array('show'=>true);
				//$d6_array['color'] = array('rgb(255,215,0)');

				$d7_array['label'] = 'contracts Module';
				$d7_array['data'] = $d7_data;
				$d7_array['lines'] = array('show'=>true,'fill'=>true);
				$d7_array['points'] = array('show'=>true);
				//$d7_array['color'] = array('rgb(0,0,128)');

				$full_data = array();
				if($this->mdl_mcb_data->setting('graph_setting_total'))
				$full_data[] = $d1_array;
				if($this->mdl_mcb_data->setting('graph_setting_paid'))
				$full_data[] = $d2_array;
				if($this->mdl_mcb_data->setting('graph_setting_discount'))
				$full_data[] = $d3_array;
				if($this->mdl_mcb_data->setting('graph_setting_shipping'))
				$full_data[] = $d4_array;
				if($this->mdl_mcb_data->setting('graph_setting_payment'))
				$full_data[] = $d5_array;
				if($this->mdl_mcb_data->setting('graph_setting_expense'))
				$full_data[] = $d6_array;
				if($this->mdl_mcb_data->setting('graph_setting_contract'))
				$full_data[] = $d7_array;

				$data['full'] = json_encode($full_data);

		$data['header_insert'] = 'header_insert';
		$this->load->view('index', $data);

	}

	function _post_handler() {

		if ($this->input->post('btn_save_settings')) {

			$this->_custom_save();

			$this->mdl_mcb_data->set_session_data();

			$this->session->set_flashdata('custom_success', $this->lang->line('system_settings_saved'));

			redirect('graph');

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

		$data['reset'] = $this->mdl_graph->reset_graph_query();

		$data['update'] = $this->mdl_graph->update_graph_query($yearselected);

		$get = $this->mdl_graph->get_graph_query();
		$rows = $get->result();

		$d1_data = array();
		$d2_data = array();
		$d3_data = array();
		$d4_data = array();
		$d5_data = array();
		$d6_data = array();
		$d7_data = array();

		foreach($rows as $row){
			$val =  intval($row->month_id);
						$d1_data[] = array($row->month_id,$row->invoice_total);
						$d2_data[] = array($row->month_id,$row->invoice_paid);
						$d3_data[] = array($row->month_id,$row->invoice_discount);
						$d4_data[] = array($row->month_id,$row->invoice_shipping);
						$d5_data[] = array($row->month_id,$row->payment_amount);
						$d6_data[] = array($row->month_id,$row->expense_amount);
						$d7_data[] = array($row->month_id,$row->contract_total);
		}

				$d1_array['label'] = 'invoice Total';
				$d1_array['data'] = $d1_data;
				$d1_array['lines'] = array('show'=>true,'fill'=>true);
				$d1_array['points'] = array('show'=>true);
				//$d1_array['color'] = array('rgb(255,165,0)');

				$d2_array['label'] = 'invoice Paid';
				$d2_array['data'] = $d2_data;
				$d2_array['lines'] = array('show'=>true,'fill'=>true);
				$d2_array['points'] = array('show'=>true);
				//$d2_array['color'] = array('rgb(173,216,230)');

				$d3_array['label'] = 'invoice Discount';
				$d3_array['data'] = $d3_data;
				$d3_array['lines'] = array('show'=>true,'fill'=>true);
				$d3_array['points'] = array('show'=>true);
				//$d3_array['color'] = array('rgb(139,0,0)');

				$d4_array['label'] = 'invoice Shipping';
				$d4_array['data'] = $d4_data;
				$d4_array['lines'] = array('show'=>true,'fill'=>true);
				$d4_array['points'] = array('show'=>true);
				//$d4_array['color'] = array('rgb(0,100,0)');

				$d5_array['label'] = 'invoice Payment';
				$d5_array['data'] = $d5_data;
				$d5_array['lines'] = array('show'=>true,'fill'=>true);
				$d5_array['points'] = array('show'=>true);
				//$d5_array['color'] = array('rgb(128,0,128)');

				$d6_array['label'] = 'accounts-expense Module';
				$d6_array['data'] = $d6_data;
				$d6_array['lines'] = array('show'=>true,'fill'=>true);
				$d6_array['points'] = array('show'=>true);
				//$d6_array['color'] = array('rgb(255,215,0)');

				$d7_array['label'] = 'contracts Module';
				$d7_array['data'] = $d7_data;
				$d7_array['lines'] = array('show'=>true,'fill'=>true);
				$d7_array['points'] = array('show'=>true);
				//$d7_array['color'] = array('rgb(0,0,128)');


				$full_data = array();
				if($this->mdl_mcb_data->setting('graph_setting_total'))
				$full_data[] = $d1_array;
				if($this->mdl_mcb_data->setting('graph_setting_paid'))
				$full_data[] = $d2_array;
				if($this->mdl_mcb_data->setting('graph_setting_discount'))
				$full_data[] = $d3_array;
				if($this->mdl_mcb_data->setting('graph_setting_shipping'))
				$full_data[] = $d4_array;
				if($this->mdl_mcb_data->setting('graph_setting_payment'))
				$full_data[] = $d5_array;
				if($this->mdl_mcb_data->setting('graph_setting_expense'))
				$full_data[] = $d6_array;
				if($this->mdl_mcb_data->setting('graph_setting_contract'))
				$full_data[] = $d7_array;

				echo json_encode($full_data);

	}

}

?>