/*jshint node:true */
module.exports = function ( grunt ) {
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-banana-checker' );

	grunt.initConfig( {
		jshint: {
			all: [
				'**/*.js',
				'!node_modules/**',
				'!vendor/**',
				'!dist/**'
			]
		},
		banana: {
			all: 'i18n/'
		}
	} );

	grunt.registerTask( 'test', [ 'jshint', 'banana' ] );
	grunt.registerTask( 'default', 'test' );
};
