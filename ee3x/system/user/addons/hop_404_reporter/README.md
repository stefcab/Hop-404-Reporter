# Hop 404 Reporter

## 404 URLs

Referrer URL : The referrer URL can have 3 different values.

 * **A normal URL :** This means the user followed the 404 URL from this referrer URL. If you go on this page, you may find the incorrect link.
 * **Referrer not specified :** This means there is no referrer. In most of the cases, this occurs when the user entered a URL manually.
 * **Referrer not tracked :** If you disabled the referrer tracking in the settings, this will be displayed.

 
## Email notifications

 * **URL to match:** This field is parsed as a regular expression so you can create advanced filters. But it’s also very easy to use. If you want a notification when a 404 URL contains “wrong_url”, just enter “wrong_url” in this field. It’s as simple as that. If you leave it empty, the notification will be send each time a 404 occurs, whatever the URL is.
 * **Interval:** You can choose to get an alert just the first time an error happens, or you can get an email alert each time it occurs. If a 404 occurs with a different referrer, it will be reported as a new error.
 * **Reset an email notification:** This is useful on notifications where the interval is set to “once”. The reset will erase all previous notifications sent, so it will start to work again as though it were just created.


## Usage

In order to function, Hop 404 Reporter requires you to put this tag -- `{exp:hop_404_reporter:process_url}` -- into your 404 page template in order to track each 404 errors occurring.

It also requires you to have a 404 template specified in your "Global Template Preferences" -- and it requires you to be generating a 404 page for bad URLs; ExpressionEngine may not always generate a 404 page when you think it should, but that's beyond the capabilities of this add-on to solve. 

## Changelog

### 1.0

Initial Release


## Licence

Updated: Jan. 6, 2009

####Permitted Use

One license grants the right to perform one installation of the Software. Each additional installation of the Software requires an additional purchased license. For free Software, no purchase is necessary, but this license still applies.

####Restrictions

Unless you have been granted prior, written consent from Hop Studios, you may not:

* Reproduce, distribute, or transfer the Software, or portions thereof, to any third party.
* Sell, rent, lease, assign, or sublet the Software or portions thereof.
* Grant rights to any other person.
* Use the Software in violation of any U.S. or international law or regulation.

####Display of Copyright Notices

All copyright and proprietary notices and logos in the Control Panel and within the Software files must remain intact.
Making Copies

You may make copies of the Software for back-up purposes, provided that you reproduce the Software in its original form and with all proprietary notices on the back-up copy.

####Software Modification

You may alter, modify, or extend the Software for your own use, or commission a third-party to perform modifications for you, but you may not resell, redistribute or transfer the modified or derivative version without prior written consent from Hop Studios. Components from the Software may not be extracted and used in other programs without prior written consent from Hop Studios.

####Technical Support

Technical support is available through e-mail, at sales@hopstudios.com. Hop Studios does not provide direct phone support. No representations or guarantees are made regarding the response time in which support questions are answered.
Refunds

Hop Studios offers refunds on software within 30 days of purchase. Contact sales@hopstudios.com for assistance. This does not apply if the Software is free.
Indemnity

You agree to indemnify and hold harmless Hop Studios for any third-party claims, actions or suits, as well as any related expenses, liabilities, damages, settlements or fees arising from your use or misuse of the Software, or a violation of any terms of this license.

####Disclaimer Of Warranty

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, WARRANTIES OF QUALITY, PERFORMANCE, NON-INFRINGEMENT, MERCHANTABILITY, OR FITNESS FOR A PARTICULAR PURPOSE. FURTHER, HOP STUDIOS DOES NOT WARRANT THAT THE SOFTWARE OR ANY RELATED SERVICE WILL ALWAYS BE AVAILABLE.
Limitations Of Liability

YOU ASSUME ALL RISK ASSOCIATED WITH THE INSTALLATION AND USE OF THE SOFTWARE. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS OF THE SOFTWARE BE LIABLE FOR CLAIMS, DAMAGES OR OTHER LIABILITY ARISING FROM, OUT OF, OR IN CONNECTION WITH THE SOFTWARE. LICENSE HOLDERS ARE SOLELY RESPONSIBLE FOR DETERMINING THE APPROPRIATENESS OF USE AND ASSUME ALL RISKS ASSOCIATED WITH ITS USE, INCLUDING BUT NOT LIMITED TO THE RISKS OF PROGRAM ERRORS, DAMAGE TO EQUIPMENT, LOSS OF DATA OR SOFTWARE PROGRAMS, OR UNAVAILABILITY OR INTERRUPTION OF OPERATIONS.
