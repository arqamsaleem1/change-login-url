=== Custom Login URL Plugin ===
Contributors: arqamsaleem
Tags: login, custom login, email login, username login
Requires at least: 5.0
Tested up to: 6.2
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

This plugin provides a custom login form that allows users to log in using either their username or email address at a custom URL. The default `wp-login.php` page is completely disabled, forcing users to use your custom login form.

The plugin also handles user authentication and validates login credentials to ensure secure logins. The custom login form is displayed via a shortcode, which you can place anywhere on your website.

== Features ==

- **Custom Login Form**: Display a custom login form at any URL of your choice.
- **Username or Email Login**: Users can log in using either their email address or username.
- **Disables Default Login**: Redirects access to `wp-login.php` to a 404 error, forcing users to use the custom login page.
- **Custom URL**: Logins can be accessed via `/custom-login` (or any custom slug you define).

== Installation ==

1. Download the plugin ZIP file.
2. Go to your WordPress admin area.
3. Navigate to **Plugins > Add New**.
4. Click the **Upload Plugin** button, then choose the ZIP file you downloaded and click **Install Now**.
5. Once installed, click **Activate**.

== Usage ==

1. Once activated, the plugin automatically creates a custom login page at the URL: `/custom-login`.
2. To change the login page slug, go to **Settings > Custom Login** and enter the desired slug. This will update the login page URL (e.g., `http://yourdomain.com/your-new-slug`).

== Frequently Asked Questions ==

= Can I change the custom login URL? =

Yes, you can change the login URL by modifying the `CUSTOM_LOGIN_SLUG` constant in the plugin file. For example, change `define( 'CUSTOM_LOGIN_SLUG', 'custom-login' );` to `define( 'CUSTOM_LOGIN_SLUG', 'my-login-page' );` to use a different URL.

= How does the plugin handle logging in with email or username? =

The plugin checks whether the input in the login form is an email or username. If it’s an email, it checks the user’s credentials using the email address. If it’s a username, it checks using the username.

= Can I add the login form to any page? =

Yes, you can add the custom login form to any page using the `[custom_login_form]` shortcode. Simply create or edit any page, and add the shortcode where you want the form to appear.

= What happens if I try to access wp-login.php? =

Accessing `wp-login.php` will return a 404 error. This is because the plugin disables access to the default WordPress login page, forcing users to use the custom login URL.

== Changelog ==

= 1.0 =
- Added a feature to allow logging in with either email or username.
- Improved the structure of the login form by separating the HTML markup into a separate template file.