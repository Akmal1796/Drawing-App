<?php
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
</head>

<body>
  <?php
  session_start();
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
      '<button class="save-img">Save Project</button>' .
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
</body>

</html>