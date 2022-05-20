# Recalculate-Rates plugin for Kimai

A Kimai plugin, which forces a recalculation of the hourly and fixed rates for timesheet records on certain updates.

There are two possible modes in which this plugin can work:

1. Recalculate prices on every update
2. Recalculate prices if certain fields were changed (Customer, Project, Activity, User, Price)

The second mode is the better one, but only available from Kimai 1.20.1 on.

You can configure the mode, by default mode 2 is used, unless your Kimai version is too old.

## Recalculate prices on every update

The good part is: you can change customer/project and activity and be sure, that the correct rate is used.

The bad part: a manually entered hourly/fixed rate will be overwritten. You HAVE to work with the pre-configured rates on your activities/projects/customers.  

## Recalculate prices only if certain fields were changed

## Installation

First clone it to your Kimai installation `plugins` directory:
```bash
cd var/plugins/
git clone https://github.com/Keleo/RecalculateRatesBundle.git
```

The file structure needs to look like this afterwards:

```bash
var/plugins/
├── RecalculateRatesBundle
│   ├── RecalculateRatesBundle.php
|   └ ... more files and directories follow here ... 
```

And then rebuild the cache: 
```bash
bin/console kimai:reload --env=prod
```
