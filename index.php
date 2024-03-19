<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "draw_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Drawing App</title>
  <link rel="stylesheet" href="Styles/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="Scripts/script.js" defer></script>
  <style>
    #myForm {
      display: none;
      position: absolute;
      top: 15%;
      left: 10%;
      width: 50%;
      height: 75%;
      background-color: rgba(200, 200, 200, 1);
      z-index: 1;
      padding: 20px;
      border-radius: 10px;
      box-sizing: border-box;
    }

    canvas {
      border: 1px solid #ddd;
      border-radius: 5px;
      margin-bottom: 10px;
    }

    #previewContainer {
      display: none;
    }

    #preview {
      width: 100%;
      height: auto;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
  </style>
</head>

<body>
  <?php
  if (isset($_SESSION['Email'])) {
    echo '<div class="loginRegisterbtn">' .
      '<button type="button" class="logreg">' . $_SESSION['Email'] . '</button>' .
      '<a href="logout.php"><button type="button" class="logreg">Logout</button></a>' .
      '</div>' .
      '<div class="container">' .
      '<section class="tools-board">' .
      '<div class="row buttons">' .
      '<button class="open-project">Open Project</button>' .
      '<button class="access-project">My Projects</button>' .
      '</div>' .
      '<div class="row">' .
      '<label class="title">Shapes</label>' .
      '<ul class="options">' .
      '<li class="option tool" id="rectangle">' .
      '<img src="icons/rectangle.svg" alt="">' .
      '<span>Rectangle</span>' .
      '</li>' .
      '<li class="option tool" id="circle">' .
      '<img src="icons/circle.svg" alt="">' .
      '<span>Circle</span>' .
      '</li>' .
      '<li class="option tool" id="triangle">' .
      '<img src="icons/triangle.svg" alt="">' .
      '<span>Triangle</span>' .
      '</li>' .
      '<li class="option">' .
      '<input type="checkbox" id="fill-color">' .
      '<label for="fill-color">Fill color</label>' .
      '</li>' .
      '</ul>' .
      '</div>' .
      '<div class="row">' .
      '<label class="title">Options</label>' .
      '<ul class="options">' .
      '<li class="option active tool" id="brush">' .
      '<img src="icons/brush.svg" alt="">' .
      '<span>Brush</span>' .
      '</li>' .
      '<li class="option tool" id="eraser">' .
      '<img src="icons/eraser.svg" alt="">' .
      '<span>Eraser</span>' .
      '</li>' .
      '<li class="option">' .
      '<input type="range" id="size-slider" min="1" max="30" value="5">' .
      '</li>' .
      '</ul>' .
      '</div>' .
      '<div class="row colors">' .
      '<label class="title">Colors</label>' .
      '<ul class="options">' .
      '<li class="option"></li>' .
      '<li class="option selected"></li>' .
      '<li class="option"></li>' .
      '<li class="option"></li>' .
      '<li class="option">' .
      '<input type="color" id="color-picker" value="#4A98F7">' .
      '</li>' .
      '</ul>' .
      '</div>' .
      '<div class="row buttons">' .
      '<button class="clear-canvas">Clear Canvas</button>' .
      '<button class="save-img">Save As Image</button>' .
      '<button id="saveProjectBtn" class="save-img">Save Project</button>' .
      '</div>' .
      '</section>' .
      '<section class="drawing-board">' .
      '<canvas></canvas>' .
      '</section>' .
      '</div>';
  } else {
    echo '<div class="loginRegisterbtn">' .
      '<a href="login.html"><button type="button" class="logreg">Login</button></a>' .
      '<a href="login.html"><button type="button" class="logreg">Register</button></a>' .
      '</div>' .
      '<div class="container">' .
      '<section class="tools-board">' .
      '<div class="row">' .
      '<label class="title">Shapes</label>' .
      '<ul class="options">' .
      '<li class="option tool" id="rectangle">' .
      '<img src="icons/rectangle.svg" alt="">' .
      '<span>Rectangle</span>' .
      '</li>' .
      '<li class="option tool" id="circle">' .
      '<img src="icons/circle.svg" alt="">' .
      '<span>Circle</span>' .
      '</li>' .
      '<li class="option tool" id="triangle">' .
      '<img src="icons/triangle.svg" alt="">' .
      '<span>Triangle</span>' .
      '</li>' .
      '<li class="option">' .
      '<input type="checkbox" id="fill-color">' .
      '<label for="fill-color">Fill color</label>' .
      '</li>' .
      '</ul>' .
      '</div>' .
      '<div class="row">' .
      '<label class="title">Options</label>' .
      '<ul class="options">' .
      '<li class="option active tool" id="brush">' .
      '<img src="icons/brush.svg" alt="">' .
      '<span>Brush</span>' .
      '</li>' .
      '<li class="option tool" id="eraser">' .
      '<img src="icons/eraser.svg" alt="">' .
      '<span>Eraser</span>' .
      '</li>' .
      '<li class="option">' .
      '<input type="range" id="size-slider" min="1" max="30" value="5">' .
      '</li>' .
      '</ul>' .
      '</div>' .
      '<div class="row colors">' .
      '<label class="title">Colors</label>' .
      '<ul class="options">' .
      '<li class="option"></li>' .
      '<li class="option selected"></li>' .
      '<li class="option"></li>' .
      '<li class="option"></li>' .
      '<li class="option">' .
      '<input type="color" id="color-picker" value="#4A98F7">' .
      '</li>' .
      '</ul>' .
      '</div>' .
      '<div class="row buttons">' .
      '<button class="clear-canvas">Clear Canvas</button>' .
      '<button class="save-img">Save As Image</button>' .
      '</div>' .
      '</section>' .
      '<section class="drawing-board">' .
      '<canvas></canvas>' .
      '</section>' .
      '</div>';
  }
  ?>

  <!-- Form section -->
  <form id="myForm" method="post" action="submit_form.php">
    <input type="hidden" id="canvasImageData" name="canvasImageData">
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['User_ID']; ?>"> <!-- Get user ID from session -->
    <div id="previewContainer">
      <label for="preview">Canvas Preview:</label><br>
      <img id="preview" src="" alt="Canvas Preview"><br>
    </div>
    <label for="projectName">Project Name:</label><br>
    <input type="text" id="projectName" name="projectName" required><br><br>
    <button type="submit">Submit</button>
    <button type="button" id="cancelBtn">Cancel</button>
  </form>

  <?php
  if (isset($_SESSION['User_ID'])) {
    // Output user ID as JavaScript variable
    echo '<script>
    var userId = ' . $_SESSION['User_ID'] . ';
  </script>';
  }
  ?>

  <!--   <div class="container">
    <section class="tools-board">
      <div class="row">
        <label class="title">Shapes</label>
        <ul class="options">
          <li class="option tool" id="rectangle">
            <img src="icons/rectangle.svg" alt="">
            <span>Rectangle</span>
          </li>
          <li class="option tool" id="circle">
            <img src="icons/circle.svg" alt="">
            <span>Circle</span>
          </li>
          <li class="option tool" id="triangle">
            <img src="icons/triangle.svg" alt="">
            <span>Triangle</span>
          </li>
          <li class="option">
            <input type="checkbox" id="fill-color">
            <label for="fill-color">Fill color</label>
          </li>
        </ul>
      </div>
      <div class="row">
        <label class="title">Options</label>
        <ul class="options">
          <li class="option active tool" id="brush">
            <img src="icons/brush.svg" alt="">
            <span>Brush</span>
          </li>
          <li class="option tool" id="eraser">
            <img src="icons/eraser.svg" alt="">
            <span>Eraser</span>
          </li>
          <li class="option">
            <input type="range" id="size-slider" min="1" max="30" value="5">
          </li>
        </ul>
      </div>
      <div class="row colors">
        <label class="title">Colors</label>
        <ul class="options">
          <li class="option"></li>
          <li class="option selected"></li>
          <li class="option"></li>
          <li class="option"></li>
          <li class="option">
            <input type="color" id="color-picker" value="#4A98F7">
          </li>
        </ul>
      </div>
      <div class="row buttons">
        <button class="clear-canvas">Clear Canvas</button>
        <button class="save-img">Save As Image</button>
      </div>
    </section>
    <section class="drawing-board">
      <canvas></canvas>
    </section>
  </div> -->

  <script>
    document.getElementById('saveProjectBtn').addEventListener('click', function() {
      document.getElementById('myForm').style.display = 'block';
      document.getElementById('previewContainer').style.display = 'block';
      //const canvas = document.getElementById('drawingCanvas');
      const imageData = canvas.toDataURL();
      document.getElementById('preview').src = imageData;
    });

    document.getElementById('cancelBtn').addEventListener('click', function() {
      document.getElementById('myForm').style.display = 'none';
      document.getElementById('previewContainer').style.display = 'none';
    });

    document.getElementById('myForm').addEventListener('submit', function(event) {
      event.preventDefault();
      const projectName = document.getElementById('projectName').value;
      const imageData = document.getElementById('preview').src;

      console.log('Project Name:', projectName);
      console.log('Canvas Image Data:', imageData);

      document.getElementById('myForm').style.display = 'none';
      document.getElementById('previewContainer').style.display = 'none';
    });

    document.getElementById('myForm').addEventListener('submit', function(event) {
      event.preventDefault();
      const projectName = document.getElementById('projectName').value;
      const imageData = document.getElementById('preview').src;

      // Update hidden input field with canvas image data
      document.getElementById('canvasImageData').value = imageData;

      // Submit the form
      this.submit();
      // Optionally, you can hide the form and preview container here
    });


    // Canvas Drawing Functionality
    /*     const canvas = document.getElementById('drawingCanvas');
        const ctx = canvas.getContext('2d');

        let isDrawing = false;
        let lastX = 0;
        let lastY = 0;

        canvas.addEventListener('mousedown', (e) => {
          isDrawing = true;
          [lastX, lastY] = [e.offsetX, e.offsetY];
        });

        canvas.addEventListener('mousemove', (e) => {
          if (!isDrawing) return;
          ctx.beginPath();
          ctx.moveTo(lastX, lastY);
          ctx.lineTo(e.offsetX, e.offsetY);
          ctx.stroke();
          [lastX, lastY] = [e.offsetX, e.offsetY];
        });

        canvas.addEventListener('mouseup', () => {
          isDrawing = false;
        });

        canvas.addEventListener('mouseout', () => {
          isDrawing = false;
        }); */

    // Check if userId variable is defined
    if (typeof userId !== 'undefined') {
      // Log user ID to the console
      console.log('User ID:', userId);
    } else {
      console.log('User ID not found.');
    }
  </script>
</body>

</html>