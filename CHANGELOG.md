# Change Log

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

## [2.1.2] - 2017/05/11

* Changed: update short names of Eswatini and Macedonia.

## [2.1.1] - 2017/05/11

* Changed: Swaziland was renamed to Eswatini.

## [2.1.0] - 2017/05/11

* New: can now lookup by `name` ([#44]).

## [2.0.0] - 2017/05/11

* Changed: renamed `getBy<Identifier>` methods to `<identifier>` ([#29]).
* Changed: renamed `getAll` to `all` ([#29]).
* Changed: renamed `listBy` to `iterator` ([#29]).
* Changed: `ISO3166` and `DataValidator` are now `final` ([#24]).
* Changed: support for PHP 5.5.x has been dropped ([#23]).
* Changed: `get()` method has been removed ([#19]).
* Changed: `currency` key in default dataset entries is now always an array ([#15]).
* New: `ISO3166` now implements `Countable` interface ([#18]).
* New: can now replace default dataset by injecting a new one into `ISO3166` through the constructor ([#18]).
* New: `getBy[Alpha2,Alpha3,Numeric]` now throw `InvalidArgumentException` if anything other than a string is passed in ([#18]).

## [1.0.1] - 2016/07/01

* Changed: updated Antarctica currencies ([#6]).
* Deprecated: the `get()` method has been deprecated and will be removed in a future release ([#12]).

## [1.0.0] - 2016/06/30

* New: initial release of `league/iso3166`.

[Unreleased]: https://github.com/thephpleague/iso3166/compare/2.1.2...HEAD
[2.1.2]: https://github.com/thephpleague/iso3166/compare/2.1.1...2.1.2
[2.1.1]: https://github.com/thephpleague/iso3166/compare/2.1.0...2.1.1
[2.1.0]: https://github.com/thephpleague/iso3166/compare/2.0.0...2.1.0
[2.0.0]: https://github.com/thephpleague/iso3166/compare/1.0.1...2.0.0
[1.0.1]: https://github.com/thephpleague/iso3166/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/thephpleague/iso3166/compare/64bae4f00dbd5679b9a36c54c37af73d5deb5be1...1.0.0

[#29]: https://github.com/thephpleague/iso3166/issues/29
[#24]: https://github.com/thephpleague/iso3166/issues/24
[#23]: https://github.com/thephpleague/iso3166/issues/23
[#19]: https://github.com/thephpleague/iso3166/issues/19
[#18]: https://github.com/thephpleague/iso3166/issues/18
[#15]: https://github.com/thephpleague/iso3166/issues/15
[#12]: https://github.com/thephpleague/iso3166/issues/12
[#6]: https://github.com/thephpleague/iso3166/issues/6
