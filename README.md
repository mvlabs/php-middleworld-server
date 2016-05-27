Simple docker-compose project that provides a php7/postgresql environment:



PREREQUISITES:

a working docker environment



USAGE:



1) pull the repository locally

2) cd into the project root

3) docker-compose up



WHAT IS PROVIDED:



there is a php7 webserver serving content from the src/ directory and responding to http://localhost

there is a postgresql database responding to localhost:5432 with username mvlabs and password mvlabs with a database ready to use also called mvlabs.

there is a phppgadmin running on port 8080 (http://localhost:8080) to allow for easy mainteinance.
