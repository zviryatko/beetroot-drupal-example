# Drupal example project

An example project for Beetroot.Academy Drupal course.

## Setup

```bash
cp .env.dist .env
docker-compose up -d
docker-compose exec php composer install
```

Open http://drupal.localhost/install.php and install the website.

**_NOTE_**:
Database settings are stored in `web/sites/default/settings.php` but it uses env variables so no need to override, just change the `.env` file instead.
