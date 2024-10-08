# Installation

```bash
composer require howmas/is-vietnam-ip
```

# How to use

```bash
use HowMAS\IsVietNamIP\Checker;

require_once 'vendor/autoload.php';

$isVietNamIP = Checker::isVietNamIP();

echo $isVietNamIP ? "Viet Nam" : "Not Viet Nam";
```
