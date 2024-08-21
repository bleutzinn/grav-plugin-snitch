# Snitch Plugin

***Abandonment Notice:** Creating and improving my plugins for Grav was fun to do, however times are changing and so am I. My interests have shifted away from coding and so I am abandoning my plugins. If you are interested in taking over please follow the ["Abandoned Resource Protocol"](https://learn.getgrav.org/advanced/grav-development#abandoned-resource-protocol). Simply skip the first two steps and refer to this statement in step 3.*

The **Snitch** Plugin is an extension for [Grav CMS](http://github.com/getgrav/grav). Passes user information to Twig

## About security

This plugin should be used with care as it can reveal sensitive configuration data.

You are advised to enable the plugin for your development environment only. Never enable this plugin in a production environment.

## Installation

Installing the Snitch plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

### GPM Installation (Preferred)

To install the plugin via the [GPM](http://learn.getgrav.org/advanced/grav-gpm), through your system's terminal (also called the command line), navigate to the root of your Grav-installation, and enter:

    bin/gpm install snitch

This will install the Snitch plugin into your `/user/plugins`-directory within Grav. Its files can be found under `/your/site/grav/user/plugins/snitch`.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `snitch`. You can find these files on [GitHub](https://github.com//grav-plugin-snitch) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/snitch
	
> NOTE: This plugin is a modular component for Grav which may require other plugins to operate, please see its [blueprints.yaml-file on GitHub](https://github.com//grav-plugin-snitch/blob/master/blueprints.yaml).

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins`-menu and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/snitch/snitch.yaml` to `user/config/plugins/snitch.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: false
```

Note that if you use the Admin Plugin, a file with your configuration named snitch.yaml will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

## Usage

First enable the plugin.

Follow along using the example in [Groups and Permissions](https://learn.getgrav.org/advanced/groups-and-permissions) and create these three groups in `user/config/groups.yaml`:

```
registered:
  icon: users
  readableName: 'Registered Users'
  description: 'The group of registered users'
  access:
    site:
      login: true
paid:
  readableName: 'Paid Members'
  description: 'The group of paid members'
  icon: money
  access:
    site:
      login: true
      paid: true
administrators:
  groupname: administrators
  readableName: Administrators
  description: 'The group of administrators'
  icon: child
  access:
    admin:
      login: true
    site:
      login: true
```

Assign one or more users to one or more groups:

```
groups: 
  - paid
```

Now use the newly available Twig variables `accounts` and `groups` to create a list (array) of all members of a certain group:

```
{% set group = 'paid' %}
{% set group_members = [] %}
{% for username, properties in accounts %}
    {% if group in properties.groups|keys %}
        {% set group_members = group_members|merge([
            username
        ]) %}
    {% endif %}
{% endfor %}
{# Simply dump the result here #}
{{ vardump(group_members) }}
```

### Notes

Note that for security reasons user passwords are not transferred to Twig.
