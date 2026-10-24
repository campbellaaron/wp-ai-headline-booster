# Headline Booster – AI Title Suggestions  
![Version](https://img.shields.io/badge/version-1.0.7-blue)
![PHP Compatibility](https://img.shields.io/badge/PHP-%3E=7.4-brightgreen)
![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-blue)
![License](https://img.shields.io/badge/License-GPL--3.0--or--later-blue)

Generate AI-powered headline variants with scoring directly inside the WordPress block editor.

![Banner](assets/banner-1544x500.png)

---

## 🧠 What is Headline Booster?

**Headline Booster** is a WordPress plugin that uses the OpenAI API to generate alternative titles for your posts in real time. It fully integrates into the Gutenberg editor and adds a sidebar panel where you can:

- Generate 3–5 alternative headlines  
- View an automatic **score** (0–100) for each suggestion  
- Instantly apply a chosen title  
- Highlight the best-performing option with *Recommended* badge  

No external tracking, no analytics, no ads — just smarter headline writing.

---

## ✨ Features

- 🔥 **AI-powered headline generation**  
- 📊 **Scoring system** to highlight strong titles  
- 🎨 **Tone selection** (Neutral, Casual, Professional, Dramatic, etc.)  
- 🧩 **Native block editor integration**  
- ⚙️ **Clean settings screen** for managing your API key  
- 🔐 **Privacy-first:** Only post titles/excerpts are sent to OpenAI  
- 🛠️ Zero JavaScript build steps — uses WP-bundled React and `createElement`

---

## 🖼️ Screenshots

### Editor Sidebar Panel
![Screenshot 1](assets/screenshot-1.png)

### Settings Page
![Screenshot 2](assets/screenshot-2.png)

---

## 🚀 Installation

1. Clone or download the plugin into:

    wp-content/plugins/headline-booster/

2. Activate **Headline Booster – AI Title Suggestions** from **Plugins → Installed Plugins**  
3. Go to **Settings → Headline Booster** and enter your OpenAI API key  
4. Edit a post → open the *Headline Booster* sidebar → click **Generate Variants**

---

## 🔧 Developer Notes

The plugin uses:

- **PHP 7.4+**
- **WordPress REST API**
- **OpenAI Chat Completions API**
- **wp.element**, **wp.components**, **wp.data**, **wp.plugins**
- `wp_remote_post` for secure server-side requests
- No JSX build step — pure `createElement()` rendering

## Changelog

- 1.0.7 — Retry an SSL connection reset once after a short delay.

- 1.0.6 — Use `gpt-6-luna` with reasoning disabled and bounded output. Update editor panel integration for WordPress 7.1 while preserving WordPress 6.0–6.5 support. Fix optional excerpts, leading headline numbers, and incomplete API responses.

- 1.0.5 -
- - Updated all plugin prefixes, class names, option keys, constants, and JavaScript globals to use a unique and fully namespaced `headline_booster_aits_` prefix as required by WordPress.org review guidelines.
- - Replaced short `hb_*` identifiers with fully prefixed versions to prevent naming conflicts.
- - Updated localized JavaScript global from `HeadlineBoosterSettings` to `HBAITSSettings`.
- - Improved internal file structure and naming consistency for better long-term maintainability.
- - Cleaned up assets and removed directory-only images from plugin zip as requested by reviewers.
- - General code cleanup and preparation for WordPress.org approval.

- 1.0.4
Plugin check compliance and text domain alignment for wp.org slug

- 1.0.0
Initial release.

### Key Files

- `headline-booster.php` — plugin bootstrap & asset loading  
- `includes/class-hb-settings.php` — settings page  
- `includes/class-hb-rest.php` — REST endpoints  
- `includes/class-hb-ai-client.php` — OpenAI API wrapper  
- `assets/js/editor-sidebar.js` — Gutenberg panel & scoring  

---

## 🔐 Privacy

Only the post title and optional excerpt are sent to the OpenAI API.  
The plugin stores **only** your API key in WordPress options.

---

## 📝 License

GPLv3 or later.  
Pull requests welcome!

---

## 👨‍💻 Author

Created by **Aaron Campbell**  
<https://campbellaaron.github.io>

