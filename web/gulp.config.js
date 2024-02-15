// gulp.config.js
module.exports = {
  public: {
    sass: {
      src: ["./source/scss/**/*.scss"],
    },
    js: {
      src: [ "./source/js/**/*.js"]
    },
    buildLocation: {
      css: "./css/",
      js: "./js/",
    },
  },
};
