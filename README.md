
# UserAgentParserComparison

We took thousands of user agent string and run them against different parsers...

[Here are the results](http://thadafinser.github.io/UserAgentParserComparison/)

## Installation

### Step1) Download this repo

Download this repo to a folder


### Step2) Install dependencies

```
composer update -o

vendor/bin/browscap browscap:update
php bin/initPiwikCache.php
php bin/initWurflCache.php
```

### Step 3) Fill the SQLite database

```
php bin/initDatabase.php
php bin/initDatabaseResults.php
```


## Step 4) Generate reports

```
php bin/generateGeneralOverview.php
php bin/generateProviderOverview.php
php bin/generateList.php
```

## Run your own queries

After executing step 3) you have all data inside a `sqlite` database!

Just open it in `data/results.sqlite3` 
