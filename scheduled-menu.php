<?php
/*
Plugin Name: Scheduled Menus
Description: Allows you to set specific dates for menus to be active.
Version: 1.0
Author: Ben Ferdinands
*/

add_action('admin_menu', 'wpsm_add_admin_menu');
add_filter('wp_nav_menu_args', 'wpsm_replace_menu');

function wpsm_add_admin_menu() {
    add_options_page('Scheduled Menus', 'Scheduled Menus', 'manage_options', 'wpsm_settings', 'wpsm_settings_page');
}

function wpsm_settings_page() {
    $all_menus = get_terms('nav_menu', array('hide_empty' => false));
    $menu_locations = get_registered_nav_menus();

    if (isset($_POST['wpsm_submit'])) {
        $scheduled_menus = array();

        if (isset($_POST['menu_id']) && isset($_POST['start_time']) && isset($_POST['end_time'])) {
            foreach ($_POST['menu_id'] as $index => $menu_id) {
                if (isset($_POST['delete'][$index]) && $_POST['delete'][$index] == '1') {
                    continue;
                }
                $scheduled_menus[] = array(
                    'menu_location' => sanitize_text_field($_POST['menu_location'][$index]),
                    'menu_id' => sanitize_text_field($menu_id),
                    'start_time' => sanitize_text_field($_POST['start_time'][$index]),
                    'end_time' => sanitize_text_field($_POST['end_time'][$index])
                );
            }
        }

        for ($i = 0; $i < count($scheduled_menus); $i++) {
            for ($j = $i + 1; $j < count($scheduled_menus); $j++) {
                if (($scheduled_menus[$i]['start_time'] <= $scheduled_menus[$j]['end_time']) &&
                    ($scheduled_menus[$i]['end_time'] >= $scheduled_menus[$j]['start_time']) &&
                    ($scheduled_menus[$i]['menu_location'] == $scheduled_menus[$j]['menu_location'])) {
                    unset($scheduled_menus[$j]);
                    $scheduled_menus = array_values($scheduled_menus);
                    $j--;
                }
            }
        }

        update_option('wpsm_scheduled_menus', $scheduled_menus);
    } else {
        $scheduled_menus = get_option('wpsm_scheduled_menus', array());
    }

    if (empty($scheduled_menus)) {
        $scheduled_menus = array(array('menu_location' => '', 'menu_id' => '', 'start_time' => '', 'end_time' => ''));
    }

    echo '<div class="wrap">';
    echo '<h2>Scheduled Menus</h2>';
    echo '<form method="post" action="">';

    foreach ($scheduled_menus as $index => $menu) {
        echo '<p>';
        echo '<label>Menu Location:</label>';
        echo '<select name="menu_location[]">';
        foreach ($menu_locations as $location_slug => $location_name) {
            $selected = $location_slug == $menu['menu_location'] ? 'selected' : '';
            echo '<option value="' . esc_attr($location_slug) . '" ' . $selected . '>' . esc_html($location_name) . '</option>';
        }
        echo '</select>';
        echo '<label>Menu:</label>';
        echo '<select name="menu_id[]">';
        foreach ($all_menus as $m) {
            $selected = $m->term_id == $menu['menu_id'] ? 'selected' : '';
            echo '<option value="' . esc_attr($m->term_id) . '" ' . $selected . '>' . esc_html($m->name) . '</option>';
        }
        echo '</select>';
        echo '<label>Start Date:</label>';
        echo '<input type="date" name="start_time[]" value="' . esc_attr($menu['start_time']) . '">';
        echo '<label>End Date:</label>';
        echo '<input type="date" name="end_time[]" value="' . esc_attr($menu['end_time']) . '">';
        echo '<input type="hidden" name="delete[' . $index . ']" value="0">';  
        echo '<a href="#" class="delete-menu">Delete</a>';  
        echo '</p>';
    }

    echo '<p><a href="#" id="add-new-menu" class="button">Add New</a></p>';
    echo '<p><input type="submit" name="wpsm_submit" value="Save Changes" class="button button-primary"></p>';
    echo '</form>';
    echo '</div>';

    echo <<<EOT
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("#add-new-menu").click(function(e) {
                e.preventDefault();
                
                var newRow = `<p>
                    <label>Menu Location:</label>
                    <select name="menu_location[]">
    EOT;
    
    foreach ($menu_locations as $location_slug => $location_name) {
        echo '<option value="' . esc_attr($location_slug) . '">' . esc_html($location_name) . '</option>';
    }
    
    echo <<<EOT
                    </select>
                    <label>Menu:</label>
                    <select name="menu_id[]">
    EOT;
    
    foreach ($all_menus as $m) {
        echo '<option value="' . esc_attr($m->term_id) . '">' . esc_html($m->name) . '</option>';
    }
    
    echo <<<EOT
                    </select>
                    <label>Start Date:</label>
                    <input type="date" name="start_time[]">
                    <label>End Date:</label>
                    <input type="date" name="end_time[]">
                    <input type="hidden" name="delete[]" value="0">
                    <a href="#" class="delete-menu">Delete</a>
                </p>`;
                
                $(this).before(newRow);
            });
    
            $('body').on('click', '.delete-menu', function(e) {
                e.preventDefault();
                $(this).prev().val(1);
                $(this).parent().hide();
            });
        });
    </script>
    EOT;
    
}

function wpsm_replace_menu($args) {
    $scheduled_menus = get_option('wpsm_scheduled_menus', array());
    $current_date = current_time('Y-m-d');

    foreach ($scheduled_menus as $menu) {
        if ($current_date >= $menu['start_time'] && $current_date <= $menu['end_time'] && $args['theme_location'] == $menu['menu_location']) {
            $args['menu'] = $menu['menu_id'];
            break;
        }
    }

    return $args;
}
?>
