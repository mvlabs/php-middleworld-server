Server component for serving [PHP Middleworld](http://www.php-middleworld.com/) data

# USAGE

Clone the repository using

```bash
git clone git@github.com:mvlabs/php-middleworld-server.git
```

The enter in the project directory

```bash
cd php-middleworld-server/src
```

Install [Composer](https://getcomposer.org/) dependencies

```bash
composer install
```

Create the following local configuration files just by copying their respective `.dist` version

```
errorhandler.local.php
local.php
middleware-data.local.php
```

Eventually start up the project with

```bash
cd ..
docker-compose up
```

At this point you should be able to see something navigating at [localhost:80](http://localhost/).

# CONTRIBUTING

If you want to add middleware to [PHP Middleworld](http://www.php-middleworld.com/), or
edit the existing informations, you could just create a merge request against `develop`
editing the `src/data/middleware.json` file. We will review it and publish it as soon
as possible!
