"use strict";

const gulp = require("gulp");
const browserSync = require("browser-sync").create();
const environments = require("gulp-environments");

const sass = require("gulp-sass")(require("sass"));
const autoprefixer = require("gulp-autoprefixer");
const cleanCSS = require("gulp-clean-css");

const babel = require("gulp-babel");
const terser = require("gulp-terser");
const {createGulpEsbuild} = require("gulp-esbuild")

const esbuild = createGulpEsbuild({
    incremental: false,
    piping: true
});

const development = environments.development;
const production = environments.production;

const paths = {
    php: {
        watch: [
            "./application/**/*.php",
            "./application/View/*.twig",
            "./application/View/**/*.twig"
        ],
    },
    js: {
        src: "./js/**/*.js",
        watch: "./js/**/*.js",
        dest: "./public/dist/"
    },
    scss: {
        entrypoints: ["./scss/main.scss"],
        watch: ["./scss/*.scss", "./scss/**/*.scss"],
        dest: "./public/dist/"
    }
};

function scss() {
    return gulp.src(paths.scss.entrypoints)
        .pipe(sass.sync({
            outputStyle: production() ? "compressed" : "expanded",
            sourceMap: development()
        })
        .on("error", sass.logError))
        .pipe(autoprefixer({
            overrideBrowsersList: ["last 2 versions"],
            cascade: false
        }))
        .pipe(cleanCSS({
            level: {
                1: {
                    all: production(),
                    specialComments: 0
                },
                2: {
                    all: production()
                }
            }
        }))
        .pipe(gulp.dest(paths.scss.dest))
        .pipe(development(browserSync.stream()));
}

function js() {
    return gulp.src(paths.js.src)
        .pipe(babel({
            presets: ["@babel/env"]
        }))
        .pipe(esbuild({
            entryNames: "[dir]",
            bundle: true,
            treeShaking: production(),
            legalComments: "none"
        }))
        .pipe(terser({
            mangle: {
                toplevel: true
            }
        }))
        .pipe(gulp.dest(paths.js.dest))
}

function reload(cb) {
    browserSync.reload();
    cb();
}

function watch() {
    browserSync.init({
        proxy: "localhost:8080",
        open: false
    });

    gulp.watch(paths.scss.watch, scss);
    gulp.watch(paths.js.watch, js);
    gulp.watch(paths.php.watch, reload);
}

exports.js = js;
exports.scss = scss;
exports.watch = watch;

gulp.task("default", gulp.series(scss, js, watch));
gulp.task("build", gulp.parallel(scss, js));
