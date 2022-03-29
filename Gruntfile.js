module.exports = function(grunt) {
 
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    makepot: {
      target: {
        options: {
          include: [
            'countdown-widget.php',
            'countdown-util.php',
            'countdown-shortcodes.php',
            'countdown-options-page.php',
            'countdown-options.php',
          ],
          type: 'wp-plugin'
        }
      }
    },
    uglify: {
      dist: {
        options: {
          banner: '/*! <%= pkg.name %> <%= pkg.version %> countdown.min.js <%= grunt.template.today("yyyy-mm-dd h:MM:ss TT") %> */\n',
          report: 'gzip'
        },
        files: {
          'assets/js/countdown.min.js' : [
            'assets/js/countdown.js',
          ]
        }
      },
    },
  });

  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-wp-i18n');

  grunt.registerTask('default', [
    'uglify:dist',
    'makepot',
  ]);

};