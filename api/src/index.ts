import { wrapConsole } from 'alliance-rest/lib/logger/logger';

import Express from 'express'
import bodyParser from "body-parser";
import cors from "cors"

import { TSRouter } from "alliance-rest";
import { Database, SyncStrategy } from "alliance-rest/lib/database/database";

import { Webserver } from './webserver'
import { ROUTES } from './router/routes';
import { Invite } from './models/category';
import config from './config/config';

import { ErrorHandlerImpl } from './error/errorHandler';

import { SecurClient, SecurUserDetailsService } from "secur-node"

wrapConsole();

const expressApp = Express();
const webserver = Webserver.getInstance();

expressApp.use(cors())
expressApp.use(bodyParser.json());
expressApp.use(bodyParser.urlencoded({ extended: true }));

// Register webserver events
webserver.on("boot", () => {
    console.info("Starting nodejs webserver...")
})
webserver.on("ready", () => {
    console.info("NodeJS Webserver is ready.")

    if(webserver.isHttps()) {
        console.info("Running in secure mode. (SSL)")
    } else {
        console.warn("Running in insecure mode. (SSL not enabled)")
        console.warn("This may be because of a missing ssl certificate and results in non-encrypted requests.")
    }
})

webserver.boot(expressApp);

SecurClient.init({
    protocol: "http",
    host: "localhost",
    port: 3333,
    path: "/"
})

// Setup database and register models
Database.createInstance({
    host: config.mysql.host,
    port: config.mysql.port,
    database: config.mysql.dbname,
    password: config.mysql.pass,
    user: config.mysql.user,
    logging: false,
    syncStrategy: SyncStrategy.STRATEGY_ALTER
}, [
    Invite
], async () => {
    // Setup default database entries
    console.info("Setting up default entries...")

    console.info("Default entries successfully setup.")
});

// Initialize Router and set authorization service
const router = TSRouter.createInstance(expressApp, ROUTES);
router.setUserDetailsService(new SecurUserDetailsService());
router.setErrorHandler(new ErrorHandlerImpl());