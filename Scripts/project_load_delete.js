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