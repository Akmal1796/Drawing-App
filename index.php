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

    .main {
      margin: 50px;
      background-color: lightslategray;
      padding: 3px 0;
      border-radius: 3px;
      position: fixed;
      top: -45px;
      left: 200px;
    }

    .nav-bar {
      word-spacing: 40px;
      font-size: 20px;
      font-family: arial;
    }

    .nav-bar ul li a {
      text-decoration: none;
      padding: 5px;
      color: white;
    }

    .nav-bar ul li {
      display: inline-block;
      -webkit-transition: background-color 1s, color 1s;
    }

    .nav-bar ul li:hover {
      background-color: #e91e63;
      color: white;
    }

    .nav-bar ul .active {
      background-color: #e91e63;
      border-radius: 2px;
      color: white;
    }

    .form-submit-btn {
      background-color: #6DD400;
      color: white;
      border: none;
      padding: 5px;
      border-radius: 10px;
      margin-right: 5px;
      cursor: pointer;
    }

    .form-cancel-btn {
      background-color: #E02020;
      color: white;
      border: none;
      border-radius: 10px;
      padding: 5px;
      border: 10px;
      cursor: pointer;
    }

    .card-cancel-btn {
      background-color: royalblue;
      color: white;
      border: none;
      padding: 5px;
      border-radius: 10px;
      cursor: pointer;
    }

    .delete-project {
      background-color: crimson;
      color: white;
      border: none;
      padding: 5px;
      border-radius: 10px;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <div class="main">
    <nav class="nav-bar">
      <ul>
        <li><a href="" class="active"> Home </a></li>
        <li><a href="about.html" class=""> About </a></li>
        <li><a href="contact.html" class=""> Contact </a></li>
      </ul>
    </nav>
  </div>
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
    <button type="submit" class="form-submit-btn">Submit</button>
    <button type="button" id="cancelBtn" class="form-cancel-btn">Cancel</button>
  </form>


  <!-- Card to display projects -->
  <div class="card" id="myProjectsCard">
    <div class="card-header">
      <h2>My Projects</h2>
      <button id="cancelProjectsBtn" class="card-cancel-btn">Cancel</button>
    </div>
    <div class="card-body" id="projectPreviews">
      <!-- Project previews will be dynamically added here -->
    </div>
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

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      const submitSuccess = urlParams.get('submit_success');
      const submitError = urlParams.get('submit_error');

      if (submitSuccess) {
        alert('Project submitted successfully!');
      } else if (submitError) {
        alert(submitError);
      }
    });


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
    });

    // Check if userId variable is defined
    if (typeof userId !== 'undefined') {
      // Log user ID to the console
      console.log('User ID:', userId);
    } else {
      console.log('User ID not found.');
    }

    // JavaScript code for the drawing app functionality and project display
    document.addEventListener('DOMContentLoaded', function() {
      const myProjectsBtn = document.getElementById('myProjectsBtn');
      const myProjectsCard = document.getElementById('myProjectsCard');
      const projectPreviews = document.getElementById('projectPreviews');
      const cancelProjectsBtn = document.getElementById('cancelProjectsBtn');

      myProjectsBtn.addEventListener('click', function() {
        // Show My Projects card
        myProjectsCard.style.display = 'block';
        // Fetch projects associated with the current user ID from the server
        fetchProjects();
      });

      cancelProjectsBtn.addEventListener('click', function() {
        // Hide My Projects card
        myProjectsCard.style.display = 'none';
      });

      function fetchProjects() {
        // Fetch projects associated with the current user ID from the server
        fetch('fetch_projects.php')
          .then(response => response.json())
          .then(data => {
            // Process the retrieved projects and display project previews
            displayProjectPreviews(data);

            // Check if there are no projects left
            if (data.length === 0) {
              document.getElementById('myProjectsCard').style.display = 'none';
            }
          })
          .catch(error => console.error('Error fetching projects:', error));
      }


      function displayProjectPreviews(projects) {
        // Clear existing project previews
        projectPreviews.innerHTML = '';
        // Display project previews
        projects.forEach(project => {
          const projectPreview = document.createElement('div');
          projectPreview.classList.add('project-preview');
          projectPreview.innerHTML = `
      <img src="${project.Project_File}" alt="${project.Project_Name}">
  <p>${project.Project_Name}</p>
        <button class="delete-project" data-id="${project.ID}">Delete</button>
    `;
          projectPreviews.appendChild(projectPreview);
        });
      }
    });

    document.addEventListener('DOMContentLoaded', function() {

      projectPreviews.addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-project')) {
          const projectId = e.target.dataset.id;
          if (confirm('Are you sure you want to delete this project?')) {
            deleteProject(projectId);
          }
        }
      });

      function deleteProject(projectId) {
        fetch('delete_project.php?id=' + projectId, {
            method: 'DELETE'
          })
          .then(response => {
            if (response.ok) {
              // Display delete success message
              alert('Project Successfully Deleted!');
              // Close the card
              myProjectsCard.style.display = 'none';
            } else {
              console.error('Failed to delete project');
            }
          })
          .catch(error => console.error('Error deleting project:', error));
      }

    });
  </script>
</body>

</html>