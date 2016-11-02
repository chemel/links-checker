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

# Check urls contained in file (one par line)
php checkFromFile.php urls.txt output-file.tsv

# Check urls from a sitemap (beta)
php checkFromSitemap.php http://blog.chemel.fr/sitemap.xml output-sitemap.tsv

```