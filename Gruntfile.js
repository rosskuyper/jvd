module.exports = function(grunt) {
	"use strict";
	grunt.initConfig({
		pkg: grunt.file.readJSON("package.json"),
		jshint: {
			options: {
				jshintrc: '.jshintrc'
			},
			gruntfile: ['Gruntfile.js'],
			main: ['assets/js/main.js']
		},
		uglify: {
			options: {
				mangle: true
			},
			main: {
				options: {
					sourceMap: true
				},
				files: {
					'public/js/main.min.js': 'assets/js/main.js'
				}
			}
		},
		sass: {
			options: {
				loadPath: 'scss'
			},
			main: {
				options: {
					// nested expanded compact compressed
					style: "compressed",
					compass: true
				},
				files: [
					{
						'public/css/style.css': 'assets/scss/main.scss'
					}
				]
			}
		},
		watch: {
			scripts: {
				files: 'assets/js/main.js',
				tasks: ['jshint:main', 'uglify']
			},
			styles: {
				files: ['assets/scss/*', 'assets/scss/inc/*'],
				tasks: ['sass:main']
			},
			gruntfile: {
				files: ['Gruntfile.js'],
				tasks: ['jshint:gruntfile', 'build']
			},
			livereload: {
				options: {
					livereload: true
				},
				files: ['public/css/**/*.css', 'public/img/**/*.img']
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.registerTask('default', ['watch']);

	grunt.registerTask('build', ['jshint', 'uglify', 'sass']);
};
