<?php

namespace EmailSettings;
/*
	The class that renders E-Mail settings page
*/
class Page
{
	/*
		@param $tabs - an array of tabs to be rendered

		@param $settings - an array of settings to be rendered
	*/
	public function __construct($tabs,$settings)
	{
		$this->settings = $settings;
		$this->tabs = $tabs;
	}
	/*
		Saves the options as a text
	*/
	protected function save()
	{
		if(!current_user_can("administrator"))
		{
			return;
		}
		if(isset($_REQUEST))
		{
			if(isset($_REQUEST["save_nonce"]))
			{
				if(wp_verify_nonce($_REQUEST["save_nonce"],__FILE__))
				{
					/*
						Loop through all settings and check if they're present in $_REQUEST array, if so, update them
					*/
					foreach($this->settings as $field)
					{
						$name = $field["name"];
						if(isset($_REQUEST[$name]))
						{
							if(is_array($_REQUEST[$name]))
							{
								$_REQUEST[$name] = json_encode($_REQUEST[$name]);
							}
							$setting = sanitize_text_field($_REQUEST[$name]);
							update_option($name,$setting);
						}
					}
					?>
					<br>
					<div class="notice notice-success is-dismissible">
				        <p> <strong><?php echo __("Success")."!";?></strong>
		                <?php echo __("Settings") . " ".__("saved"). ".";?>
		            	</p>
				    </div>
					<?php
				}
			}
		}
	}
	public function render()
	{
		/*
			Only administrator can manage plugin settings
		*/
		if(!current_user_can("administrator"))
		{
			return;
		}
		/*
			Save Settings
		*/
		$this->save();
		/*
			Prepare names for JS
		*/
		$names = array();
		foreach($this->settings as $field)
		{
			if($field["type"] == "wp_editor")
			{
				array_push($names, $field["name"]);
			}
		}
		/*
			Enqueue scripts / styles
		*/
		$plugin_url = plugins_url() . '/' . basename( plugin_dir_path( (dirname( __FILE__ ) ) ));
		
		wp_enqueue_style("email-settings/admin-bootstrap",$plugin_url.'/css/bootstrap.min.css');

		wp_enqueue_style("email-settings/settings-css",$plugin_url.'/css/settings-page.css');

		wp_enqueue_script("email-settings/settings-js",$plugin_url.'/js/settings-page.js');

		wp_localize_script( "email-settings/settings-js", 'php_vars', 
	        array(
	          'names' => $names 
	        )
	    );
		/*
			Render HTML
		*/
		?>
		<div class="wrap-overlay" style="opacity: 0;">
			<img src="<?php echo $plugin_url . '/img/load.gif'?>" alt="Load"/>
		</div>
		<div class="wrap" style="min-height: 80vh;opacity: 0;position: relative;">
			<form method="post">
			<input type="hidden" name="save_nonce" value="<?php echo wp_create_nonce(__FILE__);?>"/>
			<div class="nav-tabs-container">
				<span class="arrows dashicons dashicons-arrow-left"></span>
				<span class="arrows dashicons dashicons-arrow-right"></span>
				<ul class="nav nav-tabs">
					<?php
					/*
						Render buttons for all tabs
					*/
					foreach($this->tabs as $container)
					{
						?>
						<li><a class="bootstrap-tab-link" href="javascript:void(0)" onclick="open_tab(this)" id="<?php echo $container["id"];?>"><?php echo $container["title"];?></a></li>
						<?php
						
					}
					?>
				</ul>
			</div>
			<?php
				/*
					Render all settings fields
				*/
				foreach($this->settings as $field)
				{
					?>
					<div class="container-tab-contents" opens-by="<?php echo $field['container'];?>">
						<?php
						/*
							Get raw current value
						*/
						$current_val = get_option($field["name"]);
						/*
							Define default properties/settings for wp_editor
						*/
						$settings = array( 'textarea_name' => $field["name"].'-editor', 'editor_height' => '350');
						/*
							Render simple title for setting if it's set
						*/
						if(isset($field["title"]))
						{
							?>
								<h3><?php echo $field["title"];?></h3>
							<?php
						}
						/*
							Render simple text input (input type text)
						*/
			    		if($field["type"] == "text")
			    		{
			    			?>
			    			<label for="<?php echo $field["name"];?>">
			    					<span><?php echo $field["label"];?></span>
			    				</label>
			    				<input type="text" style="width: 100%" id="<?php echo $field["name"];?>" name="<?php echo $field["name"];?>" value="<?php echo $current_val; ?>" />
			    			<?php
			    		}
			    		/*
							Render simple radio input group
			    		*/
			    		if($field["type"] == "radio")
			    		{
			    			?>
			    				<input type="radio" id="<?php echo $field["name"].'-'.$field['value'];?>" name="<?php echo $field["name"];?>" value="<?php echo $field['value']; ?>" <?php if($current_val == $field['value']) echo "checked";?> />
			    				<label class="radio-label" for="<?php echo $field["name"].'-'.$field['value'];?>">
			    					<span><?php echo $field["label"];?></span>
			    				</label>
			    			<?php
			    		}
			    		/*
							Render simple checkbox array input (i.e multiple checkboxes)
			    		*/
			    		if($field["type"] == "checkboxarray")
			    		{
			    			/*
								Get an array of items that are checked and retrieve current value
			    			*/
			    			$test = json_decode($current_val);
			    			if(!is_null($test) && is_array($test))
			    			{
			    				if(in_array($field["value"], $test))
			    				{
			    					$current_val = $field["value"];
			    				}
			    			}
			    			?>
			    				<input type="checkbox" id="<?php echo $field["name"].'-'.$field['value'];?>" name="<?php echo $field["name"];?>[]" value="<?php echo $field['value']; ?>" <?php if($current_val == $field['value']) echo "checked";?> />
			    				<label class="radio-label" for="<?php echo $field["name"].'-'.$field['value'];?>">
			    					<span><?php echo $field["label"];?></span>
			    				</label>
			    			<?php
			    		}
			    		/*
							Render simple select input with values passed in setting options
			    		*/
			    		if($field["type"] == "select")
			    		{
			    			?>
			    			<label for="<?php echo $field["name"];?>">
			    					<span><?php echo $field["label"];?></span>
			    				</label>
							<?php
								$current_value = get_option($field["name"]);
							?>
							<select style="width: 100%" name="<?php echo $field["name"];?>" id="<?php echo $field["name"];?>">
								<?php
									foreach($field["values"] as $value)
									{
										?>
											<option value="<?php echo $value['shortname'];?>" <?php echo ($current_value == $value['shortname']) ? 'selected' : '';?>><?php echo $value['name'] .' ['.$value['shortname'].']';?></option>
										<?php
									}
								?>
							</select>
			    			<?php
			    		}
			    		/*
							Render wp_editor instance, with a hidden input to store base64
			    		*/
			    		if($field["type"] == "wp_editor")
						{
							?>
							<label for="<?php echo $field["name"];?>">
			    				<span><?php echo $field["label"];?></span>
			    			</label>
							<?php
							if(isset($field["additional_text"]))
							{
								?>
									<p><?php echo $field["additional_text"];?></p>
								<?php
							}
			    			wp_editor( base64_decode($current_val), $field["name"].'-editor', $settings );
			    			?>
			    			<input type="hidden" name="<?php echo $field["name"];?>" id="<?php echo $field["name"];?>" value="<?php echo $current_val; ?>">
			    			<?php
			    		}

			    		?>
		    		</div>
		    		<?php
				}
				?>
				<br>
				<button class="button save-btn" type="submit"><?php _e("Save");?></button>
			</form>
		</div>
		<?php
	}
}