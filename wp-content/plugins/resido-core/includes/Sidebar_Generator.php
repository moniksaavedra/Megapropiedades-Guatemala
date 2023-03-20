<?php
/*
Plugin Name: Sidebar Generator
Plugin URI: http://www.getson.info
Description: This plugin generates as many sidebars as you need. Then allows you to place them on any page you wish. Version 1.1 now supports themes with multiple sidebars.
Version: 1.1.0
Author: Kyle Getson
Author URI: http://www.kylegetson.com
Copyright (C) 2009 Kyle Robert Getson
*/

namespace Resido\Helper;

if (!class_exists('Sidebar_Generator')) {

	class Sidebar_Generator
	{

		protected static $instance = null;

		public static function init()
		{
			static $instance = false;

			if (!$instance) {
				$instance = new self();
			}

			return $instance;
		}


		function __construct()
		{
			add_action('init', array($this, 'widget_init'));
			add_action('admin_menu', array($this, 'admin_menu'));
			add_action('admin_print_scripts', array($this, 'admin_print_scripts'));
			add_action('wp_ajax_add_sidebar', array($this, 'add_sidebar'));
			add_action('wp_ajax_remove_sidebar', array($this, 'remove_sidebar'));
		}
		function admin_menu()
		{
			add_theme_page('Sidebars', 'Sidebars', 'manage_options', 'multiple_sidebars', array($this, 'admin_page'));
		}
		function widget_init()
		{
			$sidebars = $this->get_sidebars();

			if (is_array($sidebars)) {
				foreach ($sidebars as $sidebar) {

					$sidebarid        = preg_replace_callback(
						'/[^A-Za-z0-9]+/',
						function ($match) {
							return '-';
						},
						strtolower($sidebar)
					);
					$sidebar_class = $this->name_to_class($sidebar);

					register_sidebar(
						array(
							'id'            => $sidebarid,
							'name'          => $sidebar,
							'before_widget' => '<div class="single-widgets %2$s" id="%1$s"> ',
							'after_widget'  => '</div>',
							'before_title'  => '<div class="widget-title"><h4>',
							'after_title'   => '</Requests_Exception_HTTP_402></div>',
						)
					);
				}
			}
		}

		function admin_print_scripts()
		{
			wp_print_scripts(array('sack'));
?>
			<script>
				function add_sidebar(sidebar_name) {

					var mysack = new sack("<?php print esc_url(admin_url('admin-ajax.php')); ?>");

					mysack.execute = 1;
					mysack.method = 'POST';
					mysack.setVar("action", "add_sidebar");
					mysack.setVar("sidebar_name", sidebar_name);
					mysack.encVar("cookie", document.cookie, false);
					mysack.onError = function() {
						alert('Ajax error. Cannot add sidebar')
					};
					mysack.runAJAX();
					return true;
				}

				function remove_sidebar(sidebar_name, num) {

					var mysack = new sack("<?php print esc_url(admin_url('admin-ajax.php')); ?>");
					mysack.execute = 1;
					mysack.method = 'POST';
					mysack.setVar("action", "remove_sidebar");
					mysack.setVar("sidebar_name", sidebar_name);
					mysack.setVar("row_number", num);
					mysack.encVar("cookie", document.cookie, false);
					mysack.onError = function() {
						alert('Ajax error. Cannot add sidebar')
					};
					mysack.runAJAX();
					//alert('hi!:::'+sidebar_name);
					return true;
				}
			</script>
		<?php
		}

		function add_sidebar()
		{
			$sidebars = $this->get_sidebars();
			$name     = str_replace(array("\n", "\r", "\t"), '', $_POST['sidebar_name']);
			$id       = $this->name_to_class($name);
			if (isset($sidebars[$id])) {
				die("alert('Sidebar already exists, please use a different name.')");
			}

			$sidebars[$id] = $name;
			$this->update_sidebars($sidebars);

			$js = "
			var tbl = document.getElementById('sbg_table');
			var lastRow = tbl.rows.length;
			// if there's no header row in the table, then iteration = lastRow + 1
			var iteration = lastRow;
			var row = tbl.insertRow(lastRow);
			
			// left cell
			var cellLeft = row.insertCell(0);
			var textNode = document.createTextNode('$name');
			cellLeft.appendChild(textNode);
			
			//middle cell
			var cellLeft = row.insertCell(1);
			var textNode = document.createTextNode('$id');
			cellLeft.appendChild(textNode);
			
			//var cellLeft = row.insertCell(2);
			//var textNode = document.createTextNode('[<a href=\'javascript:void(0);\' class=\"button action\" onclick=\'return remove_sidebar_link($name);\'>Remove</a>]');
			//cellLeft.appendChild(textNode)
			
			var cellLeft = row.insertCell(2);
			removeLink = document.createElement('a');
      		linkText = document.createTextNode('remove');
			removeLink.setAttribute('onclick', 'remove_sidebar_link(\'$name\')');
			removeLink.setAttribute('class', 'button action');
			removeLink.setAttribute('href', 'javacript:void(0)');
        
      		removeLink.appendChild(linkText);
      		cellLeft.appendChild(removeLink);

			
		";

			die("$js");
		}

		function remove_sidebar()
		{
			$sidebars = $this->get_sidebars();
			$name     = str_replace(array("\n", "\r", "\t"), '', $_POST['sidebar_name']);
			$id       = $this->name_to_class($name);
			if (!isset($sidebars[$id])) {
				die("alert('Sidebar does not exist.')");
			}
			$row_number = $_POST['row_number'];
			unset($sidebars[$id]);
			$this->update_sidebars($sidebars);
			$js = "
			var tbl = document.getElementById('sbg_table');
			tbl.deleteRow($row_number)

		";
			die($js);
		}



		function admin_page()
		{
		?>
			<script>
				function remove_sidebar_link(name, num) {
					answer = confirm("Are you sure you want to remove " + name +
						"?\nThis will remove any widgets you have assigned to this sidebar.");
					if (answer) {
						//alert('AJAX REMOVE');
						remove_sidebar(name, num);
					} else {
						return false;
					}
				}

				function add_sidebar_link() {

					var sidebar_name = document.getElementById("sds_sidebar_name").value;

					if (sidebar_name === "") {
						alert("Please Enter a Sidebar Name");
						return false;
					}

					//    alert(sidebar_name);
					add_sidebar(sidebar_name);
				}
			</script>
			<div class="wrap">
				<h2><?php esc_html_e('Sidebar Generator', 'hill'); ?></h2>
				<p><?php esc_html_e('The sidebar name is for your use only. It will not be visible to any of your visitors. A CSS class is assigned to each of your sidebar, use this styling to customize the sidebars.', 'hill'); ?>
				</p>
				<br>

				<table class="widefat page" id="sbg_table" style="width:600px;">
					<tr>
						<th><?php esc_html_e('Name', 'hill'); ?></th>
						<th><?php esc_html_e('CSS class', 'hill'); ?></th>
						<th><?php esc_html_e('Remove', 'hill'); ?></th>
					</tr>
					<?php
					$sidebars = $this->get_sidebars();
					// $sidebars = array('bob','john','mike','asdf');
					if (is_array($sidebars) && !empty($sidebars)) {
						$cnt = 0;
						foreach ($sidebars as $sidebar) {
							$alt = ($cnt % 2 == 0 ? 'alternate' : '');
					?>
							<tr class="<?php print esc_attr($alt); ?>">
								<td><?php print wp_kses_post($sidebar); ?></td>
								<td><?php print $this->name_to_class($sidebar); ?></td>
								<td><a href="javascript:void(0);" class="button action" onclick="return remove_sidebar_link('<?php print esc_js($sidebar); ?>',<?php print esc_js($cnt + 1); ?>);" title="Remove this sidebar"><?php esc_html_e('remove', 'hill'); ?></a></td>
							</tr>
						<?php
							$cnt++;
						}
					} else {
						?>
						<tr>
							<td colspan="3"><?php esc_html_e('No Sidebars defined', 'hill'); ?></td>
						</tr>
					<?php
					}
					?>
				</table>
				<br>
				<br>
				<div class="add_sidebar">
					<input class="components-text-control__input" type="text" placeholder="Sidebar Name" id="sds_sidebar_name" value="">
					<a href="javascript:void(0);" onclick="return add_sidebar_link()" title="Add a sidebar" class="button button-primary"><?php esc_html_e('+ Add Sidebar', 'hill'); ?></a>

				</div>

			</div>
<?php
		}


		function get_sidebar($name = '0')
		{
			if (!is_singular()) {
				if ($name != '0') {
					if (is_active_sidebar($name)) {
						dynamic_sidebar($name);
					}
				} else {
					if (is_active_sidebar('default_sidebar')) {
						dynamic_sidebar('default_sidebar');
					}
				}
				return; // dont do anything
			}
			global $wp_query;
			$post                         = $wp_query->get_queried_object();
			$selected_sidebar             = get_post_meta($post->ID, 'sbg_selected_sidebar', true);
			$selected_sidebar_replacement = get_post_meta($post->ID, 'sbg_selected_sidebar_replacement', true);
			$did_sidebar                  = false;
			// this page uses a generated sidebar
			if ($selected_sidebar != '' && $selected_sidebar != '0') {
				print "\n\n<!-- begin generated sidebar -->\n";
				if (is_array($selected_sidebar) && !empty($selected_sidebar)) {
					for ($i = 0; $i < sizeof($selected_sidebar); $i++) {

						if ($name == '0' && $selected_sidebar[$i] == '0' && $selected_sidebar_replacement[$i] == '0') {
							// print "\n\n<!-- [called $name selected {$selected_sidebar[$i]} replacement {$selected_sidebar_replacement[$i]}] -->";
							if (is_active_sidebar('default_sidebar')) {
								dynamic_sidebar('default_sidebar');
							}
							$did_sidebar = true;
							break;
						} elseif ($name == '0' && $selected_sidebar[$i] == '0') {
							// we are replacing the default sidebar with something
							// print "\n\n<!-- [called $name selected {$selected_sidebar[$i]} replacement {$selected_sidebar_replacement[$i]}] -->";
							if (is_active_sidebar($selected_sidebar_replacement[$i])) {
								dynamic_sidebar($selected_sidebar_replacement[$i]);
							}
							$did_sidebar = true;
							break;
						} elseif ($selected_sidebar[$i] == $name) {
							// we are replacing this $name
							// print "\n\n<!-- [called $name selected {$selected_sidebar[$i]} replacement {$selected_sidebar_replacement[$i]}] -->";
							$did_sidebar = true;
							if (is_active_sidebar($selected_sidebar_replacement[$i])) {
								dynamic_sidebar($selected_sidebar_replacement[$i]);
							}
							break;
						}
						// print "<!-- called=$name selected={$selected_sidebar[$i]} replacement={$selected_sidebar_replacement[$i]} -->\n";
					}
				}
				if ($did_sidebar == true) {
					print "\n<!-- end generated sidebar -->\n\n";
					return;
				}
				// go through without finding any replacements, lets just send them what they asked for
				if ($name != '0') {
					if (is_active_sidebar($name)) {
						dynamic_sidebar($name);
					}
				} else {
					if (is_active_sidebar('default_sidebar')) {
						dynamic_sidebar('default_sidebar');
					}
				}
				print "\n<!-- end generated sidebar -->\n\n";
				return;
			} else {
				if ($name != '0') {
					if (is_active_sidebar($name)) {
						dynamic_sidebar($name);
					}
				} else {
					if (is_active_sidebar('default_sidebar')) {
						dynamic_sidebar('default_sidebar');
					}
				}
			}
		}

		/**
		 * replaces array of sidebar names
		 */
		function update_sidebars($sidebar_array)
		{
			$sidebars = update_option('sbg_sidebars', $sidebar_array);
		}

		/**
		 * gets the generated sidebars
		 */
		function get_sidebars()
		{
			$sidebars = get_option('sbg_sidebars');
			return $sidebars;
		}
		function name_to_class($name)
		{
			$class = str_replace(array(' ', ',', '.', '"', "'", '/', '\\', '+', '=', ')', '(', '*', '&', '^', '%', '$', '#', '@', '!', '~', '`', '<', '>', '?', '[', ']', '{', '}', '|', ':'), '', $name);
			return $class;
		}
	}
}
