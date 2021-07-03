import http from 'http'
import https from 'https'
import fs from 'fs'
import path from 'path'

import config from './config/config'

export class Webserver {
    static instance: Webserver = undefined

    private protocol: Webserver.Protocol = Webserver.Protocol.HTTP
    private certificateFile: string = path.resolve(__dirname)+"/ssl/fullchain.pem"
    private privkeyFile: string = path.resolve(__dirname)+"/ssl/privkey.pem"

    private registeredEvents: object = {}

    /**
     * Boot server up to listen to requests
     */
    public async boot(express) {
        this.emitEvent('boot')

        let port: number = config.app.port

        if(fs.existsSync(this.privkeyFile) && fs.existsSync(this.certificateFile)) {
            var privateKey  = fs.readFileSync(this.privkeyFile);
            var certificate = fs.readFileSync(this.certificateFile);
    
            const credentials = {key: privateKey, cert: certificate}
            const httpsServer = https.createServer(credentials, express)
    
            //socketio.attach(httpsServer, options)
            
            httpsServer.listen(port, () => {
                console.info('Listening on port '+port)
                this.emitEvent('ready')
            })
        } else {
            const httpServer = http.createServer(express)
            //socketio.attach(httpServer, options)
    
            httpServer.listen(port, () => {
                console.info('Listening on port '+port)
                this.emitEvent('ready')
            })
        }
    }

    /**
     * Check if protocol is secure
     */
    public isHttps(): boolean {
        return this.protocol === 'https'
    }

    /**
     * Get webserver's active protocol
     */
    public getProtocol(): Webserver.Protocol {
        return this.protocol
    }

    /**
     * Add event listener
     * @param eventName Name of the event
     * @param callback Function to execute on event
     */
    public on(eventName: string, callback: Function) {
        if(!this.registeredEvents.hasOwnProperty(eventName)) {
            this.registeredEvents[eventName] = []
        }
        
        this.registeredEvents[eventName].push({ callback })
    }

    /**
     * Remove event listener
     * @param eventName Name of the event
     * @param callback Function that should be removed
     */
    public off(eventName: string, callback: Function) {
        if(this.registeredEvents.hasOwnProperty(eventName)) {
            this.registeredEvents[eventName].find((event, index) => {
                if(event.callback === callback) {
                    this.registeredEvents[eventName].splice(index, 1)
                    return event
                }
            })
        }
    }

    /**
     * Emit an event to trigger registered listeners
     * @param eventName Name of the event
     */
    private emitEvent(eventName: string) {
        if(this.registeredEvents.hasOwnProperty(eventName)) {
            for(let event of this.registeredEvents[eventName]) {
                if(event.callback) {
                    event.callback()
                }
            }
        }
    }

    /**
     * Get global instance of webserver
     */
    static getInstance(){
        if(!this.instance) {
            this.instance = new Webserver()
        }
        return this.instance
    }

}
export namespace Webserver {
    export enum Protocol {
        HTTP = "http",
        HTTPS = "https"
    }
}