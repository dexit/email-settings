<?php
if(!function_exists('email_settings_resetpassword_email'))
{
	/*
		[FOR THEME INTEGRATION]
		Sends custom reset password E-Mail with out code generation(provided by argument)

		@param $code - custom activation URL

		@param $user - user object, whose password is about to get reset

		@returns BOOLEAN
	*/
	function email_settings_resetpassword_email($url,$user)
	{
        return EmailSettings\CustomEmail::generate(array(
        		"title-option" => "email-settings-reset-password-email-title",
        		"message-option" => "email-settings-reset-password-email-template",
        		"variables" => array("%USERNAME%","%EMAIL%","%LINK%"),
        		"url" => $url,
        		"user" => $user,
        		"wp_mail_from" => "EmailSettings\Filters::filter_reset_password_from_email",
        		"wp_mail_from_name" => "EmailSettings\Filters::filter_reset_password_from_name",
        		"fallback_message" => __("Hello, here is your password reset link:") .' '. $url
	        )
	    );
	}
}