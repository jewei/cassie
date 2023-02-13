# Cassie Lite

Easy and simple app to manage and generate PDF certificates. Screenshots available at https://jewei.net/cassie/


## Technical Overview

* Tech stack: **VILT** - Vue.js, Inertia.js, Laravel and Tailwind CSS.
* Strong static typing supported by PHPStan.
* Development environment is managed by Laravel Sail.
* Testing Framework covered by Pest.
* PDF generations are async tasks running by queue. 


## Setup

```
# Create environment variables.
cp .env.example .env

#Install dependencies
./vendor/bin/sail composer install

# Run the docker container.
./vendor/bin/sail up -d

# Seed App key.
./vendor/bin/sail artisan key:generate

# Run migration.
./vendor/bin/sail artisan migrate:fresh --seed
```


### Queues Setting

To async process queues in the background:

1. Set [queue driver](https://laravel.com/docs/10.x/queues#driver-prerequisites) `QUEUE_CONNECTION` accordingly.
2. Set and [run the queue worker](https://laravel.com/docs/10.x/queues#running-the-queue-worker).

To not use async feature, set `QUEUE_CONNECTION` to `sync`.


## Development

```
# Run dev.
./vendor/bin/sail npm run dev

# Static analysis.
./vendor/bin/phpstan analyse --memory-limit=2G

# Tests.
./vendor/bin/sail pest

# Fix coding styles.
./vendor/bin/sail pint

# Build.
./vendor/bin/sail npm run build
```


## Application Structure

1. https://freek.dev/1371-refactoring-to-actions
2. https://lorisleiva.com/why-i-wrote-laravel-actions
