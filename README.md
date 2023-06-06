## Installation
Clone the repository to your local machine:
```
git clone https://github.com/elite133713/pstt.git
```
Install the project dependencies using Composer:

```
composer install
```

### Usage

Prepare an input file with transaction data in the specified format. Each transaction should be on a new line in JSON
format:

```
{"bin":"45717360","amount":"100.00","currency":"EUR"}
{"bin":"516793","amount":"50.00","currency":"USD"}
{"bin":"45417360","amount":"10000.00","currency":"JPY"}
{"bin":"41417360","amount":"130.00","currency":"USD"}
{"bin":"4745030","amount":"2000.00","currency":"GBP"}
```

Run the `app.php` script and provide the path to the input file as an argument:

```
php app.php input.txt
```

Replace input.txt with the path to your actual input file.

The script will calculate the commissions for each transaction and display the results in the console.

### Testing

The project includes unit tests for the CommissionCalculator class. You can run the tests using PHPUnit:

```
./vendor/bin/phpunit tests
```
