<?php
namespace EmailSettings;

class Lists
{
	/*
		Provides an array of tabs to render the settings fields in

		@param title - the title of tab

		@param id - an unique identifier to add settings fields to

		@returns ARRAY
	*/
	public static function tabs()
	{
		return array(
			array(
				"title" => __("E-Mail activation"),
				"id" => "activation-email-settings"
			),
			array(
				"title" => __("Reset password request"),
				"id" => "reset-password-email-settings"
			),
			array(
				"title" => __("E-Mail change request"),
				"id" => "change-email-settings"
			),
			array(
				"title" => __("Passsord changed notify"),
				"id" => "password-change-notify-settings"
			),
			array(
				"title" => __("E-Mail changed notify"),
				"id" => "email-change-notify-settings"
			),
		);
	}
	/*
		Provides an array of settings field objects/arrays with all neded data for Activation E-Mail letter

		@param name - the unique name/id of field

		@param container - the name of container/tab to place setting to

		@param type - the type of input field(i.e text/wp_editor/radio/checkboxarrarray/select)

		@param label - the text label displayed above the input

		@param additional_text - the text diplayed between input label and input itself

		@returns ARRAY
	*/
	public static function activation_email_settings()
	{
		return array(
			array(
				"name" => "email-settings-activation-email-title",
				"container" => "activation-email-settings",
				"type" => "text",
				"label" => __("Title"), 
			),
			array(
				"name" => "email-settings-activation-email-template",
				"container" => "activation-email-settings",
				"type" => "wp_editor",
				"label" => __("Text"),
				"additional_text" => __("Variables") . ': %USERNAME%,%EMAIL%,%LINK%' 
			),
			array(
				"name" => "email-settings-activation-email-from-email",
				"container" => "activation-email-settings",
				"type" => "text",
				"label" => __("From") .' '. "E-Mail", 
			),
			array(
				"name" => "email-settings-activation-email-from-name",
				"container" => "activation-email-settings",
				"type" => "text",
				"label" => __("From"). ' '.__("Name"), 
			),
		);
	}
	/*
		Provides an array of settings field objects/arrays with all neded data for Reset Password E-Mail letter

		@param name - the unique name/id of field

		@param container - the name of container/tab to place setting to

		@param type - the type of input field(i.e text/wp_editor/radio/checkboxarrarray/select)

		@param label - the text label displayed above the input

		@param additional_text - the text diplayed between input label and input itself

		@returns ARRAY
	*/
	public static function reset_password_email_settings()
	{
		return array(
			array(
				"name" => "email-settings-reset-password-email-title",
				"container" => "reset-password-email-settings",
				"type" => "text",
				"label" => __("Title"), 
			),
			array(
				"name" => "email-settings-reset-password-email-template",
				"container" => "reset-password-email-settings",
				"type" => "wp_editor",
				"label" => __("Text"),
				"additional_text" => __("Variables") . ': %USERNAME%,%EMAIL%,%LINK%' 
			),
			array(
				"name" => "email-settings-reset-password-email-from-email",
				"container" => "reset-password-email-settings",
				"type" => "text",
				"label" => __("From") .' '. "E-Mail", 
			),
			array(
				"name" => "email-settings-reset-password-email-from-name",
				"container" => "reset-password-email-settings",
				"type" => "text",
				"label" => __("From"). ' '.__("Name"), 
			),
		);
	}
	/*
		Provides an array of settings field objects/arrays with all neded data for E-Mail change E-Mail letter

		@param name - the unique name/id of field

		@param container - the name of container/tab to place setting to

		@param type - the type of input field(i.e text/wp_editor/radio/checkboxarrarray/select)

		@param label - the text label displayed above the input

		@param additional_text - the text diplayed between input label and input itself

		@returns ARRAY
	*/
	public static function email_change_email_settings()
	{
		return array(
			array(
				"name" => "email-settings-change-email-template",
				"container" => "change-email-settings",
				"type" => "wp_editor",
				"label" => __("Text"),
				"additional_text" => __("Variables") . ': %USERNAME%,%NEW_EMAIL%,%LINK%' 
			),
			array(
				"name" => "email-settings-change-email-from-email",
				"container" => "change-email-settings",
				"type" => "text",
				"label" => __("From") .' '. "E-Mail", 
			),
			array(
				"name" => "email-settings-change-email-from-name",
				"container" => "change-email-settings",
				"type" => "text",
				"label" => __("From"). ' '.__("Name"), 
			),
		);
	}
	/*
		Provides an array of settings field objects/arrays with all neded data for password change notification E-Mail letter

		@param name - the unique name/id of field

		@param container - the name of container/tab to place setting to

		@param type - the type of input field(i.e text/wp_editor/radio/checkboxarrarray/select)

		@param label - the text label displayed above the input

		@param additional_text - the text diplayed between input label and input itself

		@returns ARRAY
	*/
	public static function password_change_notify_settings()
	{
		return array(
			array(
				"name" => "email-settings-password-change-notify-title",
				"container" => "password-change-notify-settings",
				"type" => "text",
				"label" => __("Title"), 
			),
			array(
				"name" => "email-settings-password-change-notify-template",
				"container" => "password-change-notify-settings",
				"type" => "wp_editor",
				"label" => __("Text"),
				"additional_text" => __("Variables") . ': %USERNAME%,%EMAIL%,%ADMIN_EMAIL%' 
			),
			array(
				"name" => "email-settings-password-change-notify-email-from-email",
				"container" => "password-change-notify-settings",
				"type" => "text",
				"label" => __("From") .' '. "E-Mail", 
			),
			array(
				"name" => "email-settings-password-change-notify-email-from-name",
				"container" => "password-change-notify-settings",
				"type" => "text",
				"label" => __("From"). ' '.__("Name"), 
			),
		);
	}
	/*
		Provides an array of settings field objects/arrays with all neded data for Email change notification E-Mail letter

		@param name - the unique name/id of field

		@param container - the name of container/tab to place setting to

		@param type - the type of input field(i.e text/wp_editor/radio/checkboxarrarray/select)

		@param label - the text label displayed above the input

		@param additional_text - the text diplayed between input label and input itself

		@returns ARRAY
	*/
	public static function email_change_notify_settings()
	{
		return array(
			array(
				"name" => "email-settings-email-change-notify-title",
				"container" => "email-change-notify-settings",
				"type" => "text",
				"label" => __("Title"), 
			),
			array(
				"name" => "email-settings-email-change-notify-template",
				"container" => "email-change-notify-settings",
				"type" => "wp_editor",
				"label" => __("Text"),
				"additional_text" => __("Variables") . ': %USERNAME%,%OLD_EMAIL%,%NEW_EMAIL%,%ADMIN_EMAIL%' 
			),
			array(
				"name" => "email-settings-email-change-notify-email-from-email",
				"container" => "email-change-notify-settings",
				"type" => "text",
				"label" => __("From") .' '. "E-Mail", 
			),
			array(
				"name" => "email-settings-email-change-notify-email-from-name",
				"container" => "email-change-notify-settings",
				"type" => "text",
				"label" => __("From"). ' '.__("Name"), 
			),
		);
	}
}