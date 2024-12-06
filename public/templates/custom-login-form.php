<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php _e('Login page', 'change-login-url'); ?> </title>

    <?php wp_head(); ?>
</head>
<?php
$classes = array("wp-core-ui");
?>

<body class="login no-js <?php echo esc_attr(implode(' ', $classes)); ?>">

    <?php

    $login_header_text = empty($login_header_title) ? __('Powered by WordPress') : "";
    ?>
    <div id="login">
        <h1><a href="<?php echo esc_url($login_header_url); ?>"><?php echo $login_header_text; ?></a></h1>
        <?php

        if (isset($_GET['login_error'])) {
            //echo '<p style="color:red;">Invalid email or password. Please try again.</p>';

            wp_admin_notice(
                'Invalid email or password. Please try again.',
                array(
                    'type'           => 'error',
                    'id'             => 'login_error',
                    'paragraph_wrap' => false,
                )
            );
        }
        ?>
        <form name="loginform" method="post" action="">
            <p>
                <label for="email_or_username"><?php _e('Email or Username', 'change-login-url'); ?></label>
                <input type="text" name="email_or_username" id="email_or_username" required>
            </p>
            <br>
            <div class="user-pass-wrap">
                <label for="password"><?php _e('Password', 'change-login-url'); ?></label>
                <input type="password" name="password" id="password" required>
            </div>
            <br>
            <p class="forgetmenot"><input name="rememberme" type="checkbox" id="rememberme" value="forever"> <label for="rememberme"><?php _e('Remember Me', 'change-login-url'); ?></label></p>

            <p class="submit">
                <input type="submit" name="custom_login" class="button button-primary button-large" value="<?php esc_attr_e('Log In', 'change-login-url'); ?>">
            </p>
        </form>
    </div>

    <?php
    do_action('login_init');
    do_action('login_footer');
    ?>

</body>

</html>