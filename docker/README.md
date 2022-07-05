
# unit tests
first copy and edit phpunit config
```bash
cp ../phpunit.xml.dist ../phpunit.xml
```

Execute unit tests with
```bash
docker run --rm -v $(pwd)/../:/code teqneers/runtime/php:8.1 php /code/vendor/bin/phpunit -c /code/phpunit.xml
```
