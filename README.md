# Contao Image Usage Bundle

## About
This bundle has the goal to find all used files in the frontend.
That is for images (also assets) and downloads (pdfs, etc.)

## Installation
Install [composer](https://getcomposer.org) if you haven't already.
Afterwords, you can add the bundle like this:
```sh
composer require mediamotionag/contao-image-usage-bundle
```

## Usage
1. Install Bundle
2. Update Database: vendor/contao/manager-bundle/bin/contao-console contao:migrate
3. Run Indexer: vendor/bin/contao-console contao:crawl
2. See results in the file-manager

## Contribution
Bug reports and pull requests are welcome
