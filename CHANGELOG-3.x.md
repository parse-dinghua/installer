# Changelog for 3.x

This changelog references the relevant changes (bug and security fixes) done to `orchestra/installer`.

## 3.8.3

Released: 2019-09-10

### Changes

* Boot installer files using `Illuminate\Database\Events\MigrationsStarted` event.

### Deprecated

* Deprecate `Orchestra\Installation\Installation::bootInstallerFilesForTesting()`.

## 3.8.2

Released: 2019-04-21

### Added

* Added `Orchestra\Installation\Installation::$redirectAfterInstalled` property.
* Added `Orchestra\Installation\Concerns\FileLoader`.

### Changes

* Allow to configure redirection after installation completed from `Orchestra\Installation\InstallerServiceProvider::$redirectAfterInstalled` property.

## 3.8.1

Released: 2019-04-01

### Added

* Added `Orchestra\Installation\Events\InstallationCompleted` event.
* Added `Orchesttra\Installation\Listeners\MigrateDatabaseSchema` event listener.

## 3.8.0

Released: 2019-03-16

### Changes 

* Update support for Orchestra Platform v3.8.
* Refactor controllers to use invokable format.

## 3.7.0

Released: 2018-11-09

### Changes

* Update support for Orchestra Platform v3.7.
* Change `Illuminate\Event\Dispatcher::fire()` to `Illuminate\Event\Dispatcher::dispatch()`.

## 3.6.0

Released: 2018-06-07

### Added

* Added configuration file to modify `installer.php` file.

### Changes

* Update support for Orchestra Platform v3.6.