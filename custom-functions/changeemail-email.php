<?php
if(!function_exists('email_settings_changeemail_email'))
{
	/*
		[FOR THEME INTEGRATION | PLUGGABLE]
		Sends custom activation E-Mail with out activation code generation(provided by argument)

		@param $code - custom activation URL

		@param $user - user object, who needs to be activated

		@returns BOOLEAN
	*/
	function email_settings_changeemail_email($url,$user)
	{
		$sitename = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		
        return EmailSettings\CustomEmail::generate(array(
        		"title" => sprintf( __( '[%s] Email Change Request' ), $sitename ),
        		"message-option" => "email-settings-change-email-template",
        		"variables" => array("%USERNAME%","%NEW_EMAIL%","%LINK%"),
        		"url" => $url,
        		"user" => $user,
        		"wp_mail_from" => "EmailSettings\Filters::emailchange_filter_from_email",
        		"wp_mail_from_name" => "EmailSettings\Filters::emailchange_filter_from_name",
        		"email_settings_mail_action" => "EmailSettings\Filters::remove_email_change_from_filter",
        		"fallback_message" => __("Hello, here is your email change link:").' '. $url
	        )
	    );
	}
}