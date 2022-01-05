# Changelog

All notable changes will be documented in this file.

## 0.2.0 - 2022-01-05

### Changes

- Added a new repository for getting the list of sites using the Valet source code (now included as a dependency of this package). This is now the default repository.
- Refactoring of the internal code that is used to execute terminal commands to better handle overriding the PATH environment variable.

### Upgrade guide

If you've published the config file, it's strongly recommended to change the `valet-assistant.projects_repository_class` value to use the `ValetSrcRepository` class:

```php
'projects_repository_class' => \Gbuckingham89\ValetAssistant\Entities\Repositories\Projects\ValetSrcRepository::class,
```

## 0.1.0 - 2021-12-28

- Initial development release
