If you encounter yourself dealing with conflicts in changelog you have come to the right place.

Enter Changelog manager :)

### Changelog generator command:
```
php artisan changelog:generate
```
The options for the command are:
- delete-files : in case you want to delete the yaml files afterwards.
- changelog-version : it will be in the file generated along with the date. For instance, '1.1.2'.


This will generate a markdown file with the changelog.

### YAML files:
```yaml
type:
    - '#PR-id Explanation'
```
This is the structure you need to follow: for each PR you create a yaml file
under a folder (by default 'changelogs' in the root but can be customize in the config file).
'type' is also customizable in case you have more types of changes. By default we provide:
'added', 'changed', 'deprecated', 'fixed' and 'removed'.

### Add changelog command:
```
php artisan changelog:add
```
This command will ask some questions and generate the yaml file.
