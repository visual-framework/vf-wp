const path = require('path');

const contentPath = path.resolve(__dirname, 'wp-content');
const assetsPath = path.resolve(contentPath, 'themes/vf-wp/assets');

module.exports = (env, argv) => {
  const isProduction = argv.mode === 'production';
  return {
    mode: isProduction ? 'production' : 'development',
    entry: {
      admin: path.resolve(assetsPath, 'js/admin.js'),
      head: path.resolve(assetsPath, 'js/head.js'),
      main: path.resolve(assetsPath, 'js/main.js')
    },
    output: {
      path: path.resolve(assetsPath, 'js'),
      filename: '[name].min.js'
    },
    module: {
      rules: [
        {
          test: /\.js$/,
          use: {
            loader: 'babel-loader',
            options: {
              presets: [
                [
                  '@babel/preset-env',
                  {
                    debug: !isProduction,
                    useBuiltIns: 'usage',
                    corejs: 3,
                    targets: {
                      browsers: ['> 1%']
                    },
                    exclude: ['es.string.replace']
                  }
                ]
              ]
            }
          }
        }
      ]
    }
  };
};
