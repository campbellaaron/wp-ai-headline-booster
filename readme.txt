=== Headline Booster – AI Title Suggestions ===
Contributors: campbellaaron
Donate link: https://campbellaaron.github.io
Tags: ai, headlines, seo, titles, writing, editor, gutenberg, openai
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

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

== Changelog ==

= 1.0.0 =
Initial release.

== Upgrade Notice ==

= 1.0.0 =
First stable release.
