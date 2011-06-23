<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Graph extends MY_Model {

	function reset_graph_query(){

		$this->db->from('mcb_graph');
		$this->db->order_by("month_id", "asc");
		$graphsql = $this->db->get();

		foreach ($graphsql->result_array() as $row)
				{
				//RESET update array
				$update = array(
					'invoice_discount' => '0.00',
					'invoice_total' => '0.00',
					'invoice_paid' => '0.00',
					'invoice_shipping' => '0.00',
					'payment_amount' => '0.00',
					'expense_amount' => '0.00',
					'contract_total' => '0.00'
					);
				//Set update query
				$this->db->where('mcb_graph.month_id', $row['month_id']);
				$this->db->update('mcb_graph', $update); 
				}
	}

	function update_graph_query($yearselected){
		// update invoice data
		$this->update_graph_invoice_query($yearselected);
		// update payment data
		$this->update_graph_payment_query($yearselected);
		// update module data
		$check['module_accounts'] = $this->check_enable('accounts');
			if ($check['module_accounts']){
				$check_enable = $check['module_accounts'];
					if ($check_enable[0]['module_enabled'] == 1){
							$data['update_expense'] = $this->update_graph_accounts_expense_query($yearselected);
						}
				}
		$check['module_contracts'] = $this->check_enable('contracts');
				if ($check['module_contracts']){
						$check_enable = $check['module_contracts'];
						if ($check_enable[0]['module_enabled'] == 1){
							$data['update_contracts'] = $this->update_graph_contracts_query($yearselected);
						}
				}
	}

	function get_graph_query(){

		$this->db->from('mcb_graph');
		$this->db->order_by("month_id", "asc");
		$graphsql = $this->db->get();

		return $graphsql;
		}
	// update fuctions
	function update_graph_invoice_query($yearselected){

		$this->db->select('FROM_UNIXTIME(invoice_date_entered, "%m") as month_id' ,FALSE);
		$this->db->select('FROM_UNIXTIME(invoice_date_entered, "%M") as month_name' ,FALSE);
		$this->db->select('FROM_UNIXTIME(invoice_date_entered, "%Y") as year_id' ,FALSE);
		$this->db->select('SUM(mcb_invoice_amounts.invoice_discount) as invoice_discount', FALSE);
		$this->db->select('SUM(mcb_invoice_amounts.invoice_total) as invoice_total', FALSE);
		$this->db->select('SUM(mcb_invoice_amounts.invoice_paid) as invoice_paid', FALSE);
		$this->db->select('SUM(mcb_invoice_amounts.invoice_shipping) as invoice_shipping', FALSE);
		$this->db->from('mcb_invoices');
		$this->db->join('mcb_invoice_amounts', 'mcb_invoice_amounts.invoice_id = mcb_invoices.invoice_id');
		$this->db->where('FROM_UNIXTIME(invoice_date_entered, "%Y")=',$yearselected);
		$this->db->group_by('month_id, year_id');
		$this->db->order_by("year_id", "asc");
		$this->db->order_by("month_id", "asc");

		$graphsql = $this->db->get();

		foreach ($graphsql->result_array() as $row)
			{
				//Set update array
				$update = array(
					'invoice_discount' => $row['invoice_discount'],
					'invoice_total' => $row['invoice_total'],
					'invoice_paid' => $row['invoice_paid'],
					'invoice_shipping' => $row['invoice_shipping']
					);
				//Set update query
				$this->db->where('mcb_graph.month_id', $row['month_id']);
				$this->db->update('mcb_graph', $update); 
			}
		}

	function update_graph_payment_query($yearselected){

		$this->db->select('FROM_UNIXTIME(payment_date, "%m") AS payment_month_id' ,FALSE);
		$this->db->select('FROM_UNIXTIME(payment_date, "%Y") AS payment_year_id' ,FALSE);
		$this->db->select('SUM(payment_amount) AS payment_amount' ,FALSE);
		$this->db->from('mcb_payments');
		$this->db->where('FROM_UNIXTIME(payment_date, "%Y") =',$yearselected);
		$this->db->group_by('payment_month_id, payment_year_id');
		$this->db->order_by("payment_year_id", "asc");
		$this->db->order_by("payment_month_id", "asc");

		$graphsql = $this->db->get();

		foreach ($graphsql->result_array() as $row)
			{
				//Set update array
				$update = array(
					'payment_amount' => $row['payment_amount'],
					);
				//Set update query
				$this->db->where('mcb_graph.month_id', $row['payment_month_id']);
				$this->db->update('mcb_graph', $update); 
			}
	}

	function update_graph_accounts_expense_query($yearselected){

		$this->db->select('FROM_UNIXTIME(expense_date, "%m") AS expense_month_id' ,FALSE);
		$this->db->select('FROM_UNIXTIME(expense_date, "%Y") AS expense_year_id' ,FALSE);
		$this->db->select('SUM(amount) AS expense_amount' ,FALSE);
		$this->db->from('mcb_expenses');
		$this->db->where('FROM_UNIXTIME(expense_date, "%Y") =',$yearselected);
		$this->db->group_by('expense_month_id, expense_year_id');
		$this->db->order_by("expense_year_id", "asc");
		$this->db->order_by("expense_month_id", "asc");

		$graphsql = $this->db->get();

		foreach ($graphsql->result_array() as $row)
			{
				//Set update array
				$update = array(
					'expense_amount' => $row['expense_amount'],
					);
				//Set update query
				$this->db->where('mcb_graph.month_id', $row['expense_month_id']);
				$this->db->update('mcb_graph', $update); 
			}
	}

	function update_graph_contracts_query($yearselected){

		$this->db->select('FROM_UNIXTIME(contract_date_start, "%m") as contract_month_id' ,FALSE);
		$this->db->select('FROM_UNIXTIME(contract_date_start, "%M") as contract_month_name' ,FALSE);
		$this->db->select('FROM_UNIXTIME(contract_date_start, "%Y") as contract_year_id' ,FALSE);
		$this->db->select('SUM(mcb_contract_items.item_price) as contract_total', FALSE);
		$this->db->from('mcb_contracts');
		$this->db->join('mcb_contract_items', 'mcb_contract_items.contract_id = mcb_contracts.contract_id');
		//$this->db->where('FROM_UNIXTIME(invoice_date_entered, "%Y")=',$yearselected);
		$this->db->group_by('contract_month_id, contract_year_id');
		$this->db->order_by("contract_year_id", "asc");
		$this->db->order_by("contract_month_id", "asc");

		$graphsql = $this->db->get();

		foreach ($graphsql->result_array() as $row)
			{
				//Set update array
				$update = array(
					'contract_total' => $row['contract_total']
					);
				//Set update query
				$this->db->where('mcb_graph.month_id', $row['contract_month_id']);
				$this->db->update('mcb_graph', $update); 
			}
	}

	function get_items_total_graph($yearselected){

			$this->db->select('COUNT(mcb_invoice_items.item_name) AS item_total' ,FALSE);
			$this->db->select('SUM(mcb_invoice_items.item_qty) AS item_qty' ,FALSE);
			$this->db->select('mcb_invoice_items.item_name AS item_name' ,FALSE);
			$this->db->select('mcb_invoice_stored_items.invoice_stored_unit_price AS stored_price' ,FALSE);
			$this->db->select('SUM(mcb_invoice_item_amounts.item_total) AS total_price' ,FALSE);
			$this->db->from('mcb_invoices');
			$this->db->join('mcb_invoice_items', 'mcb_invoice_items.invoice_id = mcb_invoices.invoice_id');
			$this->db->join('mcb_invoice_stored_items', 'mcb_invoice_stored_items.invoice_stored_item = mcb_invoice_items.item_name');
			$this->db->join('mcb_invoice_item_amounts','mcb_invoice_item_amounts.invoice_item_id = mcb_invoice_items.invoice_item_id');
			$this->db->where('FROM_UNIXTIME(invoice_date_entered, "%Y")=',$yearselected);
			$this->db->group_by('item_name');
			$this->db->order_by("item_total", "asc");
			$this->db->order_by("item_qty", "asc");

			$graphsql = $this->db->get();
		return $graphsql;
	}

	function get_inventory_total_graph($yearselected){

			//SELECT
			$this->db->select('COUNT(mcb_inventory.inventory_id) AS sold_amount' ,FALSE);
			$this->db->select('mcb_inventory.inventory_id' ,FALSE);
			$this->db->select('mcb_inventory.inventory_name' ,FALSE);
			$this->db->select('mcb_inventory.inventory_unit_price' ,FALSE);
			$this->db->select('SUM(mcb_invoice_items.item_price) AS income_amount' ,FALSE);
			$this->db->select('mcb_inventory_types.inventory_type_id' ,FALSE);
			$this->db->select('mcb_inventory_types.inventory_type' ,FALSE);
			$this->db->select('SUM(mcb_inventory_stock.inventory_stock_quantity) AS inventory_stock_quantity' ,FALSE);
			//FROM
			$this->db->from('mcb_inventory');
			//JOIN
			$this->db->join('mcb_invoice_items','mcb_invoice_items.inventory_id = mcb_inventory.inventory_id');
			$this->db->join('mcb_inventory_types','mcb_inventory_types.inventory_type_id = mcb_inventory.inventory_type_id');
			$this->db->join('mcb_inventory_stock','mcb_inventory_stock.invoice_item_id = mcb_inventory.inventory_id');
			//WHERE

			//GROUP BY
			$this->db->group_by('mcb_inventory.inventory_id');

			$graphsql = $this->db->get();
		return $graphsql;
	}

	function check_enable($module){
		$this->db->select('module_path AS module_path' ,FALSE);
		$this->db->select('module_enabled AS module_enabled' ,FALSE);
		$this->db->from('mcb_modules');
		$this->db->where('module_path =',$module);

		$graphsql = $this->db->get();

		return $graphsql->result_array();
	}

}

?>