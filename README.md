# 2srt2ssa [![Build Status](https://travis-ci.org/mike42/2srt2ssa.svg?branch=master)](https://travis-ci.org/mike42/2srt2ssa)

This is a utility to produce bi-lingual subtitle tracks. It is shipped as both a web application or command-line utility.

You need two [SRT-formatted](https://en.wikipedia.org/wiki/SubRip) subtitle tracks as input. Since SRT cannot hold tow subtitle tracks, we output a single [SSA-formatted file](https://en.wikipedia.org/wiki/SubStation_Alpha).

These formats can be understood by media players such as VLC.

## Quick start

### Web

```bash
composer install
cd public && php -S localhost:8080
```

### Command-line

```bash
composer install
php bin/2srt2ssa --help
```

