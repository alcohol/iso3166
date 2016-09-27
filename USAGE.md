---
layout: default
permalink: USAGE
title: Using league/iso3166
---

## Using league/iso3166

### Interface

The following methods are provided by the `League\ISO3166\ISO3166DataProvider` interface.

**Lookup data by alpha2 code:**

``` php
$data = (new League\ISO3166\ISO3166)->alpha2($alpha2);
```

**Lookup data by alpha3 code:**

``` php
$data = (new League\ISO3166\ISO3166)->alpha3($alpha3);
```

**Lookup data by numeric code:**

``` php
$data = (new League\ISO3166\ISO3166)->numeric($numeric);
```
