# Thi trắc nghiệm test cmooint

## Create database for test
Change this line in `.env` file to
```sh
IS_PROD_DB=false
```
and run seeder again

## Run step
### new
```bash
docker-compose up -d
docker-compose exec php bash
php artisan key:generate
php artisan migrate:fresh --seed
exit

```

### old
```sh
pip install mysql-connector-python
composer install
php artisan migrate:fresh --seed
python3 crawl/dop.py;
php artisan ser
```
