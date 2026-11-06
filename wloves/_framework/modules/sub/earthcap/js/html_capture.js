function EarthCap(elementId, filename = 'image-name.png', allowTaint = false, useCORS = false) {
  var options = {};

  if (useCORS) {
    options.useCORS = true;
  }

  if (allowTaint) {
    options.allowTaint = true;
  }

  html2canvas(document.getElementById(elementId), options).then(function (canvas) {
    // Convert the canvas to a data URL
    var dataURL = canvas.toDataURL();

    // Create a new image element
    var img = document.createElement("img");

    // Set the source of the image to the data URL
    img.src = dataURL;

    // Append the image to the document
    document.body.appendChild(img);

    // Create a download link
    var downloadLink = document.createElement("a");
    downloadLink.href = dataURL;
    downloadLink.download = filename;

    // Programmatically click the download link to download the image
    downloadLink.click();

    document.body.removeChild(img);

    // Remove the image element
    img.remove();
  });
}


function EarthsCap(elementId, filename = 'image-name.png', allowTaint = false, useCORS = false) {
  var options = {};

  if (useCORS) {
    options.useCORS = true;
  }

  if (allowTaint) {
    options.allowTaint = true;
  }

  html2canvas(document.getElementById(elementId), options).then(function (canvas) {
    var dataURL = canvas.toDataURL();

    var img = document.createElement("img");

    img.src = dataURL;

    // Append the image to the specified <div> element
    var divElement = document.getElementById('preview-capture');

    // Remove the previous image, if any
    var previousImg = divElement.querySelector('img');
    if (previousImg) {
      divElement.removeChild(previousImg);
    }

    divElement.appendChild(img);
  });
}