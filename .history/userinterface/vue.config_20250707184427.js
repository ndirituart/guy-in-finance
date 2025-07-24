const { defineConfig } = require('@vue/cli-service')
const path = require('path');
module.exports = defineConfig({
  transpileDependencies: true,

  //to handle routing that goes wrong
  //for example when a user tries to access a page that does not exist
  configureWebpack: {
    resolve: {
      alias: {
        '@': path.resolve(__dirname, 'src'),
      },
    },
  },
  //for api calls to backend
  devServer: {
    proxy: {
      '/api': {
        target: 'http://localhost:000', // Replace with your backend server URL
        changeOrigin: true,
        pathRewrite: { '^/api': '' },
      },
    },
  },
})
