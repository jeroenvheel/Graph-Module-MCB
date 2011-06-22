<dl>
	<dt><?php echo $this->lang->line('graph_setting_year');?></dt>
	<dd>
		<dd><input type="text" name="graph_setting_year" value="<?php echo $this->mdl_mcb_data->setting('graph_setting_year'); ?>" /></dd>
	</dd>
	
	<dt><?php echo $this->lang->line('graph_setting_total'); ?></dt>
	<dd>
		<dd><input type="checkbox" name="graph_setting_total" value="TRUE" <?php if($this->mdl_mcb_data->setting('graph_setting_total')){?>checked<?php }?> /></dd>
	</dd>
	
	<dt><?php echo $this->lang->line('graph_setting_paid'); ?></dt>
	<dd>
		<dd><input type="checkbox" name="graph_setting_paid" value="TRUE" <?php if($this->mdl_mcb_data->setting('graph_setting_paid')){?>checked<?php }?> /></dd>
	</dd>
	
	<dt><?php echo $this->lang->line('graph_setting_discount'); ?></dt>
	<dd>
		<dd><input type="checkbox" name="graph_setting_discount" value="TRUE" <?php if($this->mdl_mcb_data->setting('graph_setting_discount')){?>checked<?php }?> /></dd>
	</dd>
	
	<dt><?php echo $this->lang->line('graph_setting_shipping'); ?></dt>
	<dd>
		<dd><input type="checkbox" name="graph_setting_shipping" value="TRUE" <?php if($this->mdl_mcb_data->setting('graph_setting_shipping')){?>checked<?php }?> /></dd>
	</dd>
	
	<dt><?php echo $this->lang->line('graph_setting_payment'); ?></dt>
	<dd>
		<dd><input type="checkbox" name="graph_setting_payment" value="TRUE" <?php if($this->mdl_mcb_data->setting('graph_setting_payment')){?>checked<?php }?>  /></dd>
	</dd>
	
	<dt><?php echo $this->lang->line('graph_setting_expense'); ?></dt>
	<dd>
		<dd><input type="checkbox" name="graph_setting_expense" value="TRUE" <?php if($this->mdl_mcb_data->setting('graph_setting_expense')){?>checked<?php }?> /></dd>
	</dd>
	
	<dt><?php echo $this->lang->line('graph_setting_contract'); ?></dt> 
	<dd>
		<dd><input type="checkbox" name="graph_setting_contract" value="TRUE" <?php if($this->mdl_mcb_data->setting('graph_setting_contract')){?>checked<?php }?> /></dd>
	</dd>
</dl>