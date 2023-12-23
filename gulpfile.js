// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

"use strict";

import gulp from "gulp";
import environments from "gulp-environments";
import hash from "gulp-hash";
import browserSync from "browser-sync";

import gulpSass from "gulp-sass"
import * as dartSass from "sass";
import cleanCSS from "gulp-clean-css"
import autoprefixer from "gulp-autoprefixer";

import babel from "gulp-babel";
import terser from "gulp-terser"
import {createGulpEsbuild} from "gulp-esbuild";

const esbuild = createGulpEsbuild({
    incremental: false,
    piping: true
});

const sass = gulpSass(dartSass);

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
        .pipe(production(hash()))
        .pipe(gulp.dest(paths.scss.dest))
        .pipe(production(hash.manifest("application/assets.json")))
        .pipe(production(gulp.dest(".")))
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
        .pipe(production(hash()))
        .pipe(gulp.dest(paths.js.dest))
        .pipe(production(hash.manifest("application/assets.json")))
        .pipe(production(gulp.dest(".")))
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

gulp.task("default", gulp.series(scss, js, watch));
gulp.task("build", gulp.parallel(scss, js));
