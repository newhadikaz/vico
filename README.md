# vico
vico assessment

* in this assessment I covered the following:
- authorization with jwt token
- write -> store, edit and show api endpoints for member review on a project.
- write -> unit test for the endpoints covering different scenarios.

- Steps to launch the project:

1. clone the project.
2. run the command : composer install
3. configure the database in .env file, and migrate using this command : php bin/console doctrine:migrations:generate
4. Generate the SSL keys with the command : php bin/console lexik:jwt:generate-keypair
5. load data fixtures : php bin/console doctrine:fixtures:load
6. to test the api please import this postman collection : Vico Api.postman_collection.json

- to start the unit testing:
1. create test db : php bin/console --env=test doctrine:database:create
2. run the migration on the test db : php bin/console --env=test doctrine:migrations:generate
3. load data fixtures : php bin/console --env=test doctrine:fixtures:load
4. run the tests : php bin/phpunit
