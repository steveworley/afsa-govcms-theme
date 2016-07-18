<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728096
 */


/**
 * Override or insert variables into the maintenance page template.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("maintenance_page" in this case.)
 */
/* -- Delete this line if you want to use this function
function afsa_theme_preprocess_maintenance_page(&$variables, $hook) {
  // When a variable is manipulated or added in preprocess_html or
  // preprocess_page, that same work is probably needed for the maintenance page
  // as well, so we can just re-use those functions to do that work here.
  afsa_theme_preprocess_html($variables, $hook);
  afsa_theme_preprocess_page($variables, $hook);
}
// */

/**
 * Override or insert variables into the html templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("html" in this case.)
 */
 function afsa_theme_preprocess_html(&$variables, $hook) {
  drupal_add_library('system', 'ui.accordion');
}
/* -- Delete this line if you want to use this function
function afsa_theme_preprocess_html(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');

  // The body tag's classes are controlled by the $classes_array variable. To
  // remove a class from $classes_array, use array_diff().
  //$variables['classes_array'] = array_diff($variables['classes_array'], array('class-to-remove'));
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
/* -- Delete this line if you want to use this function
function afsa_theme_preprocess_page(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the node templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
/* -- Delete this line if you want to use this function
function afsa_theme_preprocess_node(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');

  // Optionally, run node-type-specific preprocess functions, like
  // afsa_theme_preprocess_node_page() or afsa_theme_preprocess_node_story().
  $function = __FUNCTION__ . '_' . $variables['node']->type;
  if (function_exists($function)) {
    $function($variables, $hook);
  }
}
// */

/**
 * Override or insert variables into the comment templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function afsa_theme_preprocess_comment(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the region templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("region" in this case.)
 */
/* -- Delete this line if you want to use this function
function afsa_theme_preprocess_region(&$variables, $hook) {
  // Don't use Zen's region--sidebar.tpl.php template for sidebars.
  //if (strpos($variables['region'], 'sidebar_') === 0) {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('region__sidebar'));
  //}
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
function afsa_theme_preprocess_search_result(&$vars) {
  $vars['info'] = $vars['info_split']['date'];
}
/* -- Delete this line if you want to use this function
function afsa_theme_preprocess_block(&$variables, $hook) {
  // Add a count to all the blocks in the region.
  // $variables['classes_array'][] = 'count-' . $variables['block_id'];

  // By default, Zen will use the block--no-wrapper.tpl.php for the main
  // content. This optional bit of code undoes that:
  //if ($variables['block_html_id'] == 'block-system-main') {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('block__no_wrapper'));
  //}
}
// */

/**
 * Implements hook_js_alter().
 */
function afsa_theme_js_alter(&$javascript) {
  $javascript['misc/jquery.js']['data'] = drupal_get_path('theme', 'afsa_theme') . '/js/lib/jquery.1.8.3.min.js';
  $javascript['misc/jquery.js']['version'] = '1.8.3';
  
  // $javascript['misc/ui/jquery.ui.core.min.js']['data'] = drupal_get_path('theme', 'afsa_theme') . '/js/lib/jquery-ui.1.11.4.min.js';
  // $javascript['misc/ui/jquery.ui.core.min.js']['version'] = '1.11.4';
}

/**
 * Implements hook_preprocess_node()
 */
function afsa_theme_preprocess_node(&$vars) {
  $fn = __FUNCTION__ . "__{$vars['type']}";

  if (is_callable($fn)) {
    $fn($vars);
  }
}

/**
 * Implements hook_preprocess_node().
 *
 * When a creditor meeting node is loaded add javascript that will display the
 * add to calendar links for the node.
 *
 * @see http://addtocalendar.com
 */
function afsa_theme_preprocess_node__creditor_meeting(&$vars) {
  $path = drupal_get_path('theme', 'afsa_theme');
  $node = $vars['node'];

  $field_name = 'field_event_time';
  $items = field_get_items('node', $node, $field_name);
  $field = field_info_field($field_name);
  $instance = field_info_instance('node', $field_name, $node->type);
  $display = field_get_display($instance, 'default', $node);

  // Make sure that we have dates available before continuing.
  if (empty($items)) {
    return;
  }

  $date = date_formatter_process('date_default', 'node', $node, $field, $instance, $node->language, $items[0], $display);

  // Add javascript to define the calendars.
  $vars['calendar_event'] = array(
    'atc_title' => "Creditor Meeting: {$vars['title']}",
    // @see http://php.net/manual/en/function.date.php for date formats.
    'atc_date_start' => $date['value']['db']['object']->format('Y-m-d H:i:s'),
    'atc_date_end' => $date['value2']['db']['object']->format('Y-m-d H:i:s'),
    'atc_location' => $vars['field_event_location'][0]['value'],
    'atc_description' => !empty($vars['body']) ? $vars['body'] : "Creditor meeting for {$vars['title']}",
    'atc_timezone' => drupal_get_user_timezone(),
  );

  drupal_add_js("$path/js/lib/addtocalendar.js");
  drupal_add_css("$path/css/lib/addtocalendar.css");
}
