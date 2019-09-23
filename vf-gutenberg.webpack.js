const path = require('path');

const contentPath = path.resolve(__dirname, 'wp-content');
const pluginPath = path.resolve(contentPath, 'plugins');

module.exports = (env, argv) => {
  const isProduction = argv.mode === 'production';
  return {
    mode: isProduction ? 'production' : 'development',
    entry: path.resolve(pluginPath, 'vf-gutenberg/blocks/vf-blocks.jsx'),
    output: {
      path: path.resolve(pluginPath, 'vf-gutenberg/assets'),
      filename: `vf-blocks${isProduction ? '.min' : ''}.js`
    },
    externals: {
      wp: 'wp',
      '@wordpress': 'wp',
      '@wordpress/blocks': 'wp.blocks',
      '@wordpress/block-editor': 'wp.blockEditor',
      '@wordpress/components': 'wp.components',
      '@wordpress/compose': 'wp.compose',
      '@wordpress/element': 'wp.element',
      '@wordpress/i18n': 'wp.i18n',
      react: 'React',
      'react-dom': 'ReactDOM'
    },
    module: {
      rules: [
        {
          test: /\.jsx$/,
          use: {
            loader: 'babel-loader',
            options: {
              presets: [
                [
                  '@babel/preset-env',
                  {
                    debug: false,
                    useBuiltIns: 'usage',
                    corejs: 3,
                    targets: {
                      browsers: ['> 1%']
                    }
                  }
                ],
                [
                  '@babel/preset-react',
                  {
                    pragma: 'wp.element.createElement'
                  }
                ]
              ],
              plugins: []
            }
          }
        }
      ]
    },
    resolve: {
      extensions: ['.js', '.jsx', '.json']
    }
  };
};
