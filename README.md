# "Stock Stars" Stock Trading Website

## Description

#### Stock Stars is a stock trading website built on PHP code base. Website includes the following features:

* Create a user account and login to your personal account;
* Search for a stock and view stock prices and price changes;
* Buy, sell and shortlist stocks (*currently only with 'dummy' money not real funds*);
* View your personal stock portfolio, profit/loss of the portfolio, and transaction history;
* Send stocks to a friend (other **Stock Stars** registered user);
* *All the forms used on the website include necessary validations*

## Technologies Used

* PHP 7.4
* MySQL 8.0
* Composer 2.4
* Tailwind CSS 3.2

#### PHP Packages, Dependencies, Extensions Used

* nikic/fast-route
* vlucas/phpdotenv
* twig/twig
* guzzlehttp/guzzle
* doctrine/dbal
* ext-curl
* ext-json
* php-di/php-di

## Setup

#### To setup and use the Stock Stars website, follow these steps:

###### PREREQUISITES: Make sure that PHP (v7.4), MySQL (v8.0) and Composer (v2.4) are installed on the system.
1. Clone this repository using the following command: `git clone https://github.com/dianadauksa/stock-market`
2. Install the required packages and dependencies: `composer install`
3. Rename the `.env.example` file to `.env`
4. Register at https://finnhub.io and generate a free API key.
5. Input the generated API key in the `.env` file.
6. Create a database to be used for the project (see [Database Setup](https://github.com/dianadauksa/stock-market/new/main?readme=1#database-setup-db-setup) for instructions).
7. Enter database credentials in the `.env` file (`DB_HOST` is `localhost` if running the project locally).
8. Run the project from the stock-market directory using the command: `php -S localhost:7000`
9. Open the generated link in your chosen web browser and start using the website.

## Database Setup

###### PREREQUISITES: Make sure that MySQL is installed on the system and that the user has the necessary permissions to create and modify databases.
1. Use the MySQL command-line client to connect to the MySQL server with command: `mysql -u <username> -p`.
Enter the MySQL password at the prompt and press Enter. This will connect you to the MySQL server.
2. Create the database with command: `CREATE DATABASE <database_name>`.
Replace `<database_name>` with the name of the database that you want to create.
3. Import the database structure from the ['stocks_structure' script file](stocks_structure.sql) with command: `mysql -u <username> -p <database_name> < stocks_structure.sql`.
Replace `<username>` and `<database_name>` with the MySQL username and the name of the database, respectively.
This will execute the SQL statements in the input file, which will create the tables and other objects in the database needed for the project to run.
4. Populate the database with data (optional). This can be done manually or simply by interacting with the website (*e.g., creating new account, buying/selling stocks etc.*).

## Preview of Main Features

1) GIF Create Account, Login
2) GIF Search for, Buy/Sell Stock, Shortlist Stock/Update Shortlist/Close Shortlist
3) GIF User Portfolio, User Transactions
4) GIF Send stock to a friend 
