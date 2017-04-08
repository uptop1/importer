Email Importer
==============

This mini project just reads traffic data in specific format and stores them. It's implemented for CSV and JSON only but can be extended easily to any other format.

Tools used
---------------
1. PHP7
2. Symfony3 Command
3. PHPUnit
4. vfsStream as Filesystem mock
5. Bridge Design Pattern

Getting Started
---------------

### Installation
1. Clone the repository `git clone https://github.com/uptop1/importer.git`
2. Change directory `cd importer`
3. Install the libraries using composer `composer install`
4. Run tests `phpunit tests`

Usage
-----

Use php CLI to run the console command:

```bash
$ php bin/importer 5 samples/input1.csv
```
This will import the sample traffic data from supplier #5.