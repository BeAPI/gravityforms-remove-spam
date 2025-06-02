# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.1] - 2024-06-02
### Fixed
- Translation file gravityforms-remove-spam.pot renamed and fixed

## [1.0.0] - 2024-06-02
### Added
- Definition of plugin constants (version, paths, etc.)
- Minimum required PHP version check
- Automatic deactivation if Gravity Forms or ACF PRO are not present
- Addition of an options subpage in Gravity Forms
- Registration of ACF fields for spam management
- Plugin translations loading
- Automatic deletion of entries considered as spam
- Disabling notifications for entries considered as spam
- Management of emails and links considered as spam via options
- PHP compatibility management with admin notices in case of incompatibility