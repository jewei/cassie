# Cassie Lite

Cassie Lite is the light version of Cassie, certificate as a service. https://jewei.net/cassie/

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
./vendor/bin/pint

# Build.
./vendor/bin/sail npm run build
```

## Application Structure

1. https://freek.dev/1371-refactoring-to-actions
2. https://lorisleiva.com/why-i-wrote-laravel-actions
