<?php
/*
Plugin Name: Mark New Posts
Description: Highlight and count unread WordPress posts.
Version: 5.5.12
Author: TS Soft
Author URI: http://www.ts-soft.ru/
Text Domain: mark-new-posts
License: MIT

Copyright 2015 TS Soft LLC (email: dev@ts-soft.ru )

Permission is hereby granted, free of charge, to any person obtaining a 
copy of this software and associated documentation files (the 
"Software"), to deal in the Software without restriction, including 
without limitation the rights to use, copy, modify, merge, publish, 
distribute, sublicense, and/or sell copies of the Software, and to 
permit persons to whom the Software is furnished to do so, subject to 
the following conditions: 

The above copyright notice and this permission notice shall be included 
in all copies or substantial portions of the Software. 

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS 
OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY 
CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, 
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE 
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

require_once('includes/class.options.php');

class MarkNewPosts {
	const PLUGIN_NAME = 'Mark New Posts';
	const COOKIE_EXP_DAYS = 30;
	const COOKIE_ID = 'mark_new_posts_';
	const COOKIE_POST_IDS_DELIMITER = ',';
	const OPTION_NAME = 'mark_new_posts';
	const TEXT_DOMAIN = 'mark-new-posts';
	const WP_FILTER_DEFAULT_PRIORITY = 10;

	private $cookie_name_time;
	private $cookie_name_posts;
	private $mark_posts_as_read_done = false;
	private $options;

	function MarkNewPosts() {
		$this->set_cookie_names();
		$this->load_options();

		add_action('init', array(&$this, 'init'));
		add_action('pre_get_posts', array(&$this, 'mark_posts_as_read'));
		add_action('wp_enqueue_scripts', array(&$this, 'enqueue_styles'));
		$this->set_conditional_filters();

		add_action('admin_init', array(&$this, 'admin_init'));
		add_action('admin_menu', array(&$this, 'admin_menu'));
		add_filter('plugin_action_links', array(&$this, 'add_action_links'), self::WP_FILTER_DEFAULT_PRIORITY, 2);
	}

	private function set_cookie_names() {
		$cookie_name_prefix = self::COOKIE_ID . substr(md5(get_bloginfo('name')), 0, 8);
		$this->cookie_name_time = $cookie_name_prefix . '_time';
		$this->cookie_name_posts = $cookie_name_prefix . '_posts';
	}

	private function load_options() {
		$options = get_option(self::OPTION_NAME);
		if (!$options || !is_object($options) || !is_a($options, 'MarkNewPosts_Options')) {
			$options = new MarkNewPosts_Options();
			$options->marker_placement = MarkNewPosts_MarkerPlacement::TITLE;
			$options->marker_type = MarkNewPosts_MarkerType::CIRCLE;
			$options->set_read_after_opening = false;
		}
		$this->options = $options;
	}

	public function init() {
		load_plugin_textdomain(self::TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages/');
		$this->set_current_time_cookie();
	}

	private function set_current_time_cookie() {
		if ($this->is_special_page()) {
			return;
		}
		$cookie_name_time = $this->cookie_name_time;
		$cookie = isset($_COOKIE[$cookie_name_time]) ? $_COOKIE[$cookie_name_time] : null;
		if ($cookie) {
			define('KBNP_COOKIE_LAST', $cookie);
		} else {
			$current_timestamp = $this->get_current_timestamp();
			$this->set_cookie($cookie_name_time, $current_timestamp);
			$cookie_name_posts = $this->cookie_name_posts;
			$this->set_cookie($cookie_name_posts, null);
			unset($_COOKIE[$cookie_name_posts]);
		}
	}

	private function is_special_page() {
		return is_admin() || is_404();
	}

	private function get_current_timestamp() {
		$current_time = current_time('timestamp');
		$h = gmdate('H', $current_time);
		$i = gmdate('i', $current_time);
		$s = gmdate('s', $current_time);
		$m = gmdate('m', $current_time);
		$d = gmdate('d', $current_time);
		$y = gmdate('Y', $current_time);
		return gmdate('U', mktime($h, $i, $s, $m, $d, $y));
		// $current_timestamp = gmdate( 'U', $current_time );
		// you'd think this would work, but it doesn't. *sigh*. Instead we use this
		// ugly workaround to make sure the time is the same as the post's time would be if you posted right now
	}

	private function set_cookie($cookie_name, $value) {
		$seconds_in_day = 86400;
		$exp_time = time() + self::COOKIE_EXP_DAYS * $seconds_in_day;
		setcookie($cookie_name, $value, $exp_time, COOKIEPATH, COOKIE_DOMAIN);
	}

	public function mark_posts_as_read($query) {
		if (!defined('KBNP_COOKIE_LAST')) {
			return;
		}
		if ($this->mark_posts_as_read_done || $this->is_special_page() || !$query->is_main_query()) {
			return;
		}
		$this->mark_posts_as_read_done = true;
		$read_posts_ids = $this->get_read_posts_ids();
		$update_cookie = false;
		foreach ($query->get_posts() as $post) {
			$post_id = $post->ID;
			if ($this->mark_post_as_read($post) && !in_array($post_id, $read_posts_ids)) {
				$read_posts_ids[] = $post_id;
				$update_cookie = true;
			}
		}
		if ($update_cookie) {
			$this->set_read_posts_ids($read_posts_ids);
		}
	}

	private function mark_post_as_read($post) {
		$result = false;
		$is_after_cookie_time = $this->is_after_cookie_time($post);
		if ($is_after_cookie_time) {
			$result = true;
			if ($this->options->set_read_after_opening) {
				$result = is_single() || !$this->is_post_with_excerpt($post);
			}
		}
		return $result;
	}

	private function is_post_with_excerpt($post) {
		return preg_match('/<!--more(.*?)?-->/', $post->post_content );
	}

	private function get_read_posts_ids() {
		$cookie_name = $this->cookie_name_posts;
		$cookie = isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : null;
		return $cookie
			? explode(self::COOKIE_POST_IDS_DELIMITER, $cookie)
			: array();
	}

	private function set_read_posts_ids(&$read_posts_ids) {
		$cookie = join(self::COOKIE_POST_IDS_DELIMITER, $read_posts_ids);
		$this->set_cookie($this->cookie_name_posts, $cookie);
	}

	private function is_after_cookie_time($post) {
		return defined('KBNP_COOKIE_LAST') && get_post_time('U', false, $post) >= KBNP_COOKIE_LAST;
	}

	public function enqueue_styles() {
		wp_enqueue_style('mark_new_posts_style', plugins_url('css/style.css', __FILE__));
	}

	private function set_conditional_filters() {
		if ($this->options->marker_placement === MarkNewPosts_MarkerPlacement::TITLE) {
			add_filter('the_title', array(&$this, 'mark_title'), self::WP_FILTER_DEFAULT_PRIORITY, 2);
		}
	}

	public function mark_title($title, $post_id) {
		if (in_the_loop() && $this->is_new_post($post_id)) {
			$option_value = $this->options->marker_type;
			$title_prefix = '';
			if ($option_value === MarkNewPosts_MarkerType::CIRCLE) {
				$title_prefix = '<span class="mark-new-posts-circle"></span>';
			} else if ($option_value === MarkNewPosts_MarkerType::TEXT) {
				$title_prefix = '<span class="mark-new-posts-text">New</span>';
			} else if ($option_value === MarkNewPosts_MarkerType::IMAGE_DEFAULT) {
				$title_prefix = '<img src="' . plugins_url('images/label-new-blue.png', __FILE__) . '"
					width="48" height="48" class="mark-new-posts-image-default"/>';
			}
			$title = $title_prefix . $title;
		}
		return $title;
	}

	public function admin_init() {
		add_action('wp_ajax_mark_new_posts_save_options', array(&$this, 'save_options'));
		wp_register_style('mark_new_posts_admin_style', plugins_url('css/admin.css', __FILE__));
		wp_register_script('mark_new_posts_admin_script', plugins_url('js/admin.js', __FILE__));
	}

	public function admin_menu() {
		$page = add_options_page(self::PLUGIN_NAME, self::PLUGIN_NAME, 'administrator', basename(__FILE__), array(&$this, 'display_options_page'));
		add_action('admin_print_styles-' . $page, array(&$this, 'admin_styles'));
		add_action('admin_print_scripts-' . $page, array(&$this, 'admin_scripts'));
	}

	public function admin_styles() {
		wp_enqueue_style('mark_new_posts_admin_style');
	}

	public function admin_scripts() {
		wp_enqueue_script('mark_new_posts_admin_script');
	}

	public function display_options_page() {
		if (isset($_POST['submit'])) {
			$this->submit_admin_form();
		}
		require('includes/options-page.php');
	}

	public function save_options() {
		$string_true = 'true';
		$options = new MarkNewPosts_Options();
		$options->marker_placement = intval($_POST['markerPlacement']);
		$options->marker_type = intval($_POST['markerType']);
		$options->set_read_after_opening = $_POST['setReadAfterOpening'] === $string_true;
		update_option(self::OPTION_NAME, $options);
		$result = array(
			'success' => true,
			'message' => __('Settings saved', self::TEXT_DOMAIN)
		);
		header('Content-Type: application/json');
		echo json_encode($result);
		wp_die();
	}

	private function echo_option($option, $value, $label) {
		$selected = $option === $value;
		$selected_attribute = $selected ? ' selected' : '';
		echo '<option value="' . $value . '"' . $selected_attribute . '>' . $label . '</option>';
	}

	public function add_action_links($all_links, $current_file) {
		$current_file = basename($current_file);
		if (basename(__FILE__) == $current_file) {
			$link_text = __('Settings', self::TEXT_DOMAIN);
			$link = '<a href="' . admin_url('options-general.php?page=' . $current_file) . '">' . $link_text . '</a>';
			array_unshift($all_links, $link);
		}
		return $all_links;
	}

	public function is_new_post($post) {
		return $this->is_after_cookie_time($post) && !$this->is_post_cookie_set($post);
	}

	private function is_post_cookie_set($post) {
		$post_id = $this->get_post_id($post);
		$read_posts_ids = $this->get_read_posts_ids();
		return in_array($post_id, $read_posts_ids);
	}

	private function get_post_id($post) {
		$post_id = false;
		if (empty($post)) {
			$post_id = get_the_ID();
		} else {
			if (is_int($post)) {
				$post_id = $post;
			} else if (is_a($post, 'WP_Post')) {
				$post_id = $post->ID;
			}
		}
		return $post_id;
	}

	public function new_posts_count($query) {
		$count = 0;
		if (defined('KBNP_COOKIE_LAST')) {
			$query = new WP_Query($query);
			$query->set('nopaging', true);
			foreach ($query->get_posts() as $post_id) {
				if ($this->is_new_post($post_id)) {
					$count++;
				}
			}
			wp_reset_postdata();
		}
		return $count;
	}
}

$mark_new_posts = new MarkNewPosts();

// $post (integer/object) (optional) post ID or object
function mnp_is_new_post($post = null) {
	global $mark_new_posts;
	return $mark_new_posts->is_new_post($post);
}

// $query (string/array) WP_Query query string
function mnp_new_posts_count($query = null) {
	global $mark_new_posts;
	return $mark_new_posts->new_posts_count($query);
}
?>