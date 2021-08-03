# API for retention data analysis graph

Web application developed with php.

## Requirements to run the application locally

```
php 8.0
nginx server 
```

### Configurations
Update all configurations in ``` /src/configs/Config.php ``` file

Properly update data source path when updating the data source.

## Local deployment guide
Application is configured to run on docker container. 
In case wants to run local server 
here are the steps

```clone the repository```

```switch to master, the stable branch```

install dependencies
```bash
$ composer install
```

Run unit tests.

```bash
$ vendor/bin/phpunit tests
```

### Deploy on docker container

Run ```docker-compose up ```

APIs will be exposed via http://localhost:8081/api/
Exposed port can be changed within docker-compose file. 
If port is changed in any case, Update the endpoint in the front end application.
