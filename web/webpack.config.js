const path = require('path');
const fs = require('fs');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

// Function to dynamically generate entry points
function generateSassEntryPoints() {
	const directoryPath = path.join(__dirname, 'source/scss');
	const entryPoints = {};

	fs.readdirSync(directoryPath).forEach(file => {
		if (file.endsWith('.scss')) {
			const basename = path.basename(file, '.scss');
			entryPoints[basename] = path.resolve(directoryPath, file);
		}
	});

	return entryPoints;
}

module.exports = (env, argv) => {
	const isDevelopment = argv.mode === 'development';

	return {
		mode: isDevelopment ? 'development' : 'production',
		entry: generateSassEntryPoints(),
		output: {
			path: path.resolve(__dirname, 'css'),
		},
		devtool: isDevelopment ? 'source-map' : false,
		module: {
			rules: [
				{
					test: /\.scss$/,
					use: [
						MiniCssExtractPlugin.loader,
						'css-loader',
						{
							loader: 'sass-loader',
							options: {
								sourceMap: isDevelopment,
							},
						},
					],
				},
			],
		},
		plugins: [
			new MiniCssExtractPlugin({
				filename: '[name].css',
			}),
		],
	};
};
