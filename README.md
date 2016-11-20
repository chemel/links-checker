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

# Check urls from remote file
php console.php check:file https://raw.githubusercontent.com/chemel/links-checker/master/urls.txt -q

# Check sitemap.xml
php console.php check:sitemap http://blog.chemel.fr/sitemap.xml -o sitemap-check.tsv
php console.php check:sitemap http://blog.chemel.fr/sitemap.xml -q

# Crawl sitemap.xml and check links on each pages
php console.php check:sitemap http://blog.chemel.fr/sitemap.xml -o output.tsv --level=2 

```
