module.exports = {
    devServer: {
        port: 80,
        disableHostCheck: true
    },
    css: {
        loaderOptions: {
            sass: {
                //prependData: `@import "@/assets/scss/_variables.scss";`
            }
        }
    }
};