Pantheon Upstream Management
============================

This package provides a series of scripts to be used to manage upstreams in [Pantheon](https://pantheon.io).

## Installation

Install it as usual with composer:

```
composer require pantheon-systems/upstream-management
```

It will prompt you to authorize the plugin, please do so.

## Usage

This plugin provides two commands to be used by custom upstreams:

### upstream:require

Use it to require dependencies in your upstream-configuration folder.

```
composer upstream-require drupal/ctools
```

### upstream:update-dependencies

Use it to use version locked dependencies in your upstream (and to update those versions). This way you can pin the versions for your upstream dependencies.

```
composer upstream:update-dependencies
```
