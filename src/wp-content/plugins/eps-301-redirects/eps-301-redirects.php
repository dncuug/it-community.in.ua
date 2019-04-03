<?php
 /*
Plugin Name: 301 Redirects
Description: Easily create and manage 301 redirects.
Version: 2.40
Author: WebFactory Ltd
Author URI: https://www.webfactoryltd.com/
Text Domain: eps-301-redirects

  Copyright 2015 - 2019  Web factory Ltd  (email: support@webfactoryltd.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// include only file
if (!defined('ABSPATH')) {
  wp_die(__('Do not open this file directly.', 'eps-301-redirect'));
}

if (!defined('EPS_REDIRECT_PRO')) {

  define('EPS_REDIRECT_PATH',       plugin_dir_path(__FILE__));
  define('EPS_REDIRECT_URL',        plugins_url() . '/eps-301-redirects/');
  define('EPS_REDIRECT_VERSION',    '2.3.5');
  define('EPS_REDIRECT_PRO',        false);

  include(EPS_REDIRECT_PATH . 'eps-form-elements.php');
  include(EPS_REDIRECT_PATH . 'class.drop-down-pages.php');
  include(EPS_REDIRECT_PATH . 'libs/eps-plugin-options.php');
  include(EPS_REDIRECT_PATH . 'plugin.php');

  register_activation_hook(__FILE__, array('EPS_Redirects_Plugin', '_activation'));
  register_deactivation_hook(__FILE__, array('EPS_Redirects_Plugin', '_deactivation'));

  class EPS_Redirects
  {

    /**
     *
     * Constructor
     *
     * Add some actions.
     *
     */
    public function __construct()
    {
      global $EPS_Redirects_Plugin;

      if (is_admin()) {

        if (isset($_GET['page']) && $_GET['page'] == $EPS_Redirects_Plugin->config('page_slug')) {
          // actions
          add_action('activated_plugin',      array($this, 'activation_error'));
          add_action('admin_footer_text',     array($this, 'set_ajax_url'));

          // Other
          add_action('admin_init', array($this, 'clear_cache'));
        }


        // Ajax funcs
        add_action('wp_ajax_eps_redirect_get_new_entry',            array($this, 'ajax_get_entry'));
        add_action('wp_ajax_eps_redirect_delete_entry',             array($this, 'ajax_eps_delete_entry'));
        add_action('wp_ajax_eps_redirect_get_inline_edit_entry',    array($this, 'ajax_get_inline_edit_entry'));
        add_action('wp_ajax_eps_redirect_save',                     array($this, 'ajax_save_redirect'));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'plugin_action_links'));
      } else {
        add_action('init', array($this, 'do_redirect'), 1); // Priority 1 for redirects.
        add_action('template_redirect', array($this, 'check_404'), 1); // Priority 1 for redirects.
      }
    }

    function plugin_action_links($links)
    {
      $settings_link = '<a href="' . admin_url('options-general.php?page=eps_redirects') . '" title="' . __('Configure Redirects', 'eps-301-redirects') . '">' . __('Configure Redirects', 'eps-301-redirects') . '</a>';

      array_unshift($links, $settings_link);

      return $links;
    } // plugin_action_links


    /**
     *
     * DO_REDIRECT
     *
     * This function will redirect the user if it can resolve that this url request has a redirect.
     *
     * @author epstudios
     *
     */
    public function do_redirect()
    {
      if (is_admin()) return false;
      $redirects = self::get_redirects(true); // True for only active redirects.

      if (empty($redirects)) return false; // No redirects.

      // Get current url
      $url_request = self::get_url();

      $query_string = explode('?', $url_request);
      $query_string = (isset($query_string[1])) ? $query_string[1] : false;


      foreach ($redirects as $redirect) {
        $from = urldecode($redirect->url_from);

        if ($redirect->status != 'inactive' && rtrim(trim($url_request), '/')  === self::format_from_url(trim($from))) {

          // Match, this needs to be redirected
          // increment this hit counter.
          self::increment_field($redirect->id, 'count');

          if ($redirect->status == '301') {
            header('HTTP/1.1 301 Moved Permanently');
          } elseif ($redirect->status == '302') {
            header('HTTP/1.1 302 Moved Temporarily');
          }

          $to = ($redirect->type == "url" && !is_numeric($redirect->url_to)) ? urldecode($redirect->url_to) : get_permalink($redirect->url_to);
          $to = ($query_string) ? $to . "?" . $query_string : $to;

          header('Location: ' . $to, true, (int)$redirect->status);
          exit();
        }
      }
    }

    /**
     *
     * FORMAT FROM URL
     *
     * Will construct and format the from url from what we have in storage.
     *
     * @return url string
     * @author epstudios
     *
     */
    private function format_from_url($string)
    {
      //$from = home_url() . '/' . $string;
      //return strtolower( rtrim( $from,'/') );


      $complete = home_url() . '/' . $string;
      list($uprotocol, $uempty, $uhost, $from) = explode('/', $complete, 4);
      $from = '/' . $from;
      return strtolower(rtrim($from, '/'));
    }

    /**
     *
     * GET_URL
     *
     * This function returns the current url.
     *
     * @return URL string
     * @author epstudios
     *
     */
    public static function get_url()
    {
      return strtolower(urldecode($_SERVER['REQUEST_URI']));
      //$protocol = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) ? 'https' : 'http';
      //return strtolower( urldecode( $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ) );
    }







    /**
     *
     * PARSE SERIAL ARRAY
     *
     * A necessary data parser to change the POST arrays into save-able data.
     *
     * @return array of redirects
     * @author epstudios
     *
     */
    public static function _parse_serial_array($array)
    {
      $new_redirects = array();
      $total = count($array['url_from']);

      for ($i = 0; $i < $total; $i++) {

        if (empty($array['url_to'][$i]) || empty($array['url_from'][$i])) continue;
        $new_redirects[] = array(
          'id'        => isset($array['id'][$i]) ? $array['id'][$i] : null,
          'url_from'  => $array['url_from'][$i],
          'url_to'    => $array['url_to'][$i],
          'type'      => (is_numeric($array['url_to'][$i])) ? 'post' : 'url',
          'status'    => isset($array['status'][$i]) ? $array['status'][$i] : '301'
        );
      }
      return $new_redirects;
    }

    /**
     *
     * AJAX SAVE REDIRECTS
     *
     * Saves a single redirectvia ajax.
     *
     * TODO: Maybe refactor this to reduce the number of queries.
     *
     * @return nothing
     * @author epstudios
     */
    public function ajax_save_redirect()
    {

      $update = array(
        'id'        => ($_POST['id']) ? $_POST['id'] : false,
        'url_from'  => $_POST['url_from'], // remove the $root from the url if supplied, and a leading /
        'url_to'    => $_POST['url_to'],
        'type'      => (is_numeric($_POST['url_to']) ? 'post' : 'url'),
        'status'    => $_POST['status']
      );

      $ids = self::_save_redirects(array($update));

      $updated_id = $ids[0]; // we expect only one returned id.

      // now get the new entry...
      $redirect = self::get_redirect($updated_id);
      $html = '';

      ob_start();
      $dfrom = urldecode($redirect->url_from);
      $dto   = urldecode($redirect->url_to);
      include(EPS_REDIRECT_PATH . 'templates/template.redirect-entry.php');
      $html = ob_get_contents();
      ob_end_clean();
      echo json_encode(array(
        'html'          => $html,
        'redirect_id'   => $updated_id
      ));

      exit();
    }

    /**
     *
     * redirect_exists
     *
     * Checks if a redirect exists for a given url_from
     *
     * @param $redirect
     * @return bool
     */
    public static function redirect_exists($redirect)
    {
      global $wpdb;
      $table_name = $wpdb->prefix . "redirects";
      $query = "SELECT id FROM $table_name WHERE url_from = '" . $redirect['url_from'] . "'";
      $result = $wpdb->get_row($query);
      return ($result) ? $result : false;
    }

    /**
     *
     * SAVE REDIRECTS
     *
     * Saves the array of redirects.
     *
     * TODO: Maybe refactor this to reduce the number of queries.
     *
     * @return nothing
     * @author epstudios
     */
    public static function _save_redirects($array)
    {
      if (empty($array)) return false;
      global $wpdb;
      $table_name = $wpdb->prefix . "redirects";
      $root = get_bloginfo('url') . '/';
      $ids = array();


      foreach ($array as $redirect) {

        if (!isset($redirect['id']) || empty($redirect['id'])) {

          // If the user supplied a post_id, is it valid? If so, use it!
          if ($post_id = url_to_postid($redirect['url_to'])) {
            $redirect['url_to'] = $post_id;
          }

          // new
          $entry = array(
            'url_from'      => trim(ltrim(str_replace($root, null, $redirect['url_from']), '/')),
            'url_to'        => trim($redirect['url_to']),
            'type'          => trim($redirect['type']),
            'status'        => trim($redirect['status'])
          );
          // Add count if exists:
          if (isset($redirect['count']) && is_numeric($redirect['count'])) $entry['count'] = $redirect['count'];

          $wpdb->insert(
            $table_name,
            $entry
          );
          $ids[] = $wpdb->insert_id;
        } else {
          // existing
          $entry = array(
            'url_from'  => trim(ltrim(str_replace($root, null, $redirect['url_from']), '/')),
            'url_to'    => trim($redirect['url_to']),
            'type'      => trim($redirect['type']),
            'status'    => trim($redirect['status'])
          );
          // Add count if exists:
          if (isset($redirect['count']) && is_numeric($redirect['count'])) $entry['count'] = $redirect['count'];

          $wpdb->update(
            $table_name,
            $entry,
            array('id' => $redirect['id'])
          );

          $ids[] = $redirect['id'];
        }
      }
      return $ids; // return array of affected ids.
    }
    /**
     *
     * GET REDIRECTS
     *
     * Gets the redirects. Can be switched to return Active Only redirects.
     *
     * @return array of redirects
     * @author epstudios
     *
     */
    public static function get_redirects($active_only = false)
    {
      global $wpdb;
      $table_name = $wpdb->prefix . "redirects";
      $orderby = (isset($_GET['orderby']))  ?  esc_sql($_GET['orderby']) : 'id';
      $order = (isset($_GET['order']))    ? esc_sql($_GET['order']) : 'desc';
      $orderby = (in_array(strtolower($orderby), array('id', 'url_from', 'url_to', 'count'))) ? $orderby : 'id';
      $order = (in_array(strtolower($order), array('asc', 'desc'))) ? $order : 'desc';

      $query = "SELECT *
            FROM $table_name
            WHERE status != 404 " . (($active_only) ? "AND status != 'inactive'" : null) . "
            ORDER BY $orderby $order";
      //die($query);
      $results = $wpdb->get_results($query);

      return $results;
    }

    public static function get_all()
    {
      global $wpdb;
      $table_name = $wpdb->prefix . "redirects";

      $results = $wpdb->get_results(
        "SELECT * FROM $table_name ORDER BY id DESC"
      );

      return $results;
    }

    public static function get_redirect($redirect_id)
    {
      global $wpdb;
      $table_name = $wpdb->prefix . "redirects";
      $results = $wpdb->get_results(
        "SELECT * FROM $table_name WHERE id = " . intval($redirect_id) . " LIMIT 1"
      );
      return array_shift($results);
    }

    /**
     *
     * INCREMENT FIELD
     *
     * Add +1 to the specified field for a given id
     *
     * @return the result
     * @author epstudios
     *
     */
    public static function increment_field($id, $field)
    {
      global $wpdb;
      $table_name = $wpdb->prefix . "redirects";
      $results = $wpdb->query("UPDATE $table_name SET $field = $field + 1 WHERE id = $id");
      return $results;
    }

    /**
     *
     * DO_INPUTS
     *
     * This function will list out all the current entries.
     *
     * @return html string
     * @author epstudios
     *
     */
    public static function list_redirects()
    {
      $redirects = self::get_redirects();
      $html = '';
      if (empty($redirects)) return false;
      ob_start();
      foreach ($redirects as $redirect) {
        $dfrom = urldecode($redirect->url_from);
        $dto   = urldecode($redirect->url_to);
        include(EPS_REDIRECT_PATH . 'templates/template.redirect-entry.php');
      }
      $html = ob_get_contents();
      ob_end_clean();
      return $html;
    }

    /**
     *
     * DELETE_ENTRY
     *
     * This function will remove an entry.
     *
     * @return nothing
     * @author epstudios
     *
     */
    public static function ajax_eps_delete_entry()
    {
      if (!isset($_POST['id'])) exit();

      global $wpdb;
      $table_name = $wpdb->prefix . "redirects";
      $results = $wpdb->delete($table_name, array('ID' => intval($_POST['id'])));
      echo json_encode(array('id' => $_POST['id']));
      exit();
    }
    private static function _delete($id)
    {
      global $wpdb;
      $table_name = $wpdb->prefix . "redirects";
      $wpdb->delete($table_name, array('ID' => intval($id)));
    }

    /**
     *
     * GET_ENTRY
     * AJAX_GET_ENTRY
     * GET_EDIT_ENTRY
     *
     * This function will return a blank row ready for user input.
     *
     * @return html string
     * @author epstudios
     *
     */
    public static function get_entry($redirect_id = false)
    {
      ob_start();
      ?>
<tr class="id-<?php echo ($redirect_id) ? $redirect_id : 'new'; ?>">
  <?php include(EPS_REDIRECT_PATH . 'templates/template.redirect-entry-edit.php'); ?>
</tr>
<?php
$html = ob_get_contents();
ob_end_clean();
return $html;
}

public static function get_inline_edit_entry($redirect_id = false)
{
  include(EPS_REDIRECT_PATH . 'templates/template.redirect-entry-edit-inline.php');
}


public static function ajax_get_inline_edit_entry()
{
  $redirect_id = isset($_REQUEST['redirect_id']) ? intval($_REQUEST['redirect_id']) : false;

  ob_start();
  self::get_inline_edit_entry($redirect_id);
  $html = ob_get_contents();
  ob_end_clean();
  echo json_encode(array(
    'html' => $html,
    'redirect_id' => $redirect_id
  ));
  exit();
}


public static function ajax_get_entry()
{
  echo self::get_entry();
  exit();
}

public function clear_cache()
{
  header("Cache-Control: no-cache, must-revalidate");
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Content-Type: application/xml; charset=utf-8");
}



/**
     *
     * SET_AJAX_URL
     *
     * This function will output a variable containing the admin ajax url for use in javascript.
     *
     * @author epstudios
     *
     */
public static function set_ajax_url()
{
  echo '<script>var eps_redirect_ajax_url = "' . admin_url('admin-ajax.php') . '"</script>';
}



public function activation_error()
{
  file_put_contents(EPS_REDIRECT_PATH . '/error_activation.html', ob_get_contents());
}


public static function check_404()
{ }
}



/**
 * Outputs an object or array in a readable form.
 *
 * @return void
 * @param $string = the object to prettify; Typically a string.
 * @author epstudios
 */
if (!function_exists('eps_prettify')) {
  function eps_prettify($string)
  {
    return ucwords(str_replace("_", " ", $string));
  }
}

if (!function_exists('eps_view')) {
  function eps_view($object)
  {
    echo '<pre>';
    print_r($object);
    echo '</pre>';
  }
}


// Run the plugin.
$EPS_Redirects = new EPS_Redirects();
} else {
  if (EPS_REDIRECT_PRO === true) {
    add_action('admin_notices', 'eps_redirects_pro_conflict');
    function eps_redirects_pro_conflict()
    {
      printf(
        '<div class="%s"><p>%s</p></div>',
        "error",
        "ERROR: Please de-activate the non-Pro version of EPS 301 Redirects First!"
      );
    }
  }
}
