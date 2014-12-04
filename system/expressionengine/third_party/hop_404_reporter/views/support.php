<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<h3>What does Hop 404 Reporter ?</h3>
<p>
	Hop 404 Reporter will help you track down 404 errors (a user tried to display a page that doesn't exist) on your website.
</p>

<h3>Set it up</h3>
<p>
	Hop 404 Reporter requires its tag <strong>{exp:hop_404_reporter:process_url}</strong> to be placed into your 404 page template in order to track each 404 errors occurring.
</p>

<h3>404 URLs</h3>
<p>
	<strong>Referrer URL :</strong> The referrer URL can have 3 different values.
	<ul>
		<li>&bull; A normal URL : This means the user followed the 404 URL from this referrer URL. If you go on this page, you may found the incorrect link.</li>
		<li>&bull; Referrer not specified : This means there is no referrer. In most of the cases, this occurs when the user entered a URL manually.</li>
		<li>&bull; Referrer not tracked : If you disabled the referrer tracking in the settings, this will be displayed.</li>
	</ul>
</p>

<h3>Email notifications</h3>
<p>
	<strong>URL to match :</strong> This field is parsed as a regular expression so you can create advanced filters. But it's also very easy to use. If you want a notification when a 404 URL contains "wrong_url", just enter "wrong_url" in this field. It's as simple as that. If you leave it empty, the notification will be send each time a 404 occurs, whatever the URL is.
</p>
<p>
	<strong>Interval :</strong> You can choose if the pair 404 URL + its referrer URL will be reported once, or each time it occurs. If a 404 occurs with a different referrer, it will be reported too.
</p>
<p>
	<strong>Reset an email notification :</strong> This is only usefull on notifications where the interval is set to "once". The reset will erase all previous notifications sent, so it will work as if it was just created.
</p>

<h3>It fails, crash, bug... Do something !</h3>
<p>
	You can contact us there, we'll see what we can do ;)
</p>