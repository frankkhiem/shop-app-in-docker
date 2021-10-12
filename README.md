# K Shop e-commerce

This is a website to supports the online business of smartphones, laptops, digital devices, etc. The project was done during the internship period.  
To launch the project, please follow these steps:


## Prerequisites

Before you continue, make sure your computer has the following requirements installed:  

<div>
  <a href="https://www.docker.com/" target="_blank">
    <img src="https://s3-ap-southeast-1.amazonaws.com/homepage-media/wp-content/uploads/2021/01/28133406/docker-banner.png" width="50px">
  </a>
  <p>Docker version for your OS (prefer >= 20.10.8)</p>
  <a href="https://www.docker.com/" target="_blank">
    <img src="https://www.docker.com/blog/wp-content/uploads/2020/02/Compose.png" width="50px">
  </a>
  <p>Docker Compose (prefer >= v2.0.0)</p>
</div>

With Docker and Docker Compose, you just need them.  

## Installation Guide

Clone project  
```bash
git clone https://github.com/frankkhiem/thuc-tap-chuyen-nganh.git
```

Switch branch from master to deploy-in-docker
```bash
cd thuc-tap-chuyen-nganh
git checkout deploy-in-docker
```

Start project
```bash
docker-compose up
```
Install dependencies
```bash
docker-compose exec php composer install
docker-compose exec npm npm install
```

Install laravel-echo-server
```bash
docker-compose exec npm npm install -g laravel-echo-server
```

Start laravel-echo-server and laravel queue
```bash
docker-compose exec npm laravel-echo-server start
docker-compose exec php php artisan queue:work
```

Open browser with URL: http://localhost:8080

For the project database, it can be installed in two ways:

1. Use sample database  
    - Copy files /sample_database/k3mn_shop.sql to mysql container to import create k3mn_shop database.
2. Create new database
    ```bash
    docker-compose exec php php artisan migrate
    ``` 

    Project's database will be created. Will need to import new data manually.

About the data import feature for administrator, the import template can be used in the directory /data_test_import 

## Let's experience this project 🙏 💪 🌟 🔥
