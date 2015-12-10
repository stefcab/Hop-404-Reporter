<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="box">
	<div class="tbl-ctrls">
		
		<fieldset class="tbl-search right">
			<a class="btn tn action" href="<?=ee('CP/URL')->make('addons/settings/hop_404_reporter')?>"><?=lang('nav_index')?></a>
			<a class="btn tn action" href="<?=ee('CP/URL')->make('addons/settings/hop_404_reporter/display_emails')?>"><?=lang('nav_emails')?></a>
			<a class="btn tn action" href="<?=ee('CP/URL')->make('addons/settings/hop_404_reporter/settings')?>"><?=lang('nav_settings')?></a>
			<a class="btn tn action" href="<?=ee('CP/URL')->make('addons/settings/hop_404_reporter/support')?>"><?=lang('nav_support')?></a>
		</fieldset>
		<h1><?=lang('support_page_title')?></h1>
		
		<div class="tbl-wrap">
			<h3>What is Hop 404 Reporter?</h3>
			<p>
				Hop 404 Reporter will help you track down times when a user requests a page that doesn't exist. Fixing these erros will give you more satisfied users, higher search engine rankings, and better traffic overall.
			</p>

			<h3>Set it up</h3>
			<p>
				In order to function, Hop 404 Reporter requires you to put this tag -- <strong>{exp:hop_404_reporter:process_url}</strong> -- into your 404 page template in order to track each 404 errors occurring.
			</p>

			<p>
				It also requires you to have a 404 template specified in your "Global Template Preferences" -- and it requires you to be generating a 404 page for bad URLs; ExpressionEngine may not always generate a 404 page when you think it should, but that's beyond the capabilities of this add-on to solve.
			</p>

			<h3>404 URLs</h3>
			<p>
				<strong>Referrer URL :</strong> The referrer URL can have 3 different values.
				<ul>
					<li>&bull; A normal URL : This means the user came to the 404 URL from this referrer URL. If you look at this page, you may find the incorrect link.</li>
					<li>&bull; Referrer not specified : This means there is no referrer. In most cases, this occurs when the user entered a URL manually, from a bookmark or https:// page.</li>
					<li>&bull; Referrer not tracked : If you disabled the referrer tracking in the settings, this will be displayed.</li>
				</ul>
			</p>

			<h3>Email notifications</h3>
			<p>
				<strong>URL to match :</strong> This field is parsed as a regular expression so you can create advanced filters. But it's also very easy to use. If you want a notification when a 404 URL contains "wrong_url", just enter "wrong_url" in this field. It's as simple as that. If you leave it empty, the notification will be sent each time a 404 occurs, whatever the URL is.
			</p>
			<p>
				<strong>Interval :</strong> You can choose if the 404 URL will be reported just the first time it happens, or every time it occurs. If a 404 occurs with a different referrer, it will be reported as a new error.
			</p>
			<p>
				<strong>Reset an email notification :</strong> This is only useful on notifications where the interval is set to "once". Resetting will erase all previous notifications sent, so the notification will behave as if it was just created.
			</p>

			<h3>It fails, crash, bug... Do something !</h3>
			<p>
				You can contact us at tech@hopstudios.com, we'll see what we can do ;)
			</p>
		</div>
	</div>
</div>
