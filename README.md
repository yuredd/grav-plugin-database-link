# Database Link Plugin

The **Database Link** Plugin is for [Grav CMS](http://github.com/getgrav/grav).  This README.md file should be modified to describe the features, installation, configuration, and general usage of this plugin.

## Description

Adds Form processing action "database"

## Using the plugin

In a Form Page, you can store the posted data in the configured database

`./user/pages/02.testformtodb/form.md`

```
form:
    [...]
    fields:
        [...]
    process:
        database:
          query: "INSERT INTO table(fieldA, fieldB) VALUES (?, ?)"
          values:
              - fieldA
              - fieldB
```
