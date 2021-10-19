const path = require( 'path' );
const { CleanWebpackPlugin } = require( 'clean-webpack-plugin' );
const CopyWebpackPlugin = require( 'copy-webpack-plugin' );
const glob = require( 'glob' );
const OptimizeJS = require( 'terser-webpack-plugin' );
const OptimizeCSS = require( 'csso-webpack-plugin' ).default;
const ExtractCSS = require( 'mini-css-extract-plugin' );
const RemoveStyleJS = require( 'webpack-remove-empty-scripts' );
const { WebpackManifestPlugin } = require( 'webpack-manifest-plugin' );
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );

const devMode = process.env.BUILD_MODEL !== 'release';
const suffix = devMode ? 'dev' : 'min';

const shared = {
	mode : devMode ? 'development' : 'production',

	cache : devMode,

	devtool : devMode ? 'source-map' : false,

	stats : devMode ? 'normal' : 'minimal',

	optimization : {
		minimize  : ! devMode,
		minimizer : [
			new OptimizeJS( { extractComments : true } ),
			new OptimizeCSS(),
		],
	},

	plugins : [
		new RemoveStyleJS(),
		new CleanWebpackPlugin( {
			cleanStaleWebpackAssets : false,
		} ),
		new ExtractCSS( {
			filename : `[name].css`,
		} ),
	],

	module : {
		rules : [
			{
				test    : /\.js$/,
				exclude : /node_modules/,
				use     : {
					loader  : 'babel-loader',
					options : {
						presets : [
							'@wordpress/default',
						],
					},
				},
			},

			{
				test : /\.css$/i,
				use  : [
					ExtractCSS.loader,
					{
						loader  : 'css-loader',
						options : {
							sourceMap : true,
							url       : false,
						},
					},
					{
						loader : 'postcss-loader',
					},
				],
			},
		],
	},
};

// Blocks.
const blocks = glob.sync( './block-library/**/+(editor|style|script).+(js|css)' );

const blockLibrary = [];

for ( const block of blocks ) {
	const fileExt = path.extname( block );
	const fileName = block.replace( './block-library/', '' ).split( '/' ).join( '-' );
	const fileType = fileExt.includes( '.js' ) ? 'script' : 'style';
	const entryName = fileName.replace( fileExt, '' );

	if ( entryName.includes( 'editor' ) ) {
		blockLibrary[ `${ entryName }-${ fileType }` ] = path.resolve( __dirname, block );
	} else {
		blockLibrary[ entryName ] = path.resolve( __dirname, block );
	}
}

const blockEditor = Object.assign( { ...shared }, {
	entry : {
		...blockLibrary,
		'block-styles' : path.resolve( __dirname, 'block-styles/index.js' ),
		'sidebars'     : path.resolve( __dirname, 'sidebars/index.js' ),
	},

	output : {
		path       : path.resolve( __dirname, 'dist' ),
		filename   : `[name].[fullhash].${ suffix }.js`,
		publicPath : 'dist/',
	},

	plugins : [
		new DependencyExtractionWebpackPlugin(),
		...shared.plugins,
		new WebpackManifestPlugin(),
	],
} );

module.exports = [ blockEditor ];
