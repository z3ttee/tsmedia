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

#### Configure datasource
The following options require your action to setup the database connection (**NOTE:** Only MySQL is supported):
```
spring.datasource.password=ENTER_DB_PASSWORD_HERE
spring.datasource.username=ENTER_DB_USERNAME_HERE (defaults to "tsmedia")
spring.datasource.url=jdbc:mysql://<ENTER_DB_HOST_HERE>:<ENTER_DB_PORT_HERE>/<ENTER_DB_NAME_HERE>?autoReconnect=true
```

#### Configure maximum upload file size
Remember to set a higher value on the request file size when setting a limit to file sizes on uploads. <br>
The following options may require your attention if you do not want to go with the default settings.

```
spring.servlet.multipart.max-file-size=500MB            (Defaults to 1000MB)
spring.servlet.multipart.max-request-size=512MB         (Defaults to 1024MB)
```

You can use `KB`, `MB` or `GB` for the file size units.
