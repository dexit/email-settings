<?php

namespace EmailSettings;

class Actions
{
	/*
		Adds a bootstrap (3) to wp_editor instance as another style 
	*/
	public static function add_bootstrap_to_wp_editor() 
	{
		if(isset($_GET["page"]) && $_GET["page"] === "email-settings")
		{
			//add_editor_style( plugin_dir_url(__FILE__).'css/bootstrap.min.css' );
		}
	}
}