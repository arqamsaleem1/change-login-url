<?php
/*
Plugin Name: Change Login URL
Description: Adds a new Login form with ability to customize the url slug. Disables the default login URL for added security.
Version: 1.0
Author: Arqam Saleem
Text Domain: change-login-url
*/


// Exit if accessed directly
if (! defined('ABSPATH')) exit;

// Default login slug
define('DEFAULT_CUSTOM_LOGIN_SLUG', 'my-secret-login');

// Add settings page to the WordPress admin
function custom_login_add_settings_page()
{
    add_options_page(
        'Custom Login Settings', // Page title
        'Custom Login',          // Menu title
        'manage_options',        // Capability required
        'custom-login-settings', // Menu slug
        'custom_login_settings_page' // Callback function to display the settings page
    );
}
add_action('admin_menu', 'custom_login_add_settings_page');

// Create the settings page form
function custom_login_settings_page()
{
?>
    <div class="wrap">
        <h1><?php _e('Custom Login Settings', 'change-login-url'); ?></h1>
        <form method="post" action="options.php">
            <?php
            // Output security fields for the registered setting "custom_login_slug"
            settings_fields('custom_login_options_group');
            // Output setting sections and their fields
            do_settings_sections('custom-login-settings');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Login Page Slug', 'change-login-url'); ?></th>
                    <td><input type="text" name="custom_login_slug" value="<?php echo esc_attr(get_option('custom_login_slug', DEFAULT_CUSTOM_LOGIN_SLUG)); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

// Register the setting to store the custom login page slug
function custom_login_register_settings()
{
    register_setting('custom_login_options_group', 'custom_login_slug');
    custom_login_flush_rewrites();
}
add_action('admin_init', 'custom_login_register_settings');

// Update the login slug constant based on the settings
function custom_login_get_slug()
{
    return get_option('custom_login_slug', DEFAULT_CUSTOM_LOGIN_SLUG);
}

// Update the custom login rewrite rule dynamically based on the updated slug
function custom_login_update_rewrite_rule()
{
    $slug = custom_login_get_slug();
    add_rewrite_rule('^' . $slug . '/?$', 'index.php?custom_login=1', 'top');
}
add_action('init', 'custom_login_update_rewrite_rule', 10, 0);

// Register the custom query variable
function custom_login_query_vars($vars)
{
    $vars[] = 'custom_login';
    return $vars;
}
add_filter('query_vars', 'custom_login_query_vars');

function enque_plugin_scripts()
{
    wp_enqueue_script('utils');
    wp_enqueue_script('user-profile');
    wp_enqueue_style('login');
}
add_action('login_init', 'enque_plugin_scripts');

// Template redirect function to display the login form
function custom_login_template_redirect()
{
    if (get_query_var('custom_login')) {
        // Output the custom login form
        echo do_shortcode('[custom_login_form]');
        exit;
    }
}
add_action('template_redirect', 'custom_login_template_redirect');

// Shortcode to display the custom login form
function custom_login_form()
{
    ob_start();
    $template_path = plugin_dir_path(__FILE__) . 'public/templates/custom-login-form.php';

    if (file_exists($template_path)) {
        include $template_path;
    } else {
        echo '<p>' . _e('Login form template not found.', 'change-login-url') . '</p>';
    }

    return ob_get_clean();
}
add_shortcode('custom_login_form', 'custom_login_form');

// Handle the login form submission and authentication
function custom_login_handler()
{
    if (isset($_POST['custom_login'])) {
        $email_or_username = sanitize_text_field($_POST['email_or_username']);
        $password = sanitize_text_field($_POST['password']);

        // Check if input is an email or username
        if (is_email($email_or_username)) {
            // If email, get user by email
            $user = get_user_by('email', $email_or_username);
        } else {
            // If not email, assume it's a username and get user by username
            $user = get_user_by('login', $email_or_username);
        }

        // Validate the password and log the user in
        if ($user && wp_check_password($password, $user->user_pass, $user->ID)) {
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);
            wp_redirect(home_url()); // Redirect to the home page or any desired page
            exit;
        } else {
            // Redirect back to login page with an error if authentication fails
            wp_redirect(site_url('/' . custom_login_get_slug() . '/?login_error=1'));
            exit;
        }
    }
}
add_action('init', 'custom_login_handler');

// Disable access to wp-login.php and return a 404 error
function disable_wp_login()
{
    global $pagenow;
    if ($pagenow === 'wp-login.php' && ! isset($_GET['action'])) {
        status_header(404);
        nocache_headers();
        include(get_404_template());
        exit;
    }
}
add_action('init', 'disable_wp_login');

// Flush rewrite rules on plugin activation and deactivation
function custom_login_flush_rewrites()
{
    custom_login_update_rewrite_rule();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'custom_login_flush_rewrites');

// Flush rewrite rules on plugin deactivation
function custom_login_remove_rewrites()
{
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'custom_login_remove_rewrites');
