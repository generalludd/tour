const gulp = require("gulp"),
  log = require("fancy-log"),
  glob = require("gulp-sass-glob"),
  concat = require("gulp-concat"),
  sourcemaps = require("gulp-sourcemaps"),
  exec = require("child_process").exec,
  { chdir } = require("process"),
  autoprefixer = require("gulp-autoprefixer"),
  watch = require("gulp-watch"),
  prettier = require("gulp-prettier"),
  cssnano = require("gulp-cssnano");

const sass = require("gulp-sass")(require('sass'));

// Only include config if exists.
let config;
try {
  config = require("./gulp.config");
} catch (error) {
  log(error);
}

/**
 * This task generates CSS from all SCSS files and compresses them down.
 */
function sassProdPublic(cb) {
  return gulp
    .src(config.public.sass.src)
    .pipe(prettier({}))
    .pipe(glob())
    .pipe(sass())
    .on("error", (error) => {
      log(error);
    })
    .pipe(
      autoprefixer({
        cascade: false,
      })
    )
    .pipe(
      cssnano({
        discardUnused: false,
      })
    )
    .on("end", () => {
      log("Minified CSS");
    })
    .pipe(gulp.dest(config.public.buildLocation.css))
    .on("end", () => {
      log("Saved CSS files");
    });
}

function sassDevPublic(cb) {
  return gulp
    .src(config.public.sass.src)
    .pipe(sourcemaps.init())
    .pipe(glob())
    .pipe(sass())
    .on("error", (error) => {
      log(error);
    })
    .on("end", () => {
      log("Compiled SASS to CSS");
    })
    .pipe(
      autoprefixer({
        cascade: false,
      })
    )
    .on("end", () => {
      log("Autoprefixed CSS");
    })
    .pipe(sourcemaps.write("./maps"))
    .on("end", () => {
      log("Created CSS map files");
    })
    .pipe(gulp.dest(config.public.buildLocation.css))
    .on("end", () => {
      log("Saved CSS files for the public theme");
    });
}

function jsDev(cb) {
  return gulp
    .src(config.public.js.src)
    .pipe(gulp.dest(config.public.buildLocation.js));
}

function sassWatch(cb) {
  return (
    // Endless stream mode
    watch(config.public.sass.src, function () {
      sassDevPublic();
    })
  );
}

exports.sassProd = sassProdPublic;
exports.sassDev = sassDevPublic;
exports.sassWatch = sassWatch;
exports.jsDev = jsDev;
