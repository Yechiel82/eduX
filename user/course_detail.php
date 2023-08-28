<!DOCTYPE html>
<html>
<head>
  <title>Course Details</title>
  <link rel="stylesheet" href="../css/user/course_detail.css">


</head>
<body>
  <?php
    session_start();
    require '../connection.php';
    // Check if the course ID is provided in the URL
    if (isset($_GET['id'])) {
      // Retrieve the course ID from the URL
      $courseId = $_GET['id'];

      // Perform database query to fetch course details based on the ID
      // Modify this section to fetch the course details from your database table
      $query = "SELECT * FROM courses WHERE id = $courseId";
      $result = mysqli_query($conn, $query);

      // Check if the query was successful
      if ($result && mysqli_num_rows($result) > 0) {
        $course = mysqli_fetch_assoc($result);
  ?>

  <h1><?php echo $course['title']; ?></h1>

  <div class="course-material">
    <h2>Course Material</h2>
    <ul id="material-list">
      <?php
        // Perform database query to fetch course materials based on the course ID
        $materialQuery = "SELECT week, GROUP_CONCAT(material_title SEPARATOR ', ') AS materials, GROUP_CONCAT(link SEPARATOR ', ') AS links FROM materials WHERE course_id = $courseId GROUP BY week";
        $materialResult = mysqli_query($conn, $materialQuery);

        // Check if the query was successful
        if ($materialResult && mysqli_num_rows($materialResult) > 0) {
          while ($material = mysqli_fetch_assoc($materialResult)) {
            $materialTitles = explode(', ', $material['materials']);
            $materialLinks = explode(', ', $material['links']);
            ?>
            <li>
              <input type="checkbox" id="week-<?php echo $courseId; ?>-<?php echo $material['week']; ?>" onchange="updateProgress(<?php echo $courseId; ?>)">
              <label for="week-<?php echo $material['week']; ?>"><?php echo $material['week']; ?></label>
              <div class="dropdown">
                <div class="dropdown-toggle" onclick="toggleMaterialDropdown(event, 'dropdown-<?php echo $material['week']; ?>')">View Material</div>
                <div id="dropdown-<?php echo $material['week']; ?>" class="dropdown-menu">
                  <ul>
                    <?php for ($i = 0; $i < count($materialTitles); $i++) { ?>
                      <li>
                        <a href="<?php echo $materialLinks[$i]; ?>"><?php echo $materialTitles[$i]; ?></a>
                      </li>
                    <?php } ?>
                    <!-- Add more materials -->
                  </ul>
                </div>
              </div>
            </li>
            <?php
          }
        } else {
          echo "<li>No materials available for this course.</li>";
        }

        // Free the result set
        mysqli_free_result($materialResult);
      ?>
    </ul>
  </div>

  <div class="progress-bar-container">
    <div id="progress-bar"></div>
    <span id="progress-label">0%</span>
  </div>

  <div class="center-button">
    <button onclick="updateEnrollmentProgress(<?php echo $courseId; ?>, parseFloat(document.getElementById('progress-label').textContent))" class="btn btn-primary">Done</button>
  </div>

  <script>
    window.onload = function() {
      restoreCheckboxStates();
      updateProgress();
    };

    function toggleMaterialDropdown(event, dropdownId) {
      var dropdown = document.getElementById(dropdownId);

      if (dropdown.style.display === 'none') {
        dropdown.style.display = 'block'; // Show the dropdown
        document.addEventListener('click', handleOutsideClick); // Add event listener to close dropdown on outside click
      } else {
        dropdown.style.display = 'none'; // Hide the dropdown
        document.removeEventListener('click', handleOutsideClick); // Remove event listener
      }

      event.stopPropagation(); // Prevent the click event from bubbling up to the document
    }

    function handleOutsideClick(event) {
      var dropdowns = document.getElementsByClassName('dropdown-menu');

      for (var i = 0; i < dropdowns.length; i++) {
        var dropdown = dropdowns[i];

        if (!dropdown.contains(event.target)) {
          dropdown.style.display = 'none'; // Hide the dropdown if the click is outside of it
        }
      }
    }

    function updateProgress(courseId) {
      var checkboxes = document.querySelectorAll('input[type="checkbox"]');
      var totalWeeks = checkboxes.length;
      var completedWeeks = 0;

      checkboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
          completedWeeks++;
        }
      });

      var progressBar = document.getElementById('progress-bar');
      var progressLabel = document.getElementById('progress-label');
      var progressPercent = (completedWeeks / totalWeeks) * 100;

      progressBar.style.width = progressPercent + '%';
      progressLabel.textContent = progressPercent.toFixed(0) + '%';

      saveCheckboxStates(courseId); // Menyertakan courseId saat memanggil saveCheckboxStates()

      if (progressPercent === 100) {
        checkboxes.forEach(function(checkbox) {
          checkbox.disabled = true; // Menonaktifkan checkbox jika progress sudah mencapai 100%
        });
      } else {
        checkboxes.forEach(function(checkbox) {
          checkbox.disabled = false; // Mengaktifkan checkbox jika progress belum mencapai 100%
        });
      }
    }

    function saveCheckboxStates(courseId) {
      var checkboxes = document.querySelectorAll('input[type="checkbox"]');

      checkboxes.forEach(function(checkbox) {
        var checkboxId = checkbox.id.split('-');
        var key = courseId + '-' + checkboxId[1] + '-' + checkboxId[2];
        localStorage.setItem(key, checkbox.checked);
      });
    }

    window.onload = function() {
      restoreCheckboxStates(<?php echo $courseId; ?>);
      updateProgress(<?php echo $courseId; ?>);
    };

    function restoreCheckboxStates(courseId) {
      var checkboxes = document.querySelectorAll('input[type="checkbox"]');

      checkboxes.forEach(function(checkbox) {
        var checkboxId = checkbox.id.split('-');
        var key = courseId + '-' + checkboxId[1] + '-' + checkboxId[2];
        var checked = localStorage.getItem(key);
        if (checked === 'true') {
          checkbox.checked = true;
        } else {
          checkbox.checked = false;
        }
      });
    }

    function updateEnrollmentProgress(courseId, progress) {
    // Get the current progress from the progress label element
    var currentProgress = parseFloat(document.getElementById('progress-label').textContent);

    // Check if the current progress is greater than or equal to the progress to be sent
    if (currentProgress >= progress) {
      // Use the current progress instead of the progress to be sent
      progress = currentProgress;
    }

    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Set the request URL
    var url = "update_enrollment_progress.php";

    // Create the parameters string
    var params = "courseId=" + courseId + "&progress=" + progress;

    // Configure the request
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Handle the response
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
        // Response received, do something with it if needed
        console.log(xhr.responseText);
        window.location.href = "enrollments.php";
      }
    };

    // Send the request
    xhr.send(params);
  }

  </script>

  <?php
    } else {
      echo "<h1>Course not found.</h1>";
    }

    // Free the result set
    mysqli_free_result($result);
    } else {
      echo "<h1>Invalid course ID.</h1>";
    }

    // Close database connection
    mysqli_close($conn);
  ?>

</body>
</html>