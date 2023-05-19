"use strict";

import QRCode from "qrcode";

const $form = document.getElementById("qrcode-form");
const $qrCode = document.getElementById("qrcode-canvas");

$form.addEventListener("submit", async function (event) {
    event.preventDefault();
    const $textarea = $form.elements.namedItem("text");

    try {
        await QRCode.toCanvas($qrCode, $textarea.value, {
            errorCorrectionLevel: "Q",
            scale: 6
        });
    } catch (e) {
        alert(e);
        console.error(e);
    }
}, true);
