<?php

namespace EmailSettings;
/*
	Functions class provides some static public methods that are used in filters / other plugin parts
*/
class Functions
{
	/*
		Applies a filter for activation email, sets "FROM NAME" value

		@param $old - old value (name) to send from

		@param $action - the name of email sending action (i.e activation/etc)

		@returns STRING
	*/
	public static function filter_from_name($old,$action)
	{
		$new = sanitize_text_field(get_option("email-settings-".$action."-email-from-name"));
		if(!is_null($new) && strlen($new) > 0)
		{
			return $new;
		}
		return $old;
	}
	/*
		Applies a filter for activation email, sets "FROM EMAIL" value

		@param $old - old value (name) to send from email

		@param $action - the name of email sending action (i.e activation/etc)

		@returns STRING (EMAIL)
	*/
	public static function filter_from_email($old,$action)
	{
		$new = sanitize_email(get_option("email-settings-".$action."-email-from-email"));
		if(!is_null($new) && strlen($new) > 0)
		{
			return $new;
		}
		return $old;
	}
	/*
	 	Custom PHPMailer action callback

	 	@LINK https://wordpress.stackexchange.com/questions/190928/do-something-after-sending-email
	 */
	public static function email_settings_mail_action( $is_sent, $to, $cc, $bcc, $subject, $body, $from )
	{
	    do_action( 'email_settings_mail_action', $is_sent, $to, $cc, $bcc, $subject, $body, $from );
	}
}