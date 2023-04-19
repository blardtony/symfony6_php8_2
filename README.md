# Simple docker symfony

## Init project

Build images
```bash
docker compose build
```

Launch container

```bash
docker compose up
```

Get bash shell in the container

```bash
docker exec -it www_symfony_test /bin/bash
```

Run composer install or composer update
```bash
cd project
composer update
yarn install
```

To access to your app, go on http://localhost:8000/

To access phpmyadmin go on http://localhost:8080/
Username : root and no password

Add DATABASE_URL="mysql://root:@db:3306/kifekoi?serverVersion=8&charset=utf8mb4" in .env
