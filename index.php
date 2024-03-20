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

    /* Card container */
    .card {
      display: none;
      /* Initially hide the card */
      position: fixed;
      /* Fixed positioning to overlay content */
      top: 50%;
      /* Center the card vertically */
      left: 50%;
      /* Center the card horizontally */
      transform: translate(-50%, -50%);
      /* Center the card */
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
      padding: 20px;
      max-width: 80%;
      /* Limit card width */
      max-height: 80%;
      /* Limit card height */
      overflow-y: auto;
      /* Enable vertical scrolling if needed */
      z-index: 1000;
      /* Ensure the card appears above other content */
    }

    /* Card header */
    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    /* Card title */
    .card-header h2 {
      margin: 0;
    }

    /* Card body */
    .card-body {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      /* Responsive grid layout */
      gap: 20px;
      /* Spacing between project previews */
    }

    /* Project preview */
    .project-preview {
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 10px;
      text-align: center;
    }

    /* Project preview image */
    .project-preview img {
      max-width: 200px;
      max-height: 100px;
      /* Limit image height */
      margin-bottom: 5px;
    }

    /* Project preview name */
    .project-preview p {
      margin: 0;
      font-size: 14px;
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
      '<button id="myProjectsBtn" class="access-project">My Projects</button>' .
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


  <!-- Card to display projects -->
  <div class="card" id="myProjectsCard">
    <div class="card-header">
      <h2>My Projects</h2>
      <button id="cancelProjectsBtn">Cancel</button>
    </div>
    <div class="card-body" id="projectPreviews">
      <!-- Project previews will be dynamically added here -->
    </div>
  </div>

  <?php
  if (isset($_SESSION['User_ID'])) {
    // Output user ID as JavaScript variable
    echo '<script>
    var userId = ' . $_SESSION['User_ID'] . ';
  </script>';
  }
  ?>

  <script src="Scripts/project_load_delete.js"></script>
</body>

</html>