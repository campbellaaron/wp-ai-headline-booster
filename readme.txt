=== Headline Booster – AI Title Suggestions ===
Contributors: aarcampb
Donate link: https://campbellaaron.github.io
Tags: ai, headlines, seo, gutenberg, openai
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.5
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Generate AI-powered headline variants with built-in scoring to improve clarity, SEO, and click-through rate directly inside the WordPress editor.

== Description ==

**Headline Booster** helps you instantly improve your blog post titles without leaving the block editor.

Using the OpenAI API, this plugin generates alternative headline suggestions based on your post’s current title and optional excerpt. Each suggestion is automatically scored (0–100) using a simple heuristic that evaluates clarity, structure, length, and keyword strength—making it easy to choose the strongest option.

### ✨ Features

- Generate 3–5 AI-powered alternative headlines for any post  
- Built-in scoring system highlights the most effective option  
- “Recommended” badge automatically marks the best title  
- Fully integrated into the block editor (Gutenberg)  
- Click **Use this title** to instantly replace your post’s headline  
- Settings page to enter your OpenAI API key  
- Supports multiple tone styles (Neutral, Casual, Formal, Excited, Professional, Humorous, Clickbait, Dramatic)  
- Clean, secure REST API implementation  
- No analytics, no tracking, no ads

### 🔐 Privacy & Data Use

This plugin sends **only** the post title and optional excerpt to the OpenAI API to generate suggestions.  
No other site or user data is transmitted or stored externally.

You must provide your own OpenAI API key in **Settings → Headline Booster**.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/` or install via the Plugins screen.  
2. Activate **Headline Booster – AI Title Suggestions**.  
3. Go to **Settings → Headline Booster** and enter your OpenAI API key.  
4. Edit any post, open the **Headline Booster** sidebar panel, and click **Generate Variants**.

== Frequently Asked Questions ==

= Do I need an OpenAI account? =
Yes. You must obtain an API key from <https://platform.openai.com/>.  
The plugin does not include or proxy API keys.

= What data does the plugin send to OpenAI? =
Only the post title and optional excerpt are sent to generate suggestions.

= Does this plugin store anything externally? =
No. All data processing takes place locally or via the OpenAI API.

= Does it work with the Classic Editor? =
Not currently. Headline Booster is built specifically for the block (Gutenberg) editor.

== Screenshots ==

1. Sidebar panel inside the editor showing AI-generated headline suggestions  
2. Settings page for entering your OpenAI API key and selecting tone

== External Services ==

This plugin connects to the OpenAI API to generate headline suggestions. 

It also uses your own OpenAI API key to generate headline suggestions.

When you click the "Generate Variants" button in the post editor, the plugin sends:
- The current post title
- The post excerpt (if set)
- The selected tone
to the OpenAI API at https://api.openai.com/v1/chat/completions in order to request alternative headline suggestions.

You must provide your own OpenAI API key in the plugin settings. The key is stored in your WordPress database and is used only to make requests to the OpenAI API on your behalf.

For more information about OpenAI's policies, please see:
- Terms of Use: https://openai.com/policies/terms-of-use
- Privacy Policy: https://openai.com/policies/privacy-policy

== Changelog ==

= 1.0.5 =
* Updated all plugin prefixes, class names, option keys, constants, and JavaScript globals to use a unique and fully namespaced `headline_booster_aits_` prefix as required by WordPress.org review guidelines.
* Replaced short `hb_*` identifiers with fully prefixed versions to prevent naming conflicts.
* Updated localized JavaScript global from `HeadlineBoosterSettings` to `HBAITSSettings`.
* Improved internal file structure and naming consistency for better long-term maintainability.
* Cleaned up assets and removed directory-only images from plugin zip as requested by reviewers.
* General code cleanup and preparation for WordPress.org approval.

= 1.0.4 =
Plugin check compliance and text domain alignment for wp.org slug

= 1.0.0 =
Initial release.

== Upgrade Notice ==

= 1.0.0 =
First stable release.
