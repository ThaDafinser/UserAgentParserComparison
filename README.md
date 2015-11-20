
# UserAgentParserComparison

We took thousands of user agent string and run them against different parsers...

[Here are the results](http://thadafinser.github.io/UserAgentParserComparison/)

## Installation

### Step1) Download this repo

Download this repo to a folder


### Step2) Install dependencies

```
composer update -o
```

### Step 3) Download files

#### Browscap
Download the `full_php_browscap.ini` from [here](http://browscap.org/stream?q=Full_PHP_BrowscapINI)

And put it to `data/full_php_browscap.ini`

#### Wurfl
Download the `wurfl.xml` from [here (need register)](http://www.scientiamobile.com/downloads) or [here (not allowed)](https://github.com/fauvel/wurfl-dbapi/blob/master/data/wurfl.xml)

You need to put the wurfl file to `data/wurfl.xml`

### Step 4) init caches
```
php bin/initCacheBrowscap.php
php bin/initCachePiwik.php
php bin/initCacheWurfl.php
```


### Step 5) Fill the SQLite database

```
php bin/initDatabase.php
php bin/initDatabaseResults.php
```


## Step 6) Generate reports

```
php bin/generateGeneralOverview.php
php bin/generateProviderOverview.php
php bin/generateList.php
```

## Run your own queries

After executing Step 5) you have all data inside a `sqlite` database!

Just open `data/results.sqlite3` with a viewer and query it...have fun :-)
