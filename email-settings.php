<?php
/*
Plugin Name: E-Mail Settings
Description: Configure custom HTML messages for activation, registration, reset password and change E-Mail letters.
Version:     1.0.0
Author:      Dmytro Proskurin
 *
 */
namespace EmailSettings;
/*
	Includes
*/
require_once plugin_dir_path(__FILE__) . '/functions.php';

require_once plugin_dir_path(__FILE__) . '/classes/page.php';

require_once plugin_dir_path(__FILE__) . '/classes/lists.php';

require_once plugin_dir_path(__FILE__) . '/classes/custom-email.php';

require_once plugin_dir_path(__FILE__) . '/pluggable/index.php';

require_once plugin_dir_path(__FILE__) . '/filters.php';

require_once plugin_dir_path(__FILE__) . '/actions.php';

require_once plugin_dir_path(__FILE__) . '/custom-functions/index.php';
/*
	The class that provides us a 'settings' page with an ability to edit/configure E-Mail settings
*/
class Settings
{
	/*
		A variable that will store an array of tabs of settings
	*/
	protected $tabs;
	/*
		A variable that will store array of settings
	*/	
	protected $settings;
	/*
		Constructor
	*/
	public function __construct()
	{
		$this->add_fiters_and_actions();
	}
	protected function add_fiters_and_actions()
	{
		/*
			Add actions
		*/
		add_action("admin_menu",array($this,"add_email_settings_page"));
		/*
			Add filters
		*/
		/* 
			Send E-mails as HTML
		*/
		add_filter('wp_mail_content_type', function( $content_type ) {
		     return 'text/html';
		});
		/*
		 *
		 *
		 * PASSWORD RESET
		 *
		 *
		 */
		/*
			Reset password E-Mail message filter
		*/
		add_filter( 'retrieve_password_title', 'EmailSettings\Filters::filter_reset_password_title', 10, 4 );
		/*
			Reset password E-Mail title/subject filter
		*/
		add_filter( 'retrieve_password_message', 'EmailSettings\Filters::filter_reset_password_message', 10, 4 );
		/*
		 *
		 *
		 * EMAIL CHANGE
		 *
		 *
		 */
		add_filter( 'new_user_email_content', 'EmailSettings\Filters::filter_email_change_message', 10, 2 );
		/*
		 *
		 *
		 * PASSWORD CHANGE NOTIFICATION
		 *
		 *
		 */
		add_filter( 'password_change_email', 'EmailSettings\Filters::filter_password_changed_notify_message', 10, 3 );
		/*
		 *
		 *
		 * EMAIL CHANGE NOTIFICATION
		 *
		 *
		 */
		add_filter( 'email_change_email', 'EmailSettings\Filters::filter_email_changed_notify_message', 10, 3 );
		/*
		 *
		 * 
		 * MISC
		 *
		 *
		 */
		/*
		 	Setup a custom PHPMailer action callback
		 */
		add_action( 'phpmailer_init', function( $phpmailer )
		{
		    $phpmailer->action_function = 'EmailSettings\Functions::email_settings_mail_action';
		} );


	}
	/*
		Adds an item to admin menu and a page to admin dashboard

		@returns VOID
	*/
	public function add_email_settings_page()
	{
		if(!current_user_can("administrator"))
		{
			return;
		}
		add_menu_page( __("E-Mail") .' '.__("Settings"),  __("E-Mail") .' '.__("Settings"), "manage_options", "email-settings-plugin", array($this,"init"),'dashicons-email',30);
	}
	public function init()
	{
		/*
			Declare tabs
		*/
		$this->tabs = Lists::tabs();
		/*
			Assign a default value to settings variable - array()
		*/
		$this->settings = array();
		/*
			Get an array of settings fields for activation email
		*/
		$this->settings = array_merge(Lists::activation_email_settings(), $this->settings);
		/*
			Get an array of settings fields for reset password email
		*/
		$this->settings = array_merge(Lists::reset_password_email_settings(), $this->settings);
		/*
			Get an array of settings fields for email change email
		*/
		$this->settings = array_merge(Lists::email_change_email_settings(), $this->settings);
		/*
			Get an array of settings fields for password change notification email(sent to user)
		*/
		$this->settings = array_merge(Lists::password_change_notify_settings(), $this->settings);
		/*
			Get an array of settings fields for email change notification email(sent to user)
		*/
		$this->settings = array_merge(Lists::email_change_notify_settings(), $this->settings);

		$page = new Page($this->tabs,$this->settings);
		$page->render();
	}
}
new Settings();