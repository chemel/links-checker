# Links Checker

## Install:

```bash

composer install

```

## Usage:

```bash

# Check urls contained in file (one per line)
php console.php check:file urls.txt output-file.tsv

# Check urls and return results in stdin
php console.php check:file urls.txt -q

# Check sitemap.xml
php console.php check:sitemap http://blog.chemel.fr/sitemap.xml sitemap-check.tsv
php console.php check:sitemap http://blog.chemel.fr/sitemap.xml -q

```
