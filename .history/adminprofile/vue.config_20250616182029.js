const { defineConfig } = require('@vue/cli-service')
const path = require('path');
module.exports = defineConfig({
  transpileDependencies: true,

  // to handle routing that goes wrong
  // for example when a user tries to access a page that does not exist
  configureWebpack: {
    resolve: {
      alias: {
        '@': path.resolve(__dirname, 'src'),
      },
    },

    // to have this go to a different endpoint from normal app
  },
  // CRITICAL CHANGE HERE: Set publicPath to '/admin/' for both environments
  publicPath: '/admin/', 
  // for api calls to backend
  devServer: {
    proxy: {
      '/api': {
        target: 'http://localhost:5000', // Replace with your backend server URL
        changeOrigin: true,
        pathRewrite: { '^/api': '' },
      },
    },
    // Optional: If you want to automatically open the browser at /admin/
    // open: '/admin/', 
  },
})

