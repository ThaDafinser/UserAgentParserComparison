
# UserAgentParserComparison

We took thousands of user agent string and run them against different parsers...

[Here are the results](http://thadafinser.github.io/UserAgentParserComparison/)


## Installation


### Step1) Download this repo

Download this repo to a folder


### Step2) Install dependencies

```
composer update -o --prefer-source
```


### Step 3) Download files

#### Browscap

Download all (currently 3) `browscap.ini` files for PHP from [here](http://browscap.org/)

And put it to `data/*.ini`

#### Wurfl

Download the `wurfl.xml` from [here (need register)](http://www.scientiamobile.com/downloads) or [here (not allowed)](https://github.com/fauvel/wurfl-dbapi/blob/master/data/wurfl.xml)

You need to put the wurfl file to `data/wurfl.xml`


### Step 4) init caches
```
php bin/cache/initBrowscap.php
php bin/cache/initPiwik.php
php bin/cache/initWurfl.php
```


### Step 5) config

Copy the `config.php.dist` to `config.php`
Copy the `bin/getChainProvider.php.dist` to `bin/getChainProvider.php`

And adjust your configuration


### Step 6) Init database

```
vendor/bin/doctrine orm:schema-tool:update --force
php bin/db/initProviders.php
php bin/db/initUserAgents.php
php bin/db/initResults.php
```

#### For vNEXT (not needed until yet)

```
php bin/db/initResultsEvaluation.php
php bin/db/initUserAgentsEvaluation.php
```


## Step 7) Generate reports

```
php bin/html/*.php # just all inside that folder
```

## Step 8) Run your own queries

After executing Step 5) you have already all data you need inside your `mysql` database!

So do whatever you want ;-)
