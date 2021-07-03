import path from "path"

export default {
    app: {
        salt_rounds: 5,
        jwt_expiry: 1000*60*60*24*7, // 7 days
        port: 3333,
        rootDir: path.resolve(__dirname+"/../")
    },
    mysql: {
        host: 'localhost',
        port: 3306,
        dbname: 'database',
        user: 'username',
        pass: 'password',
        prefix: 'ts_'
    },
    connections: {
        discord: {
            clientId: "INSERT_CLIENT_ID",
            clientSecret: "INSERT_CLIENT_SECRET",
            scopes: ['identify'],
            authEndpoint: "https://discord.com/api/oauth2",
            endpoint: "https://discord.com/api/v6"
        }
    },
    smtp: {
        host: "mail.example.com",
        port: 587,
        user: "mail@example.com",
        pass: "mailpassword"
    }
}