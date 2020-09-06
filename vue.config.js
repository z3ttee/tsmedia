const config = require("./src/config.json")

const isDev = process.env.NODE_ENV === 'development'
const publicPath = (isDev ? '/' : config.router.base)

module.exports = {
    publicPath
}