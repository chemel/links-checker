# Links Checker

## Install globaly

```bash

composer global require alc/links-checker

# Make sure you have export PATH in your ~/bashrc
export PATH=~/.config/composer/vendor/bin:$PATH

```

## Usage

### check:file command

```bash

# Check urls contained in file (one per line)
phplchk check:file tests/input.txt

# Check urls and write results in csv file
phplchk check:file tests/input.txt -o tests/output.csv

# Check urls from remote file
phplchk check:file https://raw.githubusercontent.com/chemel/links-checker/master/tests/input.txt

# Show help
phplchk check:file --help

```

### check:sitemap command

```bash

# Check sitemap.xml
phplchk check:sitemap http://blog.chemel.fr/sitemap.xml

# Check sitemap.xml and write results in csv file
phplchk check:sitemap http://blog.chemel.fr/sitemap.xml -o output.csv

# Crawl sitemap.xml and check links on each pages
phplchk check:sitemap http://blog.chemel.fr/sitemap.xml --level=2 
phplchk check:sitemap http://blog.chemel.fr/sitemap.xml -o output.csv --level=2 

# Show help
phplchk check:sitemap --help

```
### sitemap:crawl command

```bash

# Dump sitemap urls
phplchk sitemap:crawl http://blog.chemel.fr/sitemap.xml > sitemap.txt

```

### check:seo command

```bash

# Check + extract meta title, description, keywords, canonical url
phplchk check:seo tests/seo-input.txt tests/seo-output.csv

```

### url:generator command

```bash

phplchk url:generator 'http://blog.chemel.fr/page/{1-10}/' > check-me.txt

```
