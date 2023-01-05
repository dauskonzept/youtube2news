[![StandWithUkraine](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/badges/StandWithUkraine.svg)](https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md)
[![TYPO3 10](https://img.shields.io/badge/TYPO3-10-orange.svg)](https://get.typo3.org/version/10)
[![TYPO3 11](https://img.shields.io/badge/TYPO3-11-orange.svg)](https://get.typo3.org/version/11)
[![Latest Stable Version](http://poser.pugx.org/dskzpt/youtube2news/v)](https://packagist.org/packages/dskzpt/youtube2news)
[![Total Downloads](http://poser.pugx.org/dskzpt/youtube2news/downloads)](https://packagist.org/packages/dskzpt/youtube2news)
[![Latest Unstable Version](http://poser.pugx.org/dskzpt/youtube2news/v/unstable)](https://packagist.org/packages/dskzpt/youtube2news)
[![License](http://poser.pugx.org/dskzpt/youtube2news/license)](https://packagist.org/packages/dskzpt/youtube2news)
[![PHP Version Require](http://poser.pugx.org/dskzpt/youtube2news/require/php)](https://packagist.org/packages/dskzpt/youtube2news)

TYPO3 Extension "youtube2news"
=================================

## What does it do?

Imports YouTube videos via the official YouTube API
as [EXT:news](https://github.com/georgringer/news)
"News" entities.

**Summary of features**

* Integrates with [EXT:news](https://github.com/georgringer/news) to import
  YouTube videos as News entities
* Provides command to regularly import new/update already imported videos
* Adds a new subtype for EXT:news: "YouTube Video"

## Installation

The recommended way to install the extension is by
using [Composer](https://getcomposer.org/). In your Composer based TYPO3 project
root, just run:
<pre>composer require dskzpt/youtube2news</pre>

## Setup

1. Get your YouTube API access token by following
   the [official documentation](https://developers.google.com/youtube/v3/getting-started)
2. Enter your API access token in the Extension configuration/settings.
3. Run the provided command to import videos: <pre>youtube2news:import-videos {channelname} {storagePid} [limit|25]</pre>

__Recommended__:

Setup a cronjob/scheduler task to regularly import new videos.

## Compatibility

| Version | TYPO3 | News       | PHP        | Support/Development                  |
|---------|-------|------------|------------|--------------------------------------|
| 1.x     | 11.5  | 9.0 - 10.x | 7.4 - 8.0️ | Features, Bugfixes, Security Updates |

## Funtionalities

### Automatic import of videos

This extension comes with a command to import videos of a given youtube channel.
It is recommended to set this command up to run regularly - e.g. once a day.

<pre>youtube2news:import-videos {username} {storagePid} [limit|25]</pre>

__Arguments:__

| Name       | Description                                                           |
|------------|-----------------------------------------------------------------------|
| username   | The youtube channel to import videos from                             |
| storagePid | The PID to save the imported videos                                   |
| limit      | The maximum number of latest videos to import (Optional. Default: 25) |

### Local path to save downloaded files

By default all imported videos are saved in <code>
/public/fileadmin/youtube2news</code>
You can change this path via the Extensions settings <code>
local_file_storage_path</code> option.

## Contributing

Please refer to the [contributing](CONTRIBUTING.md) document included in this
repository.

## Testing

This Extension comes with a testsuite for coding styles and unit/functional
tests. To run the tests simply use the provided composer script:

<pre>composer ci:test</pre>
