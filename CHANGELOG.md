# Change Log

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

* Added: method ISO3166DataProvider::alpha

## [4.3.1] - 2023-09-11

* Updated: alias for Congo ([#96])

## [4.3.0] - 2023-06-05

* Added: added `exactName` ([#95])

## [4.2.1] - 2023-01-31

* Changed: Croatia adopted the EUR on 2023-01-01

## [4.2.0] - 2023-01-23

* Added: a dataprovider with often requested aliases ([#76])

## [4.1.0] - 2022-09-07

* Changed: Lookup is now unicode-safe ([#78]) and allows partial (prefix) matches ([#74])

## [4.0.0] - 2021-10-22

* Changed: Added return types ([#63] & [#65])

## [3.0.0] - 2020-12-05

* Deprecated: dropped support for PHP < 7.3
* Added: support for PHP 8

## [2.1.5] - 2020-01-29

* Changed: added a common ISO3166Exception interface ([#53]).

## [2.1.4] - 2019-10-23

* Changed: update currency for Estonia to EUR.

## [2.1.3] - 2019-09-25

* Changed: update currency for Latvia to EUR.

## [2.1.2] - 2019-03-14

* Changed: update short names of Eswatini and Macedonia.

## [2.1.1] - 2018-07-17

* Changed: Swaziland was renamed to Eswatini.

## [2.1.0] - 2018-01-02

* New: can now lookup by `name` ([#44]).

## [2.0.0] - 2017-05-11

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

## [1.0.1] - 2016-07-01

* Changed: updated Antarctica currencies ([#6]).
* Deprecated: the `get()` method has been deprecated and will be removed in a future release ([#12]).

## [1.0.0] - 2016-06-30

* New: initial release of `league/iso3166`.

[Unreleased]: https://github.com/thephpleague/iso3166/compare/4.3.1...HEAD
[4.3.1]: https://github.com/thephpleague/iso3166/compare/4.3.0...4.3.1
[4.3.0]: https://github.com/thephpleague/iso3166/compare/4.2.1...4.3.0
[4.2.1]: https://github.com/thephpleague/iso3166/compare/4.2.0...4.2.1
[4.2.0]: https://github.com/thephpleague/iso3166/compare/4.1.0...4.2.0
[4.1.0]: https://github.com/thephpleague/iso3166/compare/4.0.0...4.1.0
[4.0.0]: https://github.com/thephpleague/iso3166/compare/3.0.0...4.0.0
[3.0.0]: https://github.com/thephpleague/iso3166/compare/2.1.5...3.0.0
[2.1.5]: https://github.com/thephpleague/iso3166/compare/2.1.4...2.1.5
[2.1.4]: https://github.com/thephpleague/iso3166/compare/2.1.3...2.1.4
[2.1.3]: https://github.com/thephpleague/iso3166/compare/2.1.2...2.1.3
[2.1.2]: https://github.com/thephpleague/iso3166/compare/2.1.1...2.1.2
[2.1.1]: https://github.com/thephpleague/iso3166/compare/2.1.0...2.1.1
[2.1.0]: https://github.com/thephpleague/iso3166/compare/2.0.0...2.1.0
[2.0.0]: https://github.com/thephpleague/iso3166/compare/1.0.1...2.0.0
[1.0.1]: https://github.com/thephpleague/iso3166/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/thephpleague/iso3166/compare/64bae4f00dbd5679b9a36c54c37af73d5deb5be1...1.0.0

[#96]: https://github.com/thephpleague/iso3166/pull/96
[#95]: https://github.com/thephpleague/iso3166/pull/95
[#78]: https://github.com/thephpleague/iso3166/pull/78
[#76]: https://github.com/thephpleague/iso3166/pull/76
[#74]: https://github.com/thephpleague/iso3166/pull/74
[#65]: https://github.com/thephpleague/iso3166/pull/65
[#63]: https://github.com/thephpleague/iso3166/pull/63
[#53]: https://github.com/thephpleague/iso3166/pull/53
[#44]: https://github.com/thephpleague/iso3166/issues/44
[#29]: https://github.com/thephpleague/iso3166/issues/29
[#24]: https://github.com/thephpleague/iso3166/issues/24
[#23]: https://github.com/thephpleague/iso3166/issues/23
[#19]: https://github.com/thephpleague/iso3166/issues/19
[#18]: https://github.com/thephpleague/iso3166/issues/18
[#15]: https://github.com/thephpleague/iso3166/issues/15
[#12]: https://github.com/thephpleague/iso3166/issues/12
[#6]: https://github.com/thephpleague/iso3166/issues/6
