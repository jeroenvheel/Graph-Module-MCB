<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Graph extends Admin_Controller {

	function __construct() {

		parent::__construct(TRUE);

		$this->_post_handler();

		if (!$this->mdl_mcb_modules->check_enable('graph')) {

			redirect('mcb_modules');

		}

		$this->load->model('mdl_graph');

	}
	function index() {
			redirect('graph/line');
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

	}

}

?>