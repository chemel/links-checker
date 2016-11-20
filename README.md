# Links Checker

## Install:

```bash

composer install

```

## Usage:

### check:file command

```bash

# Check urls contained in file (one per line)
php console.php check:file tests/input.txt

# Check urls and write results in csv file
php console.php check:file tests/input.txt -o tests/output.csv

# Check urls from remote file
php console.php check:file https://raw.githubusercontent.com/chemel/links-checker/master/tests/input.txt

# Show help
php console.php check:file --help

```

### check:sitemap command

```bash

# Check sitemap.xml
php console.php check:sitemap http://blog.chemel.fr/sitemap.xml

# Check sitemap.xml and write results in csv file
php console.php check:sitemap http://blog.chemel.fr/sitemap.xml -o output.csv

# Crawl sitemap.xml and check links on each pages
php console.php check:sitemap http://blog.chemel.fr/sitemap.xml --level=2 
php console.php check:sitemap http://blog.chemel.fr/sitemap.xml -o output.csv --level=2 

# Show help
php console.php check:sitemap --help

```

### check:seo command

```bash

# Check + extract meta title, description, keywords, canonical url
php console.php check:seo tests/seo-input.txt tests/seo-output.csv

```

### url:generator command

```bash

php console.php url:generator 'http://blog.chemel.fr/page/{1-10}/' > check-me.txt

```
