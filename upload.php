<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Image from Webcam</title>

    <style type="text/css">
        video {         display: block; margin: 0 auto; border: 10px solid #ccc; }
        .button {       margin: 10px auto; padding: 10px; background: #ccc; color: white; text-align: center; width: 200px; cursor: pointer; }
        #container,
        #filters {   	width: 640px; margin: 0 auto; text-align: center; }
        span { 		text-align: center; cursor: pointer; color: silver; text-decoration: underline; }
    </style>
</head>
<body>
    <video id="video" width="640" height="480" autoplay></video>
    <p class="button" id="photo">Take Photo</p>

    <p id="filters">
    	Apply Filter:
	    <span id="filter-none">None</span>
    	<span id="filter-sepia">Sepia</span>
    	<span id="filter-grayscale">Grayscale</span>
    	<span id="filter-grayscale">Brightness</span>
    	<span id="filter-grayscale">Contrast</span>
    </p>
    <div id="container"></div>


    <script>
    	// HTML5 Camera: http://davidwalsh.name/browser-camera
    	// HTML5 Canvas Image: http://davidwalsh.name/convert-canvas-image
    	// CSS3 Image Filters: https://coderwall.com/p/ruaoig
        var WebCamVideo = function(video, callback) {
            this.videoElement = video;

            var self = this;
            if (navigator.getUserMedia) {
                navigator.getUserMedia({ video: true }, function(stream) {
                    self.videoElement.src = stream;
                    self.videoElement.play();
                }, callback);
            } else if (navigator.webkitGetUserMedia) {
                navigator.webkitGetUserMedia({ video: true }, function(stream) {
                    self.videoElement.src = window.webkitURL.createObjectURL(stream);
                    self.videoElement.play();
                }, callback);
            }
        };

        WebCamVideo.prototype.getImage = function(type, width, height) {
            var type   = type || 'image/png',
                width  = width || this.videoElement.width,
                height = height || this.videoElement.height;

            var canvas  = document.createElement('canvas'),
                context = canvas.getContext('2d');

            canvas.width  = width;
            canvas.height = height;

            context.drawImage(this.videoElement, 0, 0, width, height);

            var image = new Image;
            image.src = canvas.toDataURL(type);
            return image;
        };


        window.addEventListener('DOMContentLoaded', function() {
            // Grab video and button elements.
            var video     = document.getElementById('video'),
                photo     = document.getElementById('photo'),
                container = document.getElementById('container');

            // Initliase the webcam video stream.
            var webcam = new WebCamVideo(video, function(error) {
                console.alert('Video Capture Error: ' + error.code);
            });

            // Capture an image from the webcam video stream.
            photo.addEventListener('click', function() {
                var image = webcam.getImage();
                image.style.width = '50%';

                if (container.children.length > 0) {
                    container.insertBefore(image, container.firstChild);
                } else {
                    container.appendChild(image);
                }
            });

            // Apply CSS filters to images.
            var filters = document.querySelectorAll('#filters > span');
            [].forEach.call(filters, function(span) {
            	span.addEventListener('click', function() {
            		var filter = span.innerHTML;
            			style  = '';

            		switch (filter) {
            			case 'Sepia':
            				style = 'sepia(100%)';
            				break;

            			case 'Grayscale':
            				style = 'grayscale(100%)';
            				break;

            			case 'Brightness':
            				style = 'brightness(0.35)';
            				break;

            			case 'Contrast':
            				style = 'contrast(140%)';
            				break;

            			default:
            				style = '';
            		}

            		var images = document.querySelectorAll('#container > img');
            		[].forEach.call(images, function(img) {
            			console.log(img);
            			img.style.webkitFilter = style;
            		});
            	});
            });
        }, false);
    </script>
</body>
</html>
