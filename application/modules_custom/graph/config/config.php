<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config = array(
	'module_path'			=>	'graph',
	'module_name'			=>	'Graph',
	'module_description'	=>	'Graph data from MyClientBase.',
	'module_author'			=>	'Jeroen van Heel',
	'module_homepage'		=>	'http://www.theevilteddys.nl',
	'module_version'		=>	'0.9.2',
	'module_config'			=>	array(
		'settings_view'		=>	'graph/graph_settings/display',
		'settings_save'		=>	'graph/graph_settings/save'
	)
);

?>