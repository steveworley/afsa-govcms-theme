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
  $javascript['misc/jquery.js']['data'] = drupal_get_path('theme', 'afsa_theme') . '/js/lib/jquery.2.1.4.min.js';
  $javascript['misc/jquery.js']['version'] = '2.1.4';
/* --
  $javascript['misc/ui/jquery.ui.core.min.js']['data'] = drupal_get_path('theme', 'afsa_theme') . '/js/lib/jquery-ui.1.11.4.min.js';
  $javascript['misc/ui/jquery.ui.core.min.js']['version'] = '1.11.4';
// */
}

/**
 * Implements hook_preprocess_node()
 */
function afsa_theme_preprocess_node(&$node) {
  $fn = __FUNCTION__ . "__{$node['type']}";

  if (is_callable($fn)) {
    $fn($node);
  }
}

/**
 * Implements hook_preprocess_node().
 *
 * When a creditor meeting node is loaded add javascript that will display the
 * add to calendar links for the node.
 */
function afsa_theme_preprocess_node__creditor_meeting(&$node) {
  $path = drupal_get_path('theme', 'afsa_theme');

  $start_date = strtotime($node['field_event_time'][0]['value']);
  $end_date = strtotime($node['field_event_time'][0]['value2']);

  // Add javascript to define the calendars.
  $node['calendar_event'] = array(
    'atc_title' => "Creditor Meeting: {$node['title']}",
    'atc_date_start' => date('Y-m-d H:i:s', $start_date),
    'atc_date_end' => date('Y-m-d H:i:s', $end_date),
    'atc_location' => $node['field_event_location'][0]['value'],
    'atc_description' => $node['body'],
    'atc_timezone' => drupal_get_user_timezone(),
  );

  drupal_add_js("$path/js/lib/addtocalendar.js");
  drupal_add_css("$path/css/lib/addtocalendar.css");
}
