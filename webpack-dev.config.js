const MiniCssExtractPlugin = require("mini-css-extract-plugin");

// Webpack uses this to work with directories
const path = require('path');

// This is main configuration object.
// Here you write different options and tell Webpack what to do
module.exports = {
	entry: {
		inxytestFront: './assets/src/front/index.js',
		inxytestAdmin: './assets/src/admin/admin.js',
	},
	output: {
		path: path.resolve(__dirname, 'assets/dist'),
		filename: '[name].js'
	},
	
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /(node_modules)/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env']
					}
				}
			},
			{
				// Apply rule for .sass, .scss or .css files
				test: /\.(sa|sc|c)ss$/,
				
				// Set loaders to transform files.
				// Loaders are applying from right to left(!)
				// The first loader will be applied after others
				use: [
					{
						loader: MiniCssExtractPlugin.loader
					},
					{
						loader: "css-loader",
					},
					{
						loader: "postcss-loader",
					},
					{
						loader: "sass-loader",
						options: {
							implementation: require("sass")
						}
					}
				]
			}
		],
	},
	
	
	plugins: [
		new MiniCssExtractPlugin({
			filename: "[name].css"
		})
	
	],
	mode: 'development'
};