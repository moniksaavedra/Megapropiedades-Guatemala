<?php
/*
	Copyright 2015-2018  John Havlik  (email : john.havlik@mtekk.us)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCRESIDOBILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
require_once dirname(__FILE__) . '/includes/block_direct_access.php';
// Do a PHP version check, require 5.3 or newer
if (version_compare(phpversion(), '5.3.0', '<')) {
	// Only purpose of this function is to echo out the PHP version error
	function bcn_phpold()
	{
		printf('<div class="notice notice-error"><p>' . __('Your PHP version is too old, please upgrade to a newer version. Your version is %1$s, Breadcrumb NavXT requires %2$s', 'solustrid-core') . '</p></div>', phpversion(), '5.3.0');
	}
	// If we are in the admin, let's print a warning then return
	if (is_admin()) {
		add_action('admin_notices', 'bcn_phpold');
	}
	return;
}
// Include admin base class
if (!class_exists('mtekk_adminKit')) {
	require_once dirname(__FILE__) . '/includes/class.mtekk_adminkit.php';
}
/**
 * The administrative interface class
 */
class bcn_admin extends mtekk_adminKit
{

	const version               = '6.2.0';
	protected $full_name        = 'Breadcrumb NavXT Settings';
	protected $short_name       = 'Breadcrumb NavXT';
	protected $access_level     = 'manage_options';
	protected $identifier       = 'solustrid-core';
	protected $unique_prefix    = 'bcn';
	protected $plugin_basename  = null;
	protected $support_url      = 'https://wordpress.org/support/plugin/breadcrumb-navxt/';
	protected $breadcrumb_trail = null;
	/**
	 * Administrative interface class default constructor
	 *
	 * @param bcn_breadcrumb_trail $breadcrumb_trail a breadcrumb trail object
	 * @param string               $basename The basename of the plugin
	 */
	function __construct(bcn_breadcrumb_trail &$breadcrumb_trail, $basename)
	{
		$this->breadcrumb_trail = &$breadcrumb_trail;
		$this->plugin_basename  = $basename;
		$this->full_name        = esc_html__('Breadcrumb NavXT Settings', 'solustrid-core');
		// Grab defaults from the breadcrumb_trail object
		$this->opt = &$this->breadcrumb_trail->opt;
		// We're going to make sure we load the parent's constructor
		parent::__construct();
	}
	/**
	 * admin initialization callback function
	 *
	 * is bound to WordPress action 'admin_init' on instantiation
	 *
	 * @since  3.2.0
	 * @return void
	 */
	function init()
	{
		// We're going to make sure we run the parent's version of this function as well
		parent::init();
	}
	function wp_loaded()
	{
		parent::wp_loaded();
		breadcrumb_navxt::setup_options($this->opt);
	}
	/**
	 * Sets hard constants into the options array
	 *
	 * @param &$opts The options array to set hard constants into
	 */
	function opts_fix(&$opts)
	{
		$opts['bpost_page_hierarchy_display'] = true;
		$opts['Spost_page_hierarchy_type']    = 'BCN_POST_PARENT';
		$opts['apost_page_root']              = get_option('page_on_front');
	}
	/**
	 * Upgrades input options array, sets to $this->opt
	 *
	 * @param array  $opts
	 * @param string $version the version of the passed in options
	 */
	function opts_upgrade($opts, $version)
	{
		global $wp_post_types, $wp_taxonomies;
		// If our version is not the same as in the db, time to update
		if (version_compare($version, $this::version, '<')) {
			// Upgrading to 3.8.1
			if (version_compare($version, '3.8.1', '<')) {
				$opts['post_page_root'] = $this->get_option('page_on_front');
				$opts['post_post_root'] = $this->get_option('page_for_posts');
			}
			// Upgrading to 4.0
			if (version_compare($version, '4.0.0', '<')) {
				// Only migrate if we haven't migrated yet
				if (isset($opts['current_item_linked'])) {
					// Loop through the old options, migrate some of them
					foreach ($opts as $option => $value) {
						// Handle all of our boolean options first, they're real easy, just add a 'b'
						if (strpos($option, 'display') > 0 || $option == 'current_item_linked') {
							$this->breadcrumb_trail->opt['b' . $option] = $value;
						}
						// Handle migration of anchor templates to the templates
						elseif (strpos($option, 'anchor') > 0) {
							$parts = explode('_', $option);
							// Do excess slash removal sanitation
							$this->breadcrumb_trail->opt['H' . $parts[0] . '_template'] = $value . '%htitle%</a>';
						}
						// Handle our abs integers
						elseif ($option == 'max_title_length' || $option == 'post_post_root' || $option == 'post_page_root') {
							$this->breadcrumb_trail->opt['a' . $option] = $value;
						}
						// Now everything else, minus prefix and suffix
						elseif (strpos($option, 'prefix') === false && strpos($option, 'suffix') === false) {
							$this->breadcrumb_trail->opt['S' . $option] = $value;
						}
					}
				}
				// Add in the new settings for CPTs introduced in 4.0
				foreach ($wp_post_types as $post_type) {
					// We only want custom post types
					if (!$post_type->_builtin) {
						// Add in the archive_display option
						$this->breadcrumb_trail->opt['bpost_' . $post_type->name . '_archive_display'] = $post_type->has_archive;
					}
				}
				$opts = $this->breadcrumb_trail->opt;
			}
			if (version_compare($version, '4.0.1', '<')) {
				if (isset($opts['Hcurrent_item_template_no_anchor'])) {
					unset($opts['Hcurrent_item_template_no_anchor']);
				}
				if (isset($opts['Hcurrent_item_template'])) {
					unset($opts['Hcurrent_item_template']);
				}
			}
			// Upgrading to 4.3.0
			if (version_compare($version, '4.3.0', '<')) {
				// Removed home_title
				if (isset($opts['Shome_title'])) {
					unset($opts['Shome_title']);
				}
				// Removed mainsite_title
				if (isset($opts['Smainsite_title'])) {
					unset($opts['Smainsite_title']);
				}
			}
			// Upgrading to 5.1.0
			if (version_compare($version, '5.1.0', '<')) {
				foreach ($wp_taxonomies as $taxonomy) {
					// If we have the old options style for it, update
					if ($taxonomy->name !== 'post_format' && isset($opts['H' . $taxonomy->name . '_template'])) {
						// Migrate to the new setting name
						$opts['Htax_' . $taxonomy->name . '_template']           = $opts['H' . $taxonomy->name . '_template'];
						$opts['Htax_' . $taxonomy->name . '_template_no_anchor'] = $opts['H' . $taxonomy->name . '_template_no_anchor'];
						// Clean up old settings
						unset($opts['H' . $taxonomy->name . '_template']);
						unset($opts['H' . $taxonomy->name . '_template_no_anchor']);
					}
				}
			}
			// Upgrading to 5.4.0
			if (version_compare($version, '5.4.0', '<')) {
				// Migrate users to schema.org breadcrumbs for author and search if still on the defaults for posts
				if ($opts['Hpost_post_template'] === bcn_breadcrumb::get_default_template() && $opts['Hpost_post_template_no_anchor'] === bcn_breadcrumb::default_template_no_anchor) {
					if ($opts['Hpaged_template'] === 'Page %htitle%') {
						$opts['Hpaged_template'] = $this->opt['Hpaged_template'];
					}
					if ($opts['Hsearch_template'] === 'Search results for &#39;<a title="Go to the first page of search results for %title%." href="%link%" class="%type%">%htitle%</a>&#39;' || $opts['Hsearch_template'] === 'Search results for &#039;<a title="Go to the first page of search results for %title%." href="%link%" class="%type%">%htitle%</a>&#039;') {
						$opts['Hsearch_template'] = $this->opt['Hsearch_template'];
					}
					if ($opts['Hsearch_template_no_anchor'] === 'Search results for &#39;%htitle%&#39;' || $opts['Hsearch_template_no_anchor'] === 'Search results for &#039;%htitle%&#039;') {
						$opts['Hsearch_template_no_anchor'] = $this->opt['Hsearch_template_no_anchor'];
					}
					if ($opts['Hauthor_template'] === 'Articles by: <a title="Go to the first page of posts by %title%." href="%link%" class="%type%">%htitle%</a>') {
						$opts['Hauthor_template'] = $this->opt['Hauthor_template'];
					}
					if ($opts['Hauthor_template_no_anchor'] === 'Articles by: %htitle%') {
						$opts['Hauthor_template_no_anchor'] = $this->opt['Hauthor_template_no_anchor'];
					}
				}
			}
			// Upgrading to 5.5.0
			if (version_compare($version, '5.5.0', '<')) {
				// Translate the old 'page' taxonomy type to BCN_POST_PARENT
				if ($this->opt['Spost_post_taxonomy_type'] === 'page') {
					$this->opt['Spost_post_taxonomy_type'] = 'BCN_POST_PARENT';
				}
				if (!isset($this->opt['Spost_post_taxonomy_referer'])) {
					$this->opt['bpost_post_taxonomy_referer'] = false;
				}
				// Loop through all of the post types in the array
				foreach ($wp_post_types as $post_type) {
					// Check for non-public CPTs
					if (!apply_filters('bcn_show_cpt_private', $post_type->public, $post_type->name)) {
						continue;
					}
					// We only want custom post types
					if (!$post_type->_builtin) {
						// Translate the old 'page' taxonomy type to BCN_POST_PARENT
						if ($this->opt['Spost_' . $post_type->name . '_taxonomy_type'] === 'page') {
							$this->opt['Spost_' . $post_type->name . '_taxonomy_type'] = 'BCN_POST_PARENT';
						}
						// Translate the old 'date' taxonomy type to BCN_DATE
						if ($this->opt['Spost_' . $post_type->name . '_taxonomy_type'] === 'date') {
							$this->opt['Spost_' . $post_type->name . '_taxonomy_type'] = 'BCN_DATE';
						}
						if (!isset($this->opt['Spost_' . $post_type->name . '_taxonomy_referer'])) {
							$this->opt['bpost_' . $post_type->name . '_taxonomy_referer'] = false;
						}
					}
				}
			}
			// Upgrading to 6.0.0
			if (version_compare($version, '6.0.0', '<')) {
				// Loop through all of the post types in the array
				foreach ($wp_post_types as $post_type) {
					if (isset($this->opt['Spost_' . $post_type->name . '_taxonomy_type'])) {
						$this->opt['Spost_' . $post_type->name . '_hierarchy_type'] = $this->opt['Spost_' . $post_type->name . '_taxonomy_type'];
						unset($this->opt['Spost_' . $post_type->name . '_taxonomy_type']);
					}
					if (isset($this->opt['Spost_' . $post_type->name . '_taxonomy_display'])) {
						$this->opt['Spost_' . $post_type->name . '_hierarchy_display'] = $this->opt['Spost_' . $post_type->name . '_taxonomy_display'];
						unset($this->opt['Spost_' . $post_type->name . '_taxonomy_display']);
					}
				}
			}
			// Set the max title length to 20 if we are not limiting the title and the length was 0
			if (!$opts['blimit_title'] && $opts['amax_title_length'] == 0) {
				$opts['amax_title_length'] = 20;
			}
		}
		// Save the passed in opts to the object's option array
		$this->opt = mtekk_adminKit::parse_args($opts, $this->opt);
		// End with resetting up the options
		breadcrumb_navxt::setup_options($this->opt);
	}
	function opts_update_prebk(&$opts)
	{
		// This may no longer be needed
		breadcrumb_navxt::setup_options($opts);
		$opts = apply_filters('bcn_opts_update_prebk', $opts);
	}
	/**
	 * help action hook function
	 *
	 * @return string
	 */
	function help()
	{
		$screen = get_current_screen();
		// Exit early if the add_help_tab function doesn't exist
		if (!method_exists($screen, 'add_help_tab')) {
			return;
		}
		// Add contextual help on current screen
		if ($screen->id == 'settings_page_' . $this->identifier) {
			$general_tab = '<p>' . esc_html__('Tips for the settings are located below select options.', 'solustrid-core') .
				'</p><h5>' . esc_html__('Resources', 'solustrid-core') . '</h5><ul><li>' .
				sprintf(esc_html__("%1\$sTutorials and How Tos%2\$s: There are several guides, tutorials, and how tos available on the author's website.", 'solustrid-core'), '<a title="' . esc_attr__('Go to the Breadcrumb NavXT tag archive.', 'solustrid-core') . '" href="https://mtekk.us/archives/tag/breadcrumb-navxt">', '</a>') . '</li><li>' .
				sprintf(esc_html__('%1$sOnline Documentation%2$s: Check out the documentation for more indepth technical information.', 'solustrid-core'), '<a title="' . esc_attr__('Go to the Breadcrumb NavXT online documentation', 'solustrid-core') . '" href="https://mtekk.us/code/breadcrumb-navxt/breadcrumb-navxt-doc/">', '</a>') . '</li><li>' .
				sprintf(esc_html__('%1$sReport a Bug%2$s: If you think you have found a bug, please include your WordPress version and details on how to reproduce the bug.', 'solustrid-core'), '<a title="' . esc_attr__('Go to the Breadcrumb NavXT support post for your version.', 'solustrid-core') . '" href="https://wordpress.org/support/plugin/breadcrumb-navxt/">', '</a>') . '</li></ul>' .
				'<h5>' . esc_html__('Giving Back', 'solustrid-core') . '</h5><ul><li>' .
				sprintf(esc_html__('%1$sDonate%2$s: Love Breadcrumb NavXT and want to help development? Consider buying the author a beer.', 'solustrid-core'), '<a title="' . esc_attr__('Go to PayPal to give a donation to Breadcrumb NavXT.', 'solustrid-core') . '" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=FD5XEU783BR8U&lc=US&item_name=Breadcrumb%20NavXT%20Donation&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted">', '</a>') . '</li><li>' .
				sprintf(esc_html__('%1$sTranslate%2$s: Is your language not available? Visit the Breadcrumb NavXT translation project on WordPress.org to start translating.', 'solustrid-core'), '<a title="' . esc_attr__('Go to the Breadcrumb NavXT translation project.', 'solustrid-core') . '" href="https://translate.wordpress.org/projects/wp-plugins/breadcrumb-navxt">', '</a>') . '</li></ul>';

			$screen->add_help_tab(
				array(
					'id'      => $this->identifier . '-base',
					'title'   => __('General', 'solustrid-core'),
					'content' => $general_tab,
				)
			);
			$quickstart_tab = '<p>' . esc_html__('For the settings on this page to take effect, you must either use the included Breadcrumb NavXT widget, or place either of the code sections below into your theme.', 'solustrid-core') .
				'</p><h5>' . esc_html__('Breadcrumb trail with separators', 'solustrid-core') . '</h5><pre><code>&lt;div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/"&gt;' . "
	&lt;?php if(function_exists('bcn_display'))
	{
		bcn_display();
	}?&gt;
&lt;/div&gt;</code></pre>" .
				'<h5>' . esc_html__('Breadcrumb trail in list form', 'solustrid-core') . '</h5><pre><code>&lt;ol class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/"&gt;' . "
	&lt;?php if(function_exists('bcn_display_list'))
	{
		bcn_display_list();
	}?&gt;
&lt;/ol&gt;</code></pre>";
			$screen->add_help_tab(
				array(
					'id'      => $this->identifier . '-quick-start',
					'title'   => __('Quick Start', 'solustrid-core'),
					'content' => $quickstart_tab,
				)
			);
			$styling_tab = '<p>' . esc_html__('Using the code from the Quick Start section above, the following CSS can be used as base for styling your breadcrumb trail.', 'solustrid-core') . '</p>' .
				'<pre><code>.breadcrumbs
{
	font-size: 1.1em;
	color: #fff;
	margin: 30px 0 0 10px;
	position: relative;
	float: left;
}</code></pre>';
			$screen->add_help_tab(
				array(
					'id'      => $this->identifier . '-styling',
					'title'   => __('Styling', 'solustrid-core'),
					'content' => $styling_tab,
				)
			);
			$screen->add_help_tab(
				array(
					'id'      => $this->identifier . '-import-export-reset',
					'title'   => __('Import/Export/Reset', 'solustrid-core'),
					'content' => $this->import_form(),
				)
			);
		}
	}
	/**
	 * enqueue's the tab style sheet on the settings page
	 */
	function admin_styles()
	{
		wp_enqueue_style('mtekk_adminkit_tabs');
	}
	/**
	 * enqueue's the tab js and translation js on the settings page
	 */
	function admin_scripts()
	{
		// Enqueue ui-tabs
		wp_enqueue_script('jquery-ui-tabs');
		// Enqueue the admin tabs javascript
		wp_enqueue_script('mtekk_adminkit_tabs');
		// Load the translations for the tabs
		wp_localize_script(
			'mtekk_adminkit_tabs',
			'objectL10n',
			array(
				'mtad_uid'    => 'bcn_admin',
				'mtad_import' => __('Import', 'solustrid-core'),
				'mtad_export' => __('Export', 'solustrid-core'),
				'mtad_reset'  => __('Reset', 'solustrid-core'),
			)
		);
		// Enqueue the admin enable/disable groups javascript
		wp_enqueue_script('mtekk_adminkit_engroups');
	}
	/**
	 * A message function that checks for the BCN_SETTINGS_* define statement
	 */
	function multisite_settings_warn()
	{
		if (is_multisite()) {
			if (defined('BCN_SETTINGS_USE_LOCAL') && BCN_SETTINGS_USE_LOCAL) {
			} elseif (defined('BCN_SETTINGS_USE_NETWORK') && BCN_SETTINGS_USE_NETWORK) {
				$this->messages[] = new mtekk_adminKit_message(esc_html__('Warning: Your network settings will override any settings set in this page.', 'solustrid-core'), 'warning', true, $this->unique_prefix . '_msg_is_nsiteoveride');
			} elseif (defined('BCN_SETTINGS_FAVOR_LOCAL') && BCN_SETTINGS_FAVOR_LOCAL) {
				$this->messages[] = new mtekk_adminKit_message(esc_html__('Warning: Your network settings may override any settings set in this page.', 'solustrid-core'), 'warning', true, $this->unique_prefix . '_msg_is_isitemayoveride');
			} elseif (defined('BCN_SETTINGS_FAVOR_NETWORK') && BCN_SETTINGS_FAVOR_NETWORK) {
				$this->messages[] = new mtekk_adminKit_message(esc_html__('Warning: Your network settings may override any settings set in this page.', 'solustrid-core'), 'warning', true, $this->unique_prefix . '_msg_is_nsitemayoveride');
			}
			// Fall through if no settings mode was set
			else {
				$this->messages[] = new mtekk_adminKit_message(esc_html__('Warning: No BCN_SETTINGS_* define statement found, defaulting to BCN_SETTINGS_USE_LOCAL.', 'solustrid-core'), 'warning', true, $this->unique_prefix . '_msg_is_nosetting');
			}
		}
	}
	/**
	 * A message function that checks for deprecated settings that are set and warns the user
	 */
	function deprecated_settings_warn()
	{
		// We're deprecating the limit title length setting, let the user know the new method of accomplishing this
		if (isset($this->opt['blimit_title']) && $this->opt['blimit_title']) {
			$this->messages[] = new mtekk_adminKit_message(sprintf(esc_html__('Warning: Your are using a deprecated setting "Title Length" (see Miscellaneous &gt; Deprecated), please %1$suse CSS instead%2$s.', 'solustrid-core'), '<a title="' . __('Go to the guide on trimming breadcrumb title lengths with CSS', 'solustrid-core') . '" href="https://mtekk.us/archives/guides/trimming-breadcrumb-title-lengths-with-css/">', '</a>'), 'warning');
		}
	}
	/**
	 * Function checks the current site to see if the blog options should be disabled
	 *
	 * @return boool Whether or not the blog options should be disabled
	 */
	function maybe_disable_blog_options()
	{
		return (get_option('show_on_front') !== 'page' || get_option('page_for_posts') < 1);
	}
	/**
	 * Function checks the current site to see if the mainsite options should be disabled
	 *
	 * @return bool Whether or not the mainsite options should be disabled
	 */
	function maybe_disable_mainsite_options()
	{
		return !is_multisite();
	}
	/**
	 * The administrative page for Breadcrumb NavXT
	 */
	function admin_page()
	{
		global $wp_taxonomies, $wp_post_types;
		$this->security();
		// Do a check on deprecated settings
		$this->deprecated_settings_warn();
		// Do a check for multisite settings mode
		$this->multisite_settings_warn();
		do_action($this->unique_prefix . '_settings_pre_messages', $this->opt);
		// Display our messages
		$this->messages();
?>
		<div class="wrap">
			<h2><?php printf($this->full_name); ?></h2>
			<?php
			// We exit after the version check if there is an action the user needs to take before saving settings
			if (!$this->version_check($this->get_option($this->unique_prefix . '_version'))) {
				return;
			}
			?>
			<form action="<?php printf($this->admin_url()); ?>" method="post" id="bcn_admin-options">
				<?php settings_fields('bcn_options'); ?>
				<div id="hasadmintabs">
					<fieldset id="general" class="bcn_options">
						<h3 class="tab-title" title="<?php _e('A collection of settings most likely to be modified are located under this tab.', 'solustrid-core'); ?>"><?php _e('General', 'solustrid-core'); ?></h3>
						<h3><?php _e('General', 'solustrid-core'); ?></h3>
						<table class="form-table">
							<?php
							$this->textbox(__('Breadcrumb Separator', 'solustrid-core'), 'hseparator', '2', false, __('Placed in between each breadcrumb.', 'solustrid-core'));
							do_action($this->unique_prefix . '_settings_general', $this->opt);
							?>
						</table>
						<h3><?php _e('Current Item', 'solustrid-core'); ?></h3>
						<table class="form-table adminkit-enset-top">
							<?php
							$this->input_check(__('Link Current Item', 'solustrid-core'), 'bcurrent_item_linked', __('Yes', 'solustrid-core'));
							$this->input_check(_x('Paged Breadcrumb', 'Paged as in when on an archive or post that is split into multiple pages', 'solustrid-core'), 'bpaged_display', __('Place the page number breadcrumb in the trail.', 'solustrid-core'), false, __('Indicates that the user is on a page other than the first of a paginated archive or post.', 'solustrid-core'), 'adminkit-enset-ctrl adminkit-enset');
							$this->textbox(_x('Paged Template', 'Paged as in when on an archive or post that is split into multiple pages', 'solustrid-core'), 'Hpaged_template', '4', false, __('The template for paged breadcrumbs.', 'solustrid-core'), 'adminkit-enset');
							do_action($this->unique_prefix . '_settings_current_item', $this->opt);
							?>
						</table>
						<h3><?php _e('Home Breadcrumb', 'solustrid-core'); ?></h3>
						<table class="form-table adminkit-enset-top">
							<?php
							$this->input_check(__('Home Breadcrumb', 'solustrid-core'), 'bhome_display', __('Place the home breadcrumb in the trail.', 'solustrid-core'), false, '', 'adminkit-enset-ctrl adminkit-enset');
							$this->textbox(__('Home Template', 'solustrid-core'), 'Hhome_template', '6', false, __('The template for the home breadcrumb.', 'solustrid-core'), 'adminkit-enset');
							$this->textbox(__('Home Template (Unlinked)', 'solustrid-core'), 'Hhome_template_no_anchor', '4', false, __('The template for the home breadcrumb, used when the breadcrumb is not linked.', 'solustrid-core'), 'adminkit-enset');
							do_action($this->unique_prefix . '_settings_home', $this->opt);
							?>
						</table>
						<h3><?php _e('Blog Breadcrumb', 'solustrid-core'); ?></h3>
						<table class="form-table adminkit-enset-top">
							<?php
							$this->input_check(__('Blog Breadcrumb', 'solustrid-core'), 'bblog_display', __('Place the blog breadcrumb in the trail.', 'solustrid-core'), $this->maybe_disable_blog_options(), '', 'adminkit-enset-ctrl adminkit-enset');
							do_action($this->unique_prefix . '_settings_blog', $this->opt);
							?>
						</table>
						<h3><?php _e('Mainsite Breadcrumb', 'solustrid-core'); ?></h3>
						<table class="form-table adminkit-enset-top">
							<?php
							$this->input_check(__('Main Site Breadcrumb', 'solustrid-core'), 'bmainsite_display', __('Place the main site home breadcrumb in the trail in an multisite setup.', 'solustrid-core'), $this->maybe_disable_mainsite_options(), '', 'adminkit-enset-ctrl adminkit-enset');
							$this->textbox(__('Main Site Home Template', 'solustrid-core'), 'Hmainsite_template', '6', $this->maybe_disable_mainsite_options(), __('The template for the main site home breadcrumb, used only in multisite environments.', 'solustrid-core'), 'adminkit-enset');
							$this->textbox(__('Main Site Home Template (Unlinked)', 'solustrid-core'), 'Hmainsite_template_no_anchor', '4', $this->maybe_disable_mainsite_options(), __('The template for the main site home breadcrumb, used only in multisite environments and when the breadcrumb is not linked.', 'solustrid-core'), 'adminkit-enset');
							do_action($this->unique_prefix . '_settings_mainsite', $this->opt);
							?>
						</table>
						<?php do_action($this->unique_prefix . '_after_settings_tab_general', $this->opt); ?>
					</fieldset>
					<fieldset id="post" class="bcn_options">
						<h3 class="tab-title" title="<?php _e('The settings for all post types (Posts, Pages, and Custom Post Types) are located under this tab.', 'solustrid-core'); ?>"><?php _e('Post Types', 'solustrid-core'); ?></h3>
						<h3><?php _e('Posts', 'solustrid-core'); ?></h3>
						<table class="form-table adminkit-enset-top">
							<?php
							$this->textbox(__('Post Template', 'solustrid-core'), 'Hpost_post_template', '6', false, __('The template for post breadcrumbs.', 'solustrid-core'));
							$this->textbox(__('Post Template (Unlinked)', 'solustrid-core'), 'Hpost_post_template_no_anchor', '4', false, __('The template for post breadcrumbs, used only when the breadcrumb is not linked.', 'solustrid-core'));
							$this->input_check(__('Post Hierarchy Display', 'solustrid-core'), 'bpost_post_hierarchy_display', __('Show the hierarchy (specified below) leading to a post in the breadcrumb trail.', 'solustrid-core'), false, '', 'adminkit-enset-ctrl adminkit-enset');
							$this->input_check(__('Post Hierarchy Use Parent First', 'solustrid-core'), 'bpost_post_hierarchy_parent_first', __('Use the parent of the post as the primary hierarchy, falling back to the hierarchy selected below when the parent hierarchy is exhausted.', 'solustrid-core'), false, '', 'adminkit-enset');
							$this->input_check(__('Post Hierarchy Referer Influence', 'solustrid-core'), 'bpost_post_taxonomy_referer', __('Allow the referring page to influence the taxonomy selected for the hierarchy.', 'solustrid-core'), false, '', 'adminkit-enset');
							?>
							<tr valign="top">
								<th scope="row">
									<?php _e('Post Hierarchy', 'solustrid-core'); ?>
								</th>
								<td>
									<?php
									$this->input_radio('Spost_post_hierarchy_type', 'category', __('Categories'), false, 'adminkit-enset');
									$this->input_radio('Spost_post_hierarchy_type', 'BCN_DATE', __('Dates', 'solustrid-core'), false, 'adminkit-enset');
									$this->input_radio('Spost_post_hierarchy_type', 'post_tag', __('Tags'), false, 'adminkit-enset');
									// We use the value 'page' but really, this will follow the parent post hierarchy
									$this->input_radio('Spost_post_hierarchy_type', 'BCN_POST_PARENT', __('Post Parent', 'solustrid-core'), false, 'adminkit-enset');
									// Loop through all of the taxonomies in the array
									foreach ($wp_taxonomies as $taxonomy) {
										// Check for non-public taxonomies
										if (!apply_filters('bcn_show_tax_private', $taxonomy->public, $taxonomy->name, 'post')) {
											continue;
										}
										// We only want custom taxonomies
										if (($taxonomy->object_type == 'post' || is_array($taxonomy->object_type) && in_array('post', $taxonomy->object_type)) && !$taxonomy->_builtin) {
											$this->input_radio('Spost_post_hierarchy_type', $taxonomy->name, mb_convert_case($taxonomy->label, MB_CASE_TITLE, 'UTF-8'), false, 'adminkit-enset');
										}
									}
									?>
									<p class="description"><?php esc_html_e('The hierarchy which the breadcrumb trail will show. Note that the "Post Parent" option may require an additional plugin to behave as expected since this is a non-hierarchical post type.', 'solustrid-core'); ?></p>
								</td>
							</tr>
						</table>
						<h3><?php _e('Pages', 'solustrid-core'); ?></h3>
						<table class="form-table">
							<?php
							$this->textbox(__('Page Template', 'solustrid-core'), 'Hpost_page_template', '6', false, __('The template for page breadcrumbs.', 'solustrid-core'));
							$this->textbox(__('Page Template (Unlinked)', 'solustrid-core'), 'Hpost_page_template_no_anchor', '4', false, __('The template for page breadcrumbs, used only when the breadcrumb is not linked.', 'solustrid-core'));
							$this->input_hidden('bpost_page_hierarchy_display');
							$this->input_hidden('bpost_page_hierarchy_parent_first');
							$this->input_hidden('Spost_page_hierarchy_type');
							?>
						</table>
						<h3><?php _e('Attachments', 'solustrid-core'); ?></h3>
						<table class="form-table">
							<?php
							$this->textbox(__('Attachment Template', 'solustrid-core'), 'Hpost_attachment_template', '6', false, __('The template for attachment breadcrumbs.', 'solustrid-core'));
							$this->textbox(__('Attachment Template (Unlinked)', 'solustrid-core'), 'Hpost_attachment_template_no_anchor', '4', false, __('The template for attachment breadcrumbs, used only when the breadcrumb is not linked.', 'solustrid-core'));
							?>
						</table>
						<?php
						// Loop through all of the post types in the array
						foreach ($wp_post_types as $post_type) {
							// Check for non-public CPTs
							if (!apply_filters('bcn_show_cpt_private', $post_type->public, $post_type->name)) {
								continue;
							}
							// We only want custom post types
							if (!$post_type->_builtin) {
								$singular_name_lc = mb_strtolower($post_type->labels->singular_name, 'UTF-8');
						?>
								<h3><?php printf($post_type->labels->singular_name); ?></h3>
								<table class="form-table adminkit-enset-top">
									<?php
									$this->textbox(sprintf(__('%s Template', 'solustrid-core'), $post_type->labels->singular_name), 'Hpost_' . $post_type->name . '_template', '6', false, sprintf(__('The template for %s breadcrumbs.', 'solustrid-core'), $singular_name_lc));
									$this->textbox(sprintf(__('%s Template (Unlinked)', 'solustrid-core'), $post_type->labels->singular_name), 'Hpost_' . $post_type->name . '_template_no_anchor', '4', false, sprintf(__('The template for %s breadcrumbs, used only when the breadcrumb is not linked.', 'solustrid-core'), $singular_name_lc));
									$optid = mtekk_adminKit::get_valid_id('apost_' . $post_type->name . '_root');
									?>
									<tr valign="top">
										<th scope="row">
											<label for="<?php printf($optid); ?>"><?php printf(esc_html__('%s Root Page', 'solustrid-core'), $post_type->labels->singular_name); ?></label>
										</th>
										<td>
											<?php
											wp_dropdown_pages(
												array(
													'name'              => $this->unique_prefix . '_options[apost_' . $post_type->name . '_root]',
													'id'                => $optid,
													'echo'              => 1,
													'show_option_none'  => __('&mdash; Select &mdash;'),
													'option_none_value' => '0',
													'selected'          => $this->opt['apost_' . $post_type->name . '_root'],
												)
											);
											?>
										</td>
									</tr>
									<?php
									$this->input_check(sprintf(__('%s Archive Display', 'solustrid-core'), $post_type->labels->singular_name), 'bpost_' . $post_type->name . '_archive_display', sprintf(__('Show the breadcrumb for the %s post type archives in the breadcrumb trail.', 'solustrid-core'), $singular_name_lc), !$post_type->has_archive);
									$this->input_check(sprintf(__('%s Hierarchy Display', 'solustrid-core'), $post_type->labels->singular_name), 'bpost_' . $post_type->name . '_hierarchy_display', sprintf(__('Show the hierarchy (specified below) leading to a %s in the breadcrumb trail.', 'solustrid-core'), $singular_name_lc), false, '', 'adminkit-enset-ctrl adminkit-enset');
									$this->input_check(sprintf(__('%s Hierarchy Use Parent First', 'solustrid-core'), $post_type->labels->singular_name), 'bpost_' . $post_type->name . '_hierarchy_parent_first', sprintf(__('Use the parent of the %s as the primary hierarchy, falling back to the hierarchy selected below when the parent hierarchy is exhausted.', 'solustrid-core'), $singular_name_lc), false, '', 'adminkit-enset');
									$this->input_check(sprintf(__('%s Hierarchy Referer Influence', 'solustrid-core'), $post_type->labels->singular_name), 'bpost_' . $post_type->name . '_taxonomy_referer', __('Allow the referring page to influence the taxonomy selected for the hierarchy.', 'solustrid-core'), false, '', 'adminkit-enset');
									?>
									<tr valign="top">
										<th scope="row">
											<?php printf(__('%s Hierarchy', 'solustrid-core'), $post_type->labels->singular_name); ?>
										</th>
										<td>
											<?php
											// We use the value 'page' but really, this will follow the parent post hierarchy
											$this->input_radio('Spost_' . $post_type->name . '_hierarchy_type', 'BCN_POST_PARENT', __('Post Parent', 'solustrid-core'), false, 'adminkit-enset');
											$this->input_radio('Spost_' . $post_type->name . '_hierarchy_type', 'BCN_DATE', __('Dates', 'solustrid-core'), false, 'adminkit-enset');
											// Loop through all of the taxonomies in the array
											foreach ($wp_taxonomies as $taxonomy) {
												// Check for non-public taxonomies
												if (!apply_filters('bcn_show_tax_private', $taxonomy->public, $taxonomy->name, $post_type->name)) {
													continue;
												}
												// We only want custom taxonomies
												if ($taxonomy->object_type == $post_type->name || in_array($post_type->name, $taxonomy->object_type)) {
													$this->input_radio('Spost_' . $post_type->name . '_hierarchy_type', $taxonomy->name, $taxonomy->labels->singular_name, false, 'adminkit-enset');
												}
											}
											?>
											<p class="description">
												<?php
												if ($post_type->hierarchical) {
													esc_html_e('The hierarchy which the breadcrumb trail will show.', 'solustrid-core');
												} else {
													esc_html_e('The hierarchy which the breadcrumb trail will show. Note that the "Post Parent" option may require an additional plugin to behave as expected since this is a non-hierarchical post type.', 'solustrid-core');
												}
												?>
											</p>
										</td>
									</tr>
								</table>
						<?php
							}
						}
						do_action($this->unique_prefix . '_after_settings_tab_post', $this->opt);
						?>
					</fieldset>
					<fieldset id="tax" class="bcn_options alttab">
						<h3 class="tab-title" title="<?php _e('The settings for all taxonomies (including Categories, Tags, and custom taxonomies) are located under this tab.', 'solustrid-core'); ?>"><?php _e('Taxonomies', 'solustrid-core'); ?></h3>
						<h3><?php _e('Categories', 'solustrid-core'); ?></h3>
						<table class="form-table">
							<?php
							$this->textbox(__('Category Template', 'solustrid-core'), 'Htax_category_template', '6', false, __('The template for category breadcrumbs.', 'solustrid-core'));
							$this->textbox(__('Category Template (Unlinked)', 'solustrid-core'), 'Htax_category_template_no_anchor', '4', false, __('The template for category breadcrumbs, used only when the breadcrumb is not linked.', 'solustrid-core'));
							?>
						</table>
						<h3><?php _e('Tags', 'solustrid-core'); ?></h3>
						<table class="form-table">
							<?php
							$this->textbox(__('Tag Template', 'solustrid-core'), 'Htax_post_tag_template', '6', false, __('The template for tag breadcrumbs.', 'solustrid-core'));
							$this->textbox(__('Tag Template (Unlinked)', 'solustrid-core'), 'Htax_post_tag_template_no_anchor', '4', false, __('The template for tag breadcrumbs, used only when the breadcrumb is not linked.', 'solustrid-core'));
							?>
						</table>
						<h3><?php _e('Post Formats', 'solustrid-core'); ?></h3>
						<table class="form-table">
							<?php
							$this->textbox(__('Post Format Template', 'solustrid-core'), 'Htax_post_format_template', '6', false, __('The template for post format breadcrumbs.', 'solustrid-core'));
							$this->textbox(__('Post Format Template (Unlinked)', 'solustrid-core'), 'Htax_post_format_template_no_anchor', '4', false, __('The template for post_format breadcrumbs, used only when the breadcrumb is not linked.', 'solustrid-core'));
							?>
						</table>
						<?php
						// Loop through all of the taxonomies in the array
						foreach ($wp_taxonomies as $taxonomy) {
							// Check for non-public taxonomies
							if (!apply_filters('bcn_show_tax_private', $taxonomy->public, $taxonomy->name, null)) {
								continue;
							}
							// We only want custom taxonomies
							if (!$taxonomy->_builtin) {
								$label_lc = mb_strtolower($taxonomy->label, 'UTF-8');
						?>
								<h3><?php echo mb_convert_case($taxonomy->label, MB_CASE_TITLE, 'UTF-8'); ?></h3>
								<table class="form-table">
									<?php
									$this->textbox(sprintf(__('%s Template', 'solustrid-core'), $taxonomy->labels->singular_name), 'Htax_' . $taxonomy->name . '_template', '6', false, sprintf(__('The template for %s breadcrumbs.', 'solustrid-core'), $label_lc));
									$this->textbox(sprintf(__('%s Template (Unlinked)', 'solustrid-core'), $taxonomy->labels->singular_name), 'Htax_' . $taxonomy->name . '_template_no_anchor', '4', false, sprintf(__('The template for %s breadcrumbs, used only when the breadcrumb is not linked.', 'solustrid-core'), $label_lc));
									?>
								</table>
						<?php
							}
						}
						do_action($this->unique_prefix . '_after_settings_tab_taxonomy', $this->opt);
						?>
					</fieldset>
					<fieldset id="miscellaneous" class="bcn_options">
						<h3 class="tab-title" title="<?php _e('The settings for author and date archives, searches, and 404 pages are located under this tab.', 'solustrid-core'); ?>"><?php _e('Miscellaneous', 'solustrid-core'); ?></h3>
						<h3><?php _e('Author Archives', 'solustrid-core'); ?></h3>
						<table class="form-table">
							<?php
							$this->textbox(__('Author Template', 'solustrid-core'), 'Hauthor_template', '6', false, __('The template for author breadcrumbs.', 'solustrid-core'));
							$this->textbox(__('Author Template (Unlinked)', 'solustrid-core'), 'Hauthor_template_no_anchor', '4', false, __('The template for author breadcrumbs, used only when the breadcrumb is not linked.', 'solustrid-core'));
							$this->input_select(__('Author Display Format', 'solustrid-core'), 'Sauthor_name', array('display_name', 'nickname', 'first_name', 'last_name'), false, __('display_name uses the name specified in "Display name publicly as" under the user profile the others correspond to options in the user profile.', 'solustrid-core'));
							$optid = mtekk_adminKit::get_valid_id('aauthor_root');
							?>
							<tr valign="top">
								<th scope="row">
									<label for="<?php printf($optid); ?>"><?php esc_html_e('Author Root Page', 'solustrid-core'); ?></label>
								</th>
								<td>
									<?php
									wp_dropdown_pages(
										array(
											'name'              => $this->unique_prefix . '_options[aauthor_root]',
											'id'                => $optid,
											'echo'              => 1,
											'show_option_none'  => __('&mdash; Select &mdash;'),
											'option_none_value' => '0',
											'selected'          => $this->opt['aauthor_root'],
										)
									);
									?>
								</td>
							</tr>
						</table>
						<h3><?php _e('Miscellaneous', 'solustrid-core'); ?></h3>
						<table class="form-table">
							<?php
							$this->textbox(__('Date Template', 'solustrid-core'), 'Hdate_template', '6', false, __('The template for date breadcrumbs.', 'solustrid-core'));
							$this->textbox(__('Date Template (Unlinked)', 'solustrid-core'), 'Hdate_template_no_anchor', '4', false, __('The template for date breadcrumbs, used only when the breadcrumb is not linked.', 'solustrid-core'));
							$this->textbox(__('Search Template', 'solustrid-core'), 'Hsearch_template', '6', false, __('The anchor template for search breadcrumbs, used only when the search results span several pages.', 'solustrid-core'));
							$this->textbox(__('Search Template (Unlinked)', 'solustrid-core'), 'Hsearch_template_no_anchor', '4', false, __('The anchor template for search breadcrumbs, used only when the search results span several pages and the breadcrumb is not linked.', 'solustrid-core'));
							$this->input_text(__('404 Title', 'solustrid-core'), 'S404_title', 'regular-text');
							$this->textbox(__('404 Template', 'solustrid-core'), 'H404_template', '4', false, __('The template for 404 breadcrumbs.', 'solustrid-core'));
							?>
						</table>
						<h3><?php _e('Deprecated', 'solustrid-core'); ?></h3>
						<table class="form-table">
							<tr valign="top">
								<th scope="row">
									<?php esc_html_e('Title Length', 'solustrid-core'); ?>
								</th>
								<td>
									<label>
										<input name="bcn_options[blimit_title]" type="checkbox" id="blimit_title" value="true" <?php checked(true, $this->opt['blimit_title']); ?> />
										<?php printf(esc_html__('Limit the length of the breadcrumb title. (Deprecated, %1$suse CSS instead%2$s)', 'solustrid-core'), '<a title="' . esc_attr__('Go to the guide on trimming breadcrumb title lengths with CSS', 'solustrid-core') . '" href="https://mtekk.us/archives/guides/trimming-breadcrumb-title-lengths-with-css/">', '</a>'); ?>
									</label><br>
									<ul>
										<li>
											<label for="amax_title_length">
												<?php esc_html_e('Max Title Length: ', 'solustrid-core'); ?>
												<input type="number" name="bcn_options[amax_title_length]" id="amax_title_length" min="1" step="1" value="<?php echo esc_html($this->opt['amax_title_length'], ENT_COMPAT, 'UTF-8'); ?>" class="small-text" />
											</label>
										</li>
									</ul>
								</td>
							</tr>
						</table>
						<?php do_action($this->unique_prefix . '_after_settings_tab_miscellaneous', $this->opt); ?>
					</fieldset>
					<?php do_action($this->unique_prefix . '_after_settings_tabs', $this->opt); ?>
				</div>
				<p class="submit"><input type="submit" class="button-primary" name="bcn_admin_options" value="<?php esc_attr_e('Save Changes'); ?>" /></p>
			</form>
		</div>
<?php
	}
}
