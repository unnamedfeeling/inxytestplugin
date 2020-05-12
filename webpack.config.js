const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');

const path = require('path');

module.exports = {
	entry: './assets/src/index.js',
	output: {
		path: path.resolve(__dirname, 'assets/dist'),
		filename: 'bundle.js'
	},
	
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /(node_modules)/,
				use: {
					loader: 'babel-loader',
					options: {
						sourceMap: false,
						presets: ['@babel/preset-env']
					}
				}
			},
			{
				test: /\.(sa|sc|c)ss$/,
				use: [
					{
						loader: MiniCssExtractPlugin.loader
					},
					{
						loader: "css-loader",
						options: {
							sourceMap: false,
						}
					},
					{
						loader: "postcss-loader",
						options: {
							sourceMap: false
						}
					},
					{
						loader: "sass-loader",
						options: {
							implementation: require("sass"),
						}
					}
				]
			}
		],
	},
	
	
	plugins: [
		
		new MiniCssExtractPlugin({
			filename: "bundle.css"
		}),
		new OptimizeCssAssetsPlugin({
			cssProcessor: require('cssnano'),
			cssProcessorOptions: {
				map: {
					inline: false,
				},
				discardComments: {
					removeAll: true
				},
				discardUnused: false
			},
			canPrint: true
		}),
	
	],
	mode: 'production'
};