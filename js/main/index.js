// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

"use strict";

const $nav = document.getElementById("nav");

function toggleNav() {
    $nav.classList.toggle("open");
}

function closeNav() {
    $nav.classList.remove("open");
}

const $openMenu = document.getElementById("open-menu");
const $closeMenu = document.getElementById("close-menu");

$openMenu.onclick = toggleNav;
$closeMenu.onclick = toggleNav;