<?php
/*
    Override default pluggable function that sends activation E-Mail
*/
if( !function_exists( 'wp_new_user_notification' ) )
{
    function wp_new_user_notification( $user_id, $deprecated = null, $notify = '' ) {
        if ( $deprecated !== null ) {
            _deprecated_argument( __FUNCTION__, '4.3.1' );
        }

        global $wpdb, $wp_hasher;
        $user = get_userdata( $user_id );

        // The blogname option is escaped with esc_html on the way into the database in sanitize_option
        // we want to reverse this for the plain text arena of emails.
        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

        if ( 'user' !== $notify ) {
            $switched_locale = switch_to_locale( get_locale() );
            $message  = sprintf( __( 'New user registration on your site %s:' ), $blogname ) . "\r\n\r\n";
            $message .= sprintf( __( 'Username: %s' ), $user->user_login ) . "\r\n\r\n";
            $message .= sprintf( __( 'Email: %s' ), $user->user_email ) . "\r\n";

            @wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] New User Registration' ), $blogname ), $message );

            if ( $switched_locale ) {
                restore_previous_locale();
            }
        }

        // `$deprecated was pre-4.3 `$plaintext_pass`. An empty `$plaintext_pass` didn't sent a user notification.
        if ( 'admin' === $notify || ( empty( $deprecated ) && empty( $notify ) ) ) {
            return;
        }

        // Generate something random for a password reset key.
        $key = wp_generate_password( 20, false );

        /** This action is documented in wp-login.php */
        do_action( 'retrieve_password_key', $user->user_login, $key );

        // Now insert the key, hashed, into the DB.
        if ( empty( $wp_hasher ) ) {
            require_once ABSPATH . WPINC . '/class-phpass.php';
            $wp_hasher = new PasswordHash( 8, true );
        }
        $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
        $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );

        $switched_locale = switch_to_locale( get_user_locale( $user ) );

        /*
            Get & generate custom activation E-Mail
        */
        /*
            Get custom message
        */
        $message = base64_decode(get_option("email-settings-activation-email-template"));
        /*
            Get custom title
        */
        $title = esc_html(get_option("email-settings-activation-email-title"));
        /*
            Replace variables in text with data
        */
        $url = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login');
        $search_arr = array("%USERNAME%","%EMAIL%","%LINK%");
        $replace_arr = array($user->user_login,$user->user_email,"<a href='".$url."'>".$url."</a>");
        $message = str_replace($search_arr,$replace_arr,$message);

        /*
            Fallback
        */
        if(strlen($message) == 0)
        {
            $message = "Hello, here is your activation link: " . "<a href='".$url."'>".$url."</a>";
        }
        /*
           Apply filters
        */
        add_filter('wp_mail_from_name', 'EmailSettings\Filters::activation_filter_from_name');
        add_filter('wp_mail_from', 'EmailSettings\Filters::activation_filter_from_email');
        /*
            Send E-Mail
        */
        wp_mail($user->user_email, $title, $message);
        /*
            Remove filters
        */
        remove_filter('wp_mail_from_name', 'EmailSettings\Filters::activation_filter_from_name');
        remove_filter('wp_mail_from', 'EmailSettings\Filters::activation_filter_from_email');

        if ( $switched_locale ) {
            restore_previous_locale();
        }
    }

}