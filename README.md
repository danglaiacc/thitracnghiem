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
python3 py-migration-db/sap.py;
python3 py-migration-db/databricks-dea.py;
php artisan ser
```
