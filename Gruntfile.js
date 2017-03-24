module.exports = function (grunt) {
  grunt.initConfig({
    copy: {
      images: {
        expand: true,
        flatten: true,
        src: [
          'src/images/**/*.ico',
          'src/images/**/*.png',
          'src/images/**/*.svg',
          'node_modules/bootstrap-colorpicker/dist/img/bootstrap-colorpicker/*.png'
        ],
        dest: 'public/images/'
      }
    },
    uglify: {
      options: {
          compress: true,
          mangle: true,
          sourceMap: true
      },
      js: {
          src: [
            'node_modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js',
            'src/script/site.js'
          ],
          dest: 'public/js/site.min.js'
      }
    },
    postcss: {
      dist: {
        src: 'public/css/site.css'
      },
      options: {
        map: {
          inline: false,
          annotation: 'public/css'
        },
        processors: [
          require('autoprefixer')({
            browsers: 'last 2 versions'
          }),
          require('postcss-uncss')({
            html: ['src/index.html'],
            ignore: [/.*colorpicker.*/, '.dropdown-menu']
          }),
          require('cssnano')({
            discardComments: {
              removeAll: true
            }
          })
        ]
      }
    },
    sass: {
      dist: {
        files: {
          'public/css/site.css': 'src/style/main.scss'
        },
        options: {
          includePaths: [
            'node_modules/bootstrap/scss',
            'node_modules/bootstrap-colorpicker/src/sass'
          ]
        }
      }
    },
    watch: {
      css: {
        files: ['src/style/*', 'public/index.html'],
        tasks: ['css'],
        options: {
          livereload: true,
        }
      },
      configFiles: {
        files: ['Gruntfile.js'],
        options: {
          reload: true
        }
      }
    }
  });

  /* Load modules */
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-postcss');
  grunt.loadNpmTasks('grunt-uncss');

  /* Configure tasks */
  grunt.registerTask('js', ['uglify:js']);
  grunt.registerTask('css', ['sass:dist', 'postcss:dist']);
  grunt.registerTask('default', ['js', 'css', 'copy:images']);
};
