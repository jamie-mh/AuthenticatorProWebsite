// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

"use strict";

import QRCode from "qrcode";

const $form = document.getElementById("qrcode-form");
const $qrCode = document.getElementById("qrcode-canvas");

$form.addEventListener("submit", async function (event) {
    event.preventDefault();
    const $textarea = $form.elements.namedItem("text");
    const text = $textarea.value.trim();

    try {
        await QRCode.toCanvas($qrCode, text, {
            errorCorrectionLevel: "Q",
            scale: 6
        });
    } catch (e) {
        alert(e);
        console.error(e);
    }
}, true);
