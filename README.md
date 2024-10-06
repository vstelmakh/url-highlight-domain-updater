# Url highlight domain updater
[![build](https://github.com/vstelmakh/url-highlight-domain-updater/actions/workflows/build.yml/badge.svg)](https://github.com/vstelmakh/url-highlight-domain-updater/actions)    
This is a small helper tool that simplifies the process of top level domain list update, for the [Url highlight](https://github.com/vstelmakh/url-highlight) library.

> [!WARNING]
> This is not meant to be used publicly. See it as internal project tool.

## Requirements
* [PHP](https://www.php.net/) 8.3+
* [mbstring](https://www.php.net/manual/en/book.mbstring.php) extension
* [intl](https://www.php.net/manual/en/book.intl.php) extension

## Installation
1. Clone [Git](https://git-scm.com/) repository:
```shell
git clone git@github.com:vstelmakh/url-highlight-domain-updater.git
```

2. Navigate to repository directory:
```shell
cd url-highlight-domain-updater
```

3. Install [Composer](https://getcomposer.org/) dependencies:
```shell
composer install --prefer-dist --no-dev --optimize-autoloader
```

## Usage
Run [update-domains](bin/update-domains) console command to crawl top level domains from [IANA](https://www.iana.org/)
and save result in [Url highlight](https://github.com/vstelmakh/url-highlight) format:
```shell
bin/update-domains result/path/Domains.php
```

> [!TIP]
> Use `bin/update-domains --help` option to see command documentation and usage examples.

## Credits
[Volodymyr Stelmakh](https://github.com/vstelmakh)  
Licensed under the MIT License. See [LICENSE](LICENSE) for more information.  
