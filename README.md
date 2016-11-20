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
php console.php check urls.txt output-file.tsv

# Check urls and return results in stdin
$php console.php check urls.txt -q
# Sample output:
requestedUrl	url	success	statusCode	errorNo	error
http://blog.chemel.fr/	http://blog.chemel.fr/	1	200	0	
http://error.chemel.fr/	http://error.chemel.fr/	0	0	6	"Could not resolve host: error.chemel.fr"

```
