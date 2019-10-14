<?php

namespace EmailSettings;

class Filters
{
	/*
	
		#REGION: ACTIVATION

	*/
	/*
		Filters Sender Name for activation e-mail letter

		@returns STRING
	*/
	public static function activation_filter_from_name($old)
	{
		return Functions::filter_from_name($old,"activation");
	}
	/*
		Filters Sender Email for activation e-mail letter

		@returns STRING
	*/
	public static function activation_filter_from_email($old)
	{
		return Functions::filter_from_email($old,"activation");
	}
	/*

		#ENDREGION: ACTIVATION

	*/
	/*
	
		#REGION: RESET PASSWORD

	*/
	/*
		Filters Reset Password E-Mail subject/title

		@param $old - default/old/previously set title

		@returns STRING
	*/
	public static function filter_reset_password_title($old) 
	{
		/*
            Get custom title/subject
        */
        $title = esc_html(get_option("email-settings-reset-password-email-title"));
        /*
			Return custom title/subject
        */
        if(strlen($title) > 0)
        {
        	return $title;
        }
        /*
			Fallback: return default title/subject
        */
	    return $old;
	}
	/*
		Filters Reset Password E-Mail letter

		@param $message - default message

		@param $key - a password reset key

		@param $user_login - a username/login of user

		@param $user_data - an object that holds user data whose pasword is being reset

		@returns STRING
	*/
	public static function filter_reset_password_message( $message, $key, $user_login, $user_data ) 
	{
	    /*
            Get custom message
        */
        $custom_message = base64_decode(get_option("email-settings-reset-password-email-template"));
        /*
            Replace variables in text with data
        */
        $url = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login');
        $search_arr = array("%USERNAME%","%EMAIL%","%LINK%");
        $replace_arr = array($user_login,$user_data->user_email,"<a href='".$url."'>".$url."</a>");
        $custom_message = str_replace($search_arr,$replace_arr,$custom_message);

        /*
			Add filters for custom sender name & email
        */
		add_filter( 'wp_mail_from', 'EmailSettings\Filters::filter_reset_password_from_email' );
		add_filter( 'wp_mail_from_name', 'EmailSettings\Filters::filter_reset_password_from_name' );
        /*
			Add a function to remove the filters for sender name & email
        */
		add_action( 'email_settings_mail_action', 'EmailSettings\Filters::remove_reset_password_from_filter' );
        /*
			Return filtered message
        */
        if(strlen($custom_message) > 0)
        {
        	return $custom_message;
        }
        /*
			Fallback: return default message
        */
	    return $message;
	}
	/*
		Filters Reset Password sender E-Mail

		@param $old - the old sender email

		@returns STRING
	*/
	public static function filter_reset_password_from_email( $old ) 
	{
		return Functions::filter_from_email($old,"reset-password");
	}
	/*
		Filters Reset Password sender Name

		@param $old - the old sender name

		@returns STRING
	*/
	public static function filter_reset_password_from_name( $old ) 
	{
		return Functions::filter_from_name($old,"reset-password");
	}
	/*
		Removes previously added filters for setting custom sender name & e-mail
	*/
	public static function remove_reset_password_from_filter()
	{
		remove_filter( 'wp_mail_from', 'EmailSettings\Filters::filter_reset_password_from_email' );
		remove_filter( 'wp_mail_from_name', 'EmailSettings\Filters::filter_reset_password_from_name' );
	}
	/*
	
		#ENDREGION: RESET PASSWORD

	*/
	/*

		#REGION: EMAIL CHANGE REQUEST

	*/
	/*
		Filters Sender Name for e-mail change letter

		@returns STRING
	*/
	public static function emailchange_filter_from_name($old)
	{
		return Functions::filter_from_name($old,"change");
	}
	/*
		Filters Sender Email for e-mail change letter

		@returns STRING
	*/
	public static function emailchange_filter_from_email($old)
	{
		return Functions::filter_from_email($old,"change");
	}
	public static function filter_email_change_message( $message, $new_user_email )
	{
		/*
            Get custom message
        */
        $custom_message = base64_decode(get_option("email-settings-change-email-template"));
        /*
            Replace variables in text with data
        */
        $search_arr = array("%USERNAME%","%NEW_EMAIL%","%LINK%");
        $replace_arr = array("###USERNAME###","###EMAIL###","###ADMIN_URL###");
        $custom_message = str_replace($search_arr,$replace_arr,$custom_message);

        /*
			Apply Filters for sender name & E-mail address
        */
		add_filter( 'wp_mail_from', 'EmailSettings\Filters::emailchange_filter_from_email' );
		add_filter( 'wp_mail_from_name', 'EmailSettings\Filters::emailchange_filter_from_name' );
		add_action( 'email_settings_mail_action', 'EmailSettings\Filters::remove_email_change_from_filter' );
        /*
			Return filtered message
        */
        if(strlen($custom_message) > 0)
        {
        	return $custom_message;
        }
        /*
			Fallback: return default message
        */
		return $message;
	}
	/*
		Removes previously added filters for setting custom sender name & e-mail
	*/
	public static function remove_email_change_from_filter()
	{
		remove_filter( 'wp_mail_from', 'EmailSettings\Filters::emailchange_filter_from_email' );
		remove_filter( 'wp_mail_from_name', 'EmailSettings\Filters::emailchange_filter_from_name' );
	}
	/*

		#ENDREGION: EMAIL CHANGE REQUEST

	*/
	/*

		#REGION: PASSWORD CHANGE NOTIFICATION

	*/
	/*
		Filters Sender Name for password change notification letter

		@returns STRING
	*/
	public static function password_change_nofification_filter_from_name($old)
	{
		return Functions::filter_from_name($old,"password-change-notify");
	}
	/*
		Filters Sender Email for password change notification letter

		@returns STRING
	*/
	public static function password_change_nofification_filter_from_email($old)
	{
		return Functions::filter_from_email($old,"password-change-notify");
	}
	public static function filter_password_changed_notify_message( array $pass_change_email, $user, $userdata )
	{
		/*
			Get custom title
		*/
		$title = esc_html(get_option("email-settings-password-change-notify-title"));
		/*
            Get custom message
        */
        $custom_message = base64_decode(get_option("email-settings-password-change-notify-template"));
        /*
            Replace variables in text with data
        */
        $search_arr = array("%USERNAME%","%EMAIL%","%ADMIN_EMAIL%");
        $replace_arr = array("###USERNAME###","###EMAIL###","###ADMIN_EMAIL###");
        $custom_message = str_replace($search_arr,$replace_arr,$custom_message);

        /*
			Apply Filters for sender name & E-mail address
        */
		add_filter( 'wp_mail_from', 'EmailSettings\Filters::password_change_nofification_filter_from_email' );
		add_filter( 'wp_mail_from_name', 'EmailSettings\Filters::password_change_nofification_filter_from_name' );
		add_action( 'email_settings_mail_action', 'EmailSettings\Filters::remove_password_change_nofification_from_filter' );
        /*
			Check the lengthes of new values
        */
        if(strlen($custom_message) > 0)
        {
        	$pass_change_email["message"] = $custom_message;
        }
        /*
			Set custom title / subject
        */
        if(strlen($title))
        {
        	$pass_change_email["subject"] = $title;
        }

        return $pass_change_email;
	}
	/*
		Removes previously added filters for setting custom sender name & e-mail
	*/
	public static function remove_password_change_nofification_from_filter()
	{
		remove_filter( 'wp_mail_from', 'EmailSettings\Filters::password_change_nofification_filter_from_email' );
		remove_filter( 'wp_mail_from_name', 'EmailSettings\Filters::password_change_nofification_filter_from_name' );
	}
	/*

		#ENDREGION: PASSWORD CHANGE NOTIFICATION

	*/
	/*

		#REGION: EMAIL CHANGE NOTIFICATION

	*/
	/*
		Filters Sender Name for email change notification letter

		@returns STRING
	*/
	public static function email_change_nofification_filter_from_name($old)
	{
		return Functions::filter_from_name($old,"email-change-notify");
	}
	/*
		Filters Sender Email for password change notification letter

		@returns STRING
	*/
	public static function email_change_nofification_filter_from_email($old)
	{
		return Functions::filter_from_email($old,"email-change-notify");
	}
	public static function filter_email_changed_notify_message( array $email_change_email, $user, $userdata )
	{
		/*
			Get custom title
		*/
		$title = esc_html(get_option("email-settings-email-change-notify-title"));
		/*
            Get custom message
        */
        $custom_message = base64_decode(get_option("email-settings-email-change-notify-template"));
        /*
            Replace variables in text with data
        */
        $search_arr = array("%USERNAME%","%OLD_EMAIL%","%NEW_EMAIL%","%ADMIN_EMAIL%",);
        $replace_arr = array("###USERNAME###","###EMAIL###","###NEW_EMAIL###","###ADMIN_EMAIL###");
        $custom_message = str_replace($search_arr,$replace_arr,$custom_message);

        /*
			Apply Filters for sender name & E-mail address
        */
		add_filter( 'wp_mail_from', 'EmailSettings\Filters::email_change_nofification_filter_from_email' );
		add_filter( 'wp_mail_from_name', 'EmailSettings\Filters::email_change_nofification_filter_from_name' );
		add_action( 'email_settings_mail_action', 'EmailSettings\Filters::remove_email_change_nofification_from_filter' );
        /*
			Check the lengthes of new values
        */
        if(strlen($custom_message) > 0)
        {
        	$email_change_email["message"] = $custom_message;
        }
        /*
			Set custom title / subject
        */
        if(strlen($title))
        {
        	$email_change_email["subject"] = $title;
        }

        return $email_change_email;
	}
	/*
		Removes previously added filters for setting custom sender name & e-mail
	*/
	public static function remove_email_change_nofification_from_filter()
	{
		remove_filter( 'wp_mail_from', 'EmailSettings\Filters::email_change_nofification_filter_from_email' );
		remove_filter( 'wp_mail_from_name', 'EmailSettings\Filters::email_change_nofification_filter_from_name' );
	}
	/*

		#ENDREGION: EMAIL CHANGE NOTIFICATION

	*/

}