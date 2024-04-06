About this directory:
=====================

By default, this application is configured to load all configs in
`./config/autoload/{,*.}{global,local}.php`. Doing this provides a
location for a developer to drop in configuration override files provided by
modules, as well as cleanly provide individual, application-wide config files
for things like database connections, etc.

To use connection settings from environment variables make a copy of local.environment 
and rename it to local.php
Default environment variables are:
DB_HOST - address of the MySQL server
DB_NAME - database on the server
DB_USER - user account that have all necessary rights on the database
DB_PASSWORD - password for the user

If you want to use constant values instead of env variables, make a copy of local.environment,
rename it to local.php and replace all getenv() calls with respective values.
