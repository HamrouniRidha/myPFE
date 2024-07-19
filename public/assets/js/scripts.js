/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});
function createCertificateModal(nom, prenom, sujet, date_debut, date_fin) {
    // Create a canvas element
    var canvas = document.createElement("canvas");
    canvas.width = 1414;
    canvas.height = 2000;
    var ctx = canvas.getContext("2d");

    // Load the certificate image onto the canvas
    var img = new Image();
    img.onload = function () {
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

        // Set the font and text color for the certificate text
        ctx.font = "110px Poppins";
        ctx.textAlign = "center";
        ctx.fillStyle = "black";

        // Draw the name onto the certificate
        ctx.fillText(nom, 999, 607);
        ctx.fillText(prenom, 1150, 607);

        ctx.font = "35px Montserrat Semi-Bold";
        ctx.textAlign = "left";
        ctx.fillStyle = "#72151B";

        // Draw the subject onto the certificate
        ctx.fillText(sujet, 995, 761);

        // Draw the start date onto the certificate
        ctx.fillText(date_debut, 1305, 765);

        // Draw the end date onto the certificate
        ctx.fillText(date_fin, 401, 845);

        // Draw the current date onto the certificate
        var today = new Date();
        ctx.fillText(today.toLocaleDateString(), 573, 1083);

        // Create a new image element and set its source to the canvas data URL
        var imgData = canvas.toDataURL('image/png'); // Correction: 'image/png' au lieu de 'attestaion/png'
        var imgElement = document.createElement('img');
        imgElement.src = imgData;

        // Create a new download link element and set its attributes
        var linkElement = document.createElement('a');
        linkElement.href = imgData;
        linkElement.download = 'certificate.png';

        // Append the image and link elements to the HTML document
        document.body.appendChild(imgElement);
        document.body.appendChild(linkElement);

        // Trigger a click event on the link element to download the certificate image
        linkElement.click();
        linkElement.remove();
        imgElement.remove();
    };
    img.src = "/assets/attestation/Attestation.png";
}

