# Thi trắc nghiệm test cmooint

## Create database for test
Change this line in `.env` file to
```sh
IS_PROD_DB=false
```
and run seeder again

## Run step
```sh
pip install mysql-connector-python
composer install
php artisan migrate:fresh --seed
python3 crawl/dop.py;
php artisan ser
```
