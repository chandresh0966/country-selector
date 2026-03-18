=== Country Selector ===

* Contributors: https://github.com/chandresh0966/
* Tags: country, redirect, geolocation, country redirector, location popup
* Requires at least: 5.6
* Tested up to: 6.6
* Stable tag: 1.0
* Requires PHP: 7.0
* License: GPLv3 or later
* License URI: https://www.gnu.org/licenses/gpl-3.0.html

Country Selector lets site owners show a country selection popup and redirect visitors to country-specific URLs.

== Description ==

Country Selector adds a frontend modal popup where visitors choose their country. Based on the selected country, the plugin can redirect users to a matching URL configured in the WordPress admin.

Core capabilities:
* Enable or disable the popup from admin settings.
* Configure popup title and description text.
* Add country-to-URL redirect mappings.
* Store visitor selection in cookies.

How it works:
* On activation, the plugin creates two tables:
* `wp_cs_countries`
* `wp_cs_country_redirect`
* In admin, you manage popup settings and country redirect rows.
* On frontend, the popup is shown when enabled.
* User choice is stored in cookies and used for redirect decisions.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`, or install it from the WordPress admin plugin screen.
2. Activate the plugin from the Plugins screen.
3. Go to Country Selector in wp-admin.
4. Configure:
* Enable Popup
* Popup Title / Popup Content
* Country redirect URL mappings
5. Save settings and test in a private/incognito browser window.

== Usage Notes ==

* If a selected country has a redirect URL, the visitor is redirected there.
* If no redirect URL is set for a selected country, the popup closes and no redirect happens.
* Cookie lifetime in this version is fixed to 1 day.

== Plugin Analysis (Current v1.0) ==

Strengths:
* Simple setup and easy admin workflow.
* Country list bootstrap on install.
* Uses escaping in many output points.

Important improvement opportunities identified in code review:
* Security (AJAX): delete endpoint should verify a nonce and user capability.
* Security (SQL): one query builds SQL with string concatenation instead of prepared placeholders.
* Behavior: frontend redirect cookie flow can trigger repeated popup + redirect behavior after selection.
* Reliability: settings save error path redirects to `admin.php?page=cs-config`, but the menu slug is `country-selector-config`.
* Data integrity: country seed data is inserted on each activation without deduplication.
* Performance: scripts/styles are enqueued on all admin pages and all frontend pages, not only where needed.
* Compatibility: plugin relies on PHP sessions in WordPress (`session_start` on `init`), which can conflict with cache/CDN setups.
* Compliance: readme/license metadata should stay consistent with the LICENSE file.

== Recommended Roadmap ==

1. Add nonce + capability checks for AJAX delete requests.
2. Convert all DB reads/writes to strict prepared statements and typed validation.
3. Correct settings error redirect slug to `country-selector-config`.
4. Prevent country seed duplicates on re-activation (unique key or upsert strategy).
5. Refactor cookie/redirect logic to avoid repeated automatic redirect loops.
6. Load assets conditionally (plugin admin page and only pages where popup is rendered).
7. Replace session-based flash messaging with WordPress-native admin notices/transients.
8. Add localization wrappers consistently and improve i18n coverage.
9. Add automated tests (activation/install, save settings, delete mapping, frontend cookie flow).

== Frequently Asked Questions ==

= Who should use this plugin? =

Sites that serve multiple regions or have separate country-specific domains can use this plugin to route visitors to the best destination after country selection.

= Does this plugin detect country automatically? =

No. The current version is user-selection based via popup.

= Can I configure cookie lifetime and button text? =

In this codebase, cookie lifetime is fixed and button text is currently static.

== Screenshots ==

1. Admin settings page with popup settings and country redirect mapping.
2. Frontend popup asking user to select country.

== Changelog ==

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.0 =
Initial release.