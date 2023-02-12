# Cassie Lite

Cassie Lite is the light version of Cassie, certificate as a service.

Screenshots available at https://jewei.net/cassie/

## Technical Overview

Stack: **VILT** - Vue.js, Inertia.js, Laravel, Tailwind CSS.

Strong typing supported by PHPStan.

Development environment is managed by Laravel Sail.

Testing Framework with Pest.

PDF generations are queues processed in the background, please set [queue driver](https://laravel.com/docs/10.x/queues#driver-prerequisites) `QUEUE_CONNECTION` accordingly.

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
