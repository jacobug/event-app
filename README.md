# Event app 

## Install

1. Get repository
```
git clone [repository]
```
2. Run docker
```
docker-compose up -d
```
3. Run composer
```
composer install
```
4. Build database schema
```
php bin/console doctrine:migrations:migrate
```
5. Run Symfony
```
symfony server:start
```
**_Symfony CLI required_**

### Sample data
```
php bin/console doctrine:fixtures:load
```

## Routes

__[ GET ]__
* /signup/{id}
* /list/attendees
* /list/events

__[ POST ]__
* /signup/{id}
* /send/confirmation


## Notes

* Mailcatcher webmail: http://localhost:1080

* Sending queued massages asynchronously 
``` 
php bin/console messenger:consume async
```