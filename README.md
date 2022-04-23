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

### How to export content

1. Create module, see `modules/custom/beetroot_content/beetroot_content.info.yaml`
2. Change entity types and uuid's in module info yaml.
3. Run `drush default-content:export-module beetroot_content -y`

```
 drush default-content:export-references taxonomy_term 3 --folder modules/custom/beetroot_content/content
```
