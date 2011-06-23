<?php $this->load->view('dashboard/header'); ?>

<div class="container_10" id="center_wrapper">

	<div class="grid_10" id="content_wrapper">

		<div class="section_wrapper">

			<h3 class="title_black" id="year_head"><?php echo $this->lang->line('graph_view'); ?> <?php echo $this->mdl_mcb_data->setting('graph_setting_year'); ?></h3>

			<?php $this->load->view('dashboard/system_messages'); ?>

			<div class="content toggle">

				<div id="yearly_graph" style="width:930px; height:300px;">

				</div>
						<!--<a href="javascript:void(0)" rel="<?=$previous?>" id="previous"><?php echo $this->lang->line('graph_previous'); ?></a>-->
						<!--<a href="javascript:void(0)" rel="<?=$next?>" id="next"><?php echo $this->lang->line('graph_next'); ?></a>-->
			</div>

		</div>
	</div>

	<div class="grid_4" id="content_wrapper">
		<div class="section_wrapper">

			<h3 class="title_black"><?php echo $this->lang->line('graph_legend'); ?></h3>

			<div class="content toggle">

				<div id="legend_graph">
					<div id="interactive">
					</div>
					<div id="hover">
					</div>
				</div>

			</div>

		</div>

	</div>

	<div class="grid_6" id="content_wrapper">
	<form method="post" id="setting_form" action="<?php echo site_url($this->uri->uri_string()); ?>">
		<div class="section_wrapper">

			<h3 class="title_black"><?php echo $this->lang->line('graph_string'); ?>

			<!-- <input type="submit" name="btn_save_settings" style="float: right; margin-top: 10px; margin-right: 10px;" value="<?php echo $this->lang->line('save_settings'); ?>" /> -->
			<!-- <?php $this->load->view('dashboard/btn_add', array('btn_name'=>'item_Chart', 'btn_value'=>$this->lang->line('graph_btn_item'))); ?> -->
			<?php $this->load->view('dashboard/btn_add', array('btn_name'=>'inventory_Chart', 'btn_value'=>$this->lang->line('graph_btn_inventory'))); ?>
			<?php $this->load->view('dashboard/btn_add', array('btn_name'=>'Bar_Chart', 'btn_value'=>$this->lang->line('graph_btn_bar'))); ?>
			<?php $this->load->view('dashboard/btn_add', array('btn_name'=>'Line_Chart', 'btn_value'=>$this->lang->line('graph_btn_line'))); ?>
			</h3>

			<div class="content toggle">

				<div id="settings_graph">
					<!--<dl>
						<dt><?php echo $this->lang->line('graph_setting_year');?></dt>
						<dd>
							<dd><input type="text" id="year_txt" name="graph_setting_year" value="<?php echo $this->mdl_mcb_data->setting('graph_setting_year'); ?>" /></dd>
						</dd>

					</dl>-->
				</div>

			</div>

		</div>
		</form>
	</div>

</div>

<?php $this->load->view('dashboard/sidebar'); ?>

<script id="source" language="javascript" type="text/javascript">
	$(function () {
		var title_str = '<?=$this->lang->line('graph_view');?>'
		var ticks = <?= $ticks ?>;
		var full = <?= $full ?>;
		var base_url = '<?= base_url() ?>';
		var options = {
			legend: {
				container: '#legend_graph',
				show: false
			},
			xaxis: {ticks: ticks},
			shadowSize: 4,
			//grid: {
			//	hoverable: true,
			//	clickable: true
			//},
			series: {
				pie: {
					combine:{
						threshold:0.02,
						label: 'Combined'
					},
					innerRadius: 0.6,
					show: true,
					radius: 1,
					tilt: 0.3,
					label:{
						show: true,
						formatter: function(label, full){
							return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">'+label+'<br/>'+Math.round(full.percent)+'%</div>';
						},
						background: { 
							opacity: 0.3 
						}
					}
				}
			}
		};

		$.plot($("#yearly_graph"), full,options);

		$("#previous").click(function(){
			var pre_rel = $(this).attr('rel');
			var next_rel = $("#next").attr('rel');
			pre_str = title_str+" "+pre_rel;
			$.post(base_url+"index.php/<?= $this->uri->uri_string()?>/ajax_load/"+ Math.random(),{year:$(this).attr('rel')},function(data){
				$("#previous").attr('rel',(parseInt(pre_rel)-1));
				$("#next").attr('rel',(parseInt(next_rel)-1));
				$("#year_head").html(pre_str);
				$("#year_txt").val(pre_rel);
				$.plot($("#yearly_graph"), data,options);
			},'json');
		});

		$("#next").click(function(){
			var next_rel = $(this).attr('rel');
			var pre_rel = $("#previous").attr('rel');
			next_str = title_str+" "+next_rel;
			$.post(base_url+"index.php/<?= $this->uri->uri_string()?>/ajax_load/"+ Math.random(),{year:$(this).attr('rel')},function(data){
				$("#previous").attr('rel',(parseInt(pre_rel)+1));
				$("#next").attr('rel',(parseInt(next_rel)+1));
				$("#year_head").html(next_str);
				$("#year_txt").val(next_rel);
					$.plot($("#yearly_graph"), data,options);
			},'json');
		});

		$("#year_txt").keyup(function(){
			var datastr = $('#setting_form').serialize();
			var year_txt = $("#year_txt").val();
			if($(this).val().length == 4){
			 $.post(base_url+"index.php/<?= $this->uri->uri_string()?>/ajax_custom_save/"+ Math.random(),datastr,function(data){
				$.plot($("#yearly_graph"), data,options);
				$("#previous").attr('rel',parseInt(year_txt)-1);
				$("#next").attr('rel',parseInt(year_txt)+1);
				$("#year_head").html(title_str+" "+year_txt);
			 },'json');
			}
		})
	});
</script>


<?php $this->load->view('dashboard/footer'); ?>
