<h1 align="center">Welcome to MessengerSymfony 👋</h1>
<p>
</p>

> A school project in symfony. This is a like messenger / whatsapp app.

## Installation

```sh
git clone https://github.com/Weder77/MessengerSymfony.git
cd MessengerSymfony
```
Now you have to edit your .env
If you are on MacOS, edit line 32, replace with 
```
DATABASE_URL=mysql://root:root@127.0.0.1:[YOUR PORT]/messenger
```
and if you are on windows, replace with :
```
DATABASE_URL=mysql://root:@localhost:[YOUR PORT]/messenger
```

Next, follow theses instructions

```
composer install
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migration:migrate
php bin/console doctrine:fixtures:load
```

## Run server

```sh
php -S localhost:8000 -t public
```


## Authors

👤 **@Weder77 @Sheraw91**

* Github: [@Weder77](https://github.com/Weder77)
* Github: [@Sheraw91](https://github.com/Sheraw91)

## Show your support

Give a ⭐️ if this project helped you!
