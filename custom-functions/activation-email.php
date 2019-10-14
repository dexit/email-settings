<?php
if(!function_exists('email_settings_activation_email'))
{
	/*
		[FOR THEME INTEGRATION | PLUGGABLE]
		Sends custom activation E-Mail with out activation code generation(provided by argument)

		@param $code - custom activation URL

		@param $user - user object, who needs to be activated

		@returns BOOLEAN
	*/
	function email_settings_activation_email($url,$user)
	{
        return EmailSettings\CustomEmail::generate(array(
        		"title-option" => "email-settings-activation-email-title",
        		"message-option" => "email-settings-activation-email-template",
        		"variables" => array("%USERNAME%","%EMAIL%","%LINK%"),
        		"url" => $url,
        		"user" => $user,
        		"wp_mail_from" => "EmailSettings\Filters::activation_filter_from_email",
        		"wp_mail_from_name" => "EmailSettings\Filters::activation_filter_from_name",
        		"fallback_message" => __("Hello, here is your activation link:").' '. $url
	        )
	    );
	}
}