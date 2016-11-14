Backup Library
==============

[![Build Status](https://travis-ci.org/4devs/backup.svg?branch=master)](https://travis-ci.org/4devs/backup)

## Installation
Backup uses Composer, please checkout the [composer website](http://getcomposer.org) for more information.

The simple following command will install `fdevs/backup` into your project. It also add a new
entry in your `composer.json` and update the `composer.lock` as well.


```bash
composer require fdevs/backup
```

## Usage examples

### dump/restore folder with compress

```php
<?php
use FDevs\Backup\Manager;
use FDevs\Backup\Source\Folder;
use FDevs\Backup\Filesystem\LocalFile;
use FDevs\Backup\Compress\TarGzip;

$sourceFolder = '';//your source folder. for example with images
$tmpFolder = ''; //tmp folder MST be writable
$dumpFolder = '';//dump folder if ypu use local dump

$source = new Folder($sourceFolder,$tmpFolder);
$filesystem = new LocalFile($dumpFolder);
$compress = new TarGzip();

$manager = new Manager($source,$filesystem,$compress);
$key = $manager->dump();
echo $key;//show key dumped folder

$manager->restore($key);//restore data

$manager->keyList();//show all keys in uses filesystem
```

### dump/restore mongodb with compress

```php
<?php
use FDevs\Backup\Manager;
use FDevs\Backup\Source\MongoDB;
use FDevs\Backup\Filesystem\LocalFile;
use FDevs\Backup\Compress\TarGzip;

$dumpFolder = '';//dump folder if ypu use local dump
$tmpFolder = ''; //tmp folder MUST be writable
$options = [
    //'host' => 'localhost' Specifies a resolvable hostname for the mongod to which to connect. default localhost
    //'port' => 27017 Specifies the TCP port on which the MongoDB instance listens for client connections. Delault 27017
    //'db'=>'dbname' Specifies a database to backup. If you do not specify a database, copies all databases in this instance into the dump files.
    //'collection'=>'collection' Specifies a collection to backup. If you do not specify a collection, this option copies all collections in the specified database or instance to the dump files.
    //'username'=>'username' Specifies a username with which to authenticate to a MongoDB database that uses authentication.
    //'password'=>'password' Specifies a password with which to authenticate to a MongoDB database that uses authentication.
    //'override' => false Before restoring the collections from the dumped backup, drops the collections from the target database. 
];

$source = new MongoDB($tmpFolder);
$filesystem = new LocalFile($dumpFolder);
$compress = new TarGzip();

$manager = new Manager($source,$filesystem,$compress);
$key = $manager->dump($options);
echo $key;//show key dumped folder

$manager->restore($key,$options);//restore data

$manager->keyList();//show all keys in uses filesystem
```

### dump/restore mysql with compress

```php
<?php
use FDevs\Backup\Manager;
use FDevs\Backup\Source\Mysql;
use FDevs\Backup\Filesystem\LocalFile;
use FDevs\Backup\Compress\TarGzip;

$dumpFolder = __DIR__.'/dump/';//dump folder if ypu use local dump
$tmpFolder = __DIR__.'/tmp/'; //tmp folder MUST be writable
$options = [
    'host' => 'localhost',// Specifies a resolvable hostname for the mysqldump to which to connect. default localhost
    'port' => 3306, //Specifies the TCP port on which the Mysql server instance listens for client connections. Delault 3306
    'databases'=>['dbname'],// Specifies a database to backup. If you do not specify a database, copies all databases in this instance into the dump files.
    'user'=>'symfony', //Specifies a username with which to authenticate to a Mysql database that uses authentication.
    'password'=>'symfony', //Specifies a password with which to authenticate to a Mysql database that uses authentication.
    'override' => false,//Before restoring the collections from the dumped backup, drops the tables from the target database.
];

$source = new Mysql($tmpFolder);
$filesystem = new LocalFile($dumpFolder);
$compress = new TarGzip();

$manager = new Manager($source,$filesystem,$compress);
$key = $manager->dump($options);
echo $key;//show key dumped folder

$manager->restore($key,$options);//restore data

$manager->keyList();//show all keys in uses filesystem
```

License
-------

This library is under the MIT license. See the complete license in the library:

    LICENSE


---
Created by [4devs](http://4devs.pro/) - Check out our [blog](http://4devs.io/) for more insight into this and other open-source projects we release.
