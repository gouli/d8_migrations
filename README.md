# d8_migrations
These modules are used to migrate HTML into Drupal nodes.

Installation
------------------------------------------------------------------------------------
1) Download both modules.
2) Install both modules and their dependencies.
3) Place your HTML pages into migrate_html > data directory.
4) Run 'drush migrate-import html' to import the html into node pages.
5) Run 'drush migrate-rollback html' to rollback html migration.

Modules
------------------------------------------------------------------------------------
migrate_html : This consists of HTML pages in "data" folder and Migration configuration in "config/install" folder.
htmlparser: This consists of HTML parser plugin which parses HTML and converts them to text to be added on Nodes.
