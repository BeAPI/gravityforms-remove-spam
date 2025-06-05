# Gravity Forms Remove Spam for Gravity Forms

**Version:** 1.0.3

**Author:** Be API Technical team  

**Plugin URI:** https://beapi.fr

## Description

**Gravity Forms Remove Spam** is a WordPress plugin that automatically deletes submissions considered as spam in Gravity Forms. Administrators can define, via an ACF options page, lists of email addresses and links considered as spam. Any entry matching these criteria is automatically deleted.

## Features

- **Automatic spam removal:**
  - Deletes Gravity Forms entries containing emails or links defined as spam.
- **Customizable criteria:**
  - List of email addresses considered as spam.
  - List of links (URLs) considered as spam if found in field content.
- **Gravity Forms integration:**
  - Hooks into `gform_after_submission` to delete entries after submission.
  - Hooks into `gform_disable_notification` to disable notifications for spam entries.
- **ACF options page:**
  - Adds a "Spam" subpage under the Gravity Forms menu (requires ACF Pro) to manage criteria.
- **Multisite compatible:**
- **Translation ready:**
  - Textdomain automatically loaded from `/languages`.
- **Security & robustness:**
  - The plugin deactivates itself if Gravity Forms or ACF Pro are not active.

## Installation

1. Download and upload the plugin to your WordPress site, then activate it.
2. Make sure [Gravity Forms](https://www.gravityforms.com/) **and** [ACF Pro](https://www.advancedcustomfields.com/pro/) are installed and activated.
3. Go to **Forms > Spam** in the WordPress admin to configure spam emails and links.

**Via Composer:**

```
composer require gravityforms/remove-spam
```

## Configuration

On the **Spam** options page:

- **Email addresses considered as spam:**
  - Enter one or more addresses, separated by commas (one per line possible).
  - Any submission using these emails will be deleted.
- **Links considered as spam in content:**
  - Enter one or more URLs, separated by commas (one per line possible).
  - If any of these links are found in a text, textarea, or name field, the entry will be deleted.

## How it works

- On each form submission, the plugin checks all fields for emails or links considered as spam.
- If a match is found, the entry is deleted via the Gravity Forms API and notifications are disabled.
- Criteria are centralized on the main site in multisite environments.

## Requirements

- PHP **7.4** or higher
- WordPress **5.0** or higher
- Gravity Forms
- ACF Pro

## License

This plugin is licensed under the GPLv2 or later. 
The development of this plugin is sponsored by CDC Habitat, a leading provider of social housing in France.