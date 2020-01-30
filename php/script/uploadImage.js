$(document).ready(function() {
  var boxes;
  var gaugeType;
  var coordinates;
  // whenever a new file is selected, do all the things
  $("#imageInput").change(function(e) {
    var file = e.originalEvent.srcElement.files[0];
    boxes = [];

    paintCanvas(file);
    initializeDrawing();
    prepareUpload(file);
  });

  // paint the image on the canvas (this does NOT append an img!)
  function paintCanvas(file) {
    var canvas = document.getElementById('displayimage');
    var imgCanvas = new Image();
    var reader = new FileReader();

    reader.onloadend = function() {
      imgCanvas.src = reader.result
    }

    reader.readAsDataURL(file);
    imgCanvas.onload = function() {
      drawImage(imgCanvas);
    }

    function drawImage(imgCanvas) {
      var ctx = canvas.getContext('2d');
      ctx.drawImage(imgCanvas, 0, 0, 1000, 600);
    }
  }

  // tell the page to draw rectangles on the canvas when the user drags
  function initializeDrawing() {
    var isDown;
    var start;
    var end;
    var canvasEl = document.getElementById("displayimage");
    var draw = canvasEl.getContext("2d");
    draw.lineWidth = "2";
    draw.strokeStyle = "red";

    // when the mouse is pressed, track its position
    $("#displayimage").mousedown(function(e) {
      isDown = true;
      start = getMousePos(canvasEl, e);
      end = getMousePos(canvasEl, e);
      e.preventDefault();
    });

    // when it's released, draw a rectangle
    $("#displayimage").mouseup(function(e) {
      if (!isDown) return;
      var end = getMousePos(canvasEl, e);
      var h = end.y - start.y;
      var w = end.x - start.x;

      draw.beginPath();
      draw.rect(start.x, start.y, w, h);
      draw.stroke();
      draw.closePath();

      // create an element so we know it's working
      $('#coordinates1').html('current: ' + start.x + ', ' + start.y + '<br/>last: ' + end.x + ', ' + end.y);

      // package the data so we can send it to the server
      // convert the coordinates to percentages so resolution and aspect ratio don't matter

      var startXPercent = start.x / canvasEl.width;
      var startYPercent = start.y / canvasEl.height;
      var endXPercent = end.x / canvasEl.width;
      var endYPercent = end.y / canvasEl.height;

      coordinates = [startXPercent, startYPercent, endXPercent, endYPercent];
      gaugeType = "";
      $("#myNav").css("width", "100%");
    });

    function getMousePos(canvas, evt) {
      var rect = canvas.getBoundingClientRect();
      return {
        x: Math.floor(evt.clientX - rect.left),
        y: Math.floor(evt.clientY - rect.top)
      };
    }
  }
  $(".mySelect").change(function() {
    gaugeType=$('.mySelect option:selected').val();
  });

  $("#closeNav").click(function() {
    $("#myNav").css("width", "0%");
    gaugeType=$('.mySelect option:selected').val();
    boxes.push([coordinates, gaugeType]);
  });

  // tell the upload button to asynchronously send the image to the upload php script
  function prepareUpload(file) {
    var uploadButton = $('#uploadButton');
    uploadButton.css('display', 'block');

    // clear any previous click handlers and add a new one for the new image
    uploadButton.off('click');
    uploadButton.click(function() {

      // format the image data to be transmitted
      var formData = new FormData();
      formData.append('image', file);

      // this fancy ajax call sends a whole file!
      // WARNING: xampp/php/php.ini must have max file upload size high enough!
      var taskId;
      $.ajax({
        url: 'action/uploadImage.php',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(data) {


          // now that the image is uploaded, send up the user-defined gauge data
          $.post('action/uploadGaugeData.php', {
              data: boxes,
              subdirName: data
            },
            function(data) {
              taskId = data; // return and set the username
            }
          );
        }
      });
      $.ajax({
        url: 'runPython.php',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(data) {
          intervalId = setInterval(function() {
            checkForOutput(taskId);
          }, 2500);

        }
      });

      // also watch for the return data!

      // send a request to the server to see if any results are in
      function checkForOutput(taskId) {
        $.post('action/downloadResults.php', {
            'taskId': taskId
          },
          function(data, status) {
            if (data != 'no results') {
              alert('mission success!');

              clearInterval(intervalId);

              var resultsForm = $('#resultsForm');
              $('#results').val(data);
              resultsForm.submit();
            } else {
              alert('nothing found just yet');
            }
          });
      }
    });
  }
});
