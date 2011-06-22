<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Setup extends Admin_Controller {

	function __construct() {

		parent::__construct(TRUE);

	}

	function index() {}

	function install() {

		$this->db->query(
		"CREATE TABLE IF NOT EXISTS `mcb_graph` (
		`month_id` varchar(25) NOT NULL,
		`invoice_discount` decimal(10,2) NOT NULL,
		`invoice_total` decimal(10,2) NOT NULL,
		`invoice_paid` decimal(10,2) NOT NULL,
		`invoice_shipping` decimal(10,2) NOT NULL,
		`payment_amount` decimal(10,2) NOT NULL,
		`expense_amount` decimal(10,2) NOT NULL,
		`contract_total` decimal(10,2) NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;"
		);

		$this->db->query(
			"INSERT INTO `mcb_graph` (`month_id`, `invoice_discount`, `invoice_total`, `invoice_paid`, `invoice_shipping`, `payment_amount`, `expense_amount`, `contract_total` ) VALUES
			('01', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
			('02', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
			('03', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
			('04', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
			('05', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
			('06', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
			('07', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
			('08', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
			('09', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
			('10', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
			('11', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
			('12', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');"
		);
		$this->mdl_mcb_data->save('graph_setting_year', date("Y"));
	}

	function uninstall() {

		$queries = array(
			"DROP TABLE IF EXISTS `mcb_graph`"
		);

		foreach ($queries as $query) {

			$this->db->query($query);

		}
		$this->mdl_mcb_data->delete('graph_setting_year');
		$this->mdl_mcb_data->delete('graph_setting_total');
		$this->mdl_mcb_data->delete('graph_setting_paid');
		$this->mdl_mcb_data->delete('graph_setting_discount');
		$this->mdl_mcb_data->delete('graph_setting_shipping');
		$this->mdl_mcb_data->delete('graph_setting_payment');
		$this->mdl_mcb_data->delete('graph_setting_expense');
		$this->mdl_mcb_data->delete('graph_setting_contract');
	}

	function upgrade() {

			switch($this->mdl_mcb_modules->custom_modules['graph']->module_version) {
			case '0.8.8':
				$this->u089();
				$this->u089A();
				$this->u089B();
				$this->u089C();
				$this->u089J();
				$this->u090();
				$this->u092();
				break;
			case '0.8.9':
				$this->u089A();
				$this->u089B();
				$this->u089C();
				$this->u089J();
				$this->u089K();
				$this->u090();
				$this->u092();
				break;
			case '0.8.9.A':
				$this->u089B();
				$this->u089C();
				$this->u089J();
				$this->u089K();
				$this->u090();
				$this->u092();
				break;
			case '0.8.9.B':
				$this->u089C();
				$this->u089J();
				$this->u089K();
				$this->u090();
				$this->u092();
				break;
			case '0.8.9.C':
				$this->u089J();
				$this->u089K();
				$this->u090();
				$this->u092();
				break;
			case '0.8.9.J':
				$this->u089K();
				$this->u090();
				$this->u092();
				break;
			case '0.8.9.K':
				$this->u090();
				$this->u092();
			case '0.9.0':
				$this->u092();
		}
	}

	function u089() {

		$this->set_module_version('0.8.9');

	}

	function u089A() {

		$this->db->query(
		"ALTER TABLE `mcb_graph` ADD `invoice_shipping` DECIMAL( 10, 2 ) NOT NULL AFTER `invoice_paid` ,
		 ADD `payment_amount` DECIMAL( 10, 2 ) NOT NULL AFTER `invoice_shipping` "
		);

		$this->set_module_version('0.8.9.A');

	}

	function u089B() {

		$this->db->query(
		"ALTER TABLE `mcb_graph` ADD `expense_amount` DECIMAL( 10, 2 ) NOT NULL AFTER `payment_amount`"
		);

		$this->set_module_version('0.8.9.B');

	}

	function u089C() {

		$this->db->query(
		"ALTER TABLE `mcb_graph` ADD `contract_total` DECIMAL( 10, 2 ) NOT NULL AFTER `expense_amount`"
		);

		$this->set_module_version('0.8.9.C');

	}

	function u089J() {

		$this->set_module_version('0.8.9.J');

	}
	function u089K() {

		$this->set_module_version('0.8.9.K');

	}

	function u090() {

		$this->set_module_version('0.9.0');

	}
	function u092() {

		$this->mdl_mcb_data->save('graph_setting_year', date("Y"));
		$this->set_module_version('0.9.2');

	}

	function set_module_version($module_version) {

		$this->db->set('module_version', $module_version);

		$this->db->where('module_path', 'graph');

		$this->db->update('mcb_modules');

	}

}
?>