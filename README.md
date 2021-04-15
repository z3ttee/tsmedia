![Header](/concepts/github_header.png)

# Table of Contents

1. Prerequisites
2. How to configure the service
3. 
4. 

### Prerequisites
* At least Java 8 installed
* Running mysql database
* OPTIONAL: Running SMTP server

### How to configure the service
Because the service was created using Spring Boot, you can just place a file called `application.properties` in the root directory of the application. <br>
The following options require your action to setup the database connection (**NOTE:** Only MySQL is supported):
```
spring.datasource.password=ENTER_DB_PASSWORD_HERE
spring.datasource.username=ENTER_DB_USERNAME_HERE (defaults to "tsmedia")
spring.datasource.url=jdbc:mysql://<ENTER_DB_HOST_HERE>:<ENTER_DB_PORT_HERE>/<ENTER_DB_NAME_HERE>?autoReconnect=true
```
