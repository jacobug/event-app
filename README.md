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

### Sample data
```
php bin/console doctrine:fixtures:load
```

## Routes

__[ GET ]__
/signup
/list/attendees
/list/events

__[ POST ]__
/signup
/send/confirmation/user


## Notes

* Mailcatcher webmail: http://localhost:1080

* Consume queued massages
``` 
php bin/console messenger:consume async
```