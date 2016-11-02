Links Checker
====

Install:
======

```bash

composer install

```

Usage:
======

```bash

# Check urls contained in file (one per line)
php checkFromFile.php urls.txt output-file.tsv

# Check urls from a sitemap
php checkFromSitemap.php http://blog.chemel.fr/sitemap.xml output-sitemap.tsv

```