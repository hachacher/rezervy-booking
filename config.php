<?php 

/** Use Railway environment variables if available, otherwise fallback to localhost **/
define("rzvy_HOSTNAME", getenv("DB_HOST") ?: "localhost");
define("rzvy_USERNAME", getenv("DB_USER") ?: "root");
define("rzvy_PASSWORD", getenv("DB_PASSWORD") ?: "");
define("rzvy_DATABASE", getenv("DB_DATABASE") ?: "rezervy");
define("rzvy_PORT", getenv("DB_PORT") ?: 3306);