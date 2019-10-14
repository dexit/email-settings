# Email Settings
The plugin that allows to customize the activation,reset password, email change, email change notification, password change notification E-Mail templates.

# Main Features
- Customizable E-Mail letter title/subject
- Customizable E-Mail letter message
- Customizable E-Mail sender name(i.e sent from)
- Customizable E-Mail sender e-mail(i.e sent from)

# Theme Integration
`email_settings_activation_email($url,$user)` - a function to send custom activation E-Mail<br>
<b>$url</b> - the actual activation URL to send<br>
<b>$user</b> - the WP_User object of user who is about to be activated<br>

An example of usage:<br>
```
if(function_exists("email_settings_activation_email"))
{
	$result = email_settings_activation_email($activation_link,$user);
}
else
{
    // fallback, send your default E-Mail using wp_mail
}
```
`email_settings_changeemail_email($url,$user)` - a function to send custom email change E-Mail<br>
<b>$url</b> - the custom e-mail change URL to send<br>
<b>$user</b> - the WP_User object of user whose e-mail should be changed<br>

An example of usage:<br>
```
if(function_exists("email_settings_changeemail_email"))
{
	$result = email_settings_changeemail_email($url,$user);
}
else
{
    // fallback, send your default E-Mail using wp_mail
}
```
`email_settings_resetpassword_email($url,$user)` - a function to send custom password reset E-Mail<br>
<b>$url</b> - the custom password reset URL to send<br>
<b>$user</b> - the WP_User object of user whose password should be changed<br>

An example of usage:<br>
```
if(function_exists("email_settings_resetpassword_email"))
{
	$result = email_settings_resetpassword_email($url,$user);
}
else
{
    // fallback, send your default E-Mail using wp_mail
}
```

# Screenshot
<img src="https://i.imgur.com/sLSajWm.png" alt="screenshot"/>

