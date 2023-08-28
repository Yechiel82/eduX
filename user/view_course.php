<?php
    session_start();

    require "../connection.php";

    // Define an array of course descriptions
    $course_descriptions = array(
        1 => "Python Programming is a high-level programming language known for its simplicity and readability. It offers a wide range of applications, from web development to data analysis and artificial intelligence. Python's syntax allows developers to express concepts in fewer lines of code compared to other languages, making it beginner-friendly and efficient for rapid development.
        This course will take you through the fundamentals of Python Programming, including variables, data types, control flow, functions, and file handling. You will learn how to write Python scripts, create modular and reusable code, and work with libraries and frameworks to build various applications.
        <br/><br/>
        Topics covered in this course include:
        <br/>
        - Introduction to Python and its features <br/>
        - Python data types and variables <br/>
        - Control flow statements (if-else, loops) <br/>
        - Functions and modules in Python <br/>
        - File handling and input/output operations <br/>
        - Error handling and exception handling <br/>
        - Introduction to object-oriented programming (OOP) concepts in Python <br/>
        - Working with libraries and frameworks like NumPy, Pandas, and Django <br/>
        <br/>
        By the end of this course, you will have a solid foundation in Python Programming and be able to develop your own Python applications, automate tasks, and analyze data.
    
        Join us now and embark on your journey to become a proficient Python developer!",
        13 => "Data Science Essentials is a comprehensive course that covers the fundamental concepts and techniques of data science. In this course, you will learn about data manipulation, data visualization, statistical analysis, machine learning, and more. Through hands-on exercises and real-world examples, you will gain practical skills to extract valuable insights from data and make data-driven decisions. Whether you are a beginner or have some experience in data science, this course will provide you with a solid foundation to excel in the field.",
        9 => "Web Development Fundamentals is a beginner-friendly course that introduces you to the core concepts of web development. In this course, you will learn about HTML, CSS, and JavaScript, which are the building blocks of the web. You will understand how to structure web pages, apply styles, and add interactivity using JavaScript.
        <br/><br/>
        Topics covered in this course include:
        <br/>
        - Introduction to web development <br/>
        - HTML fundamentals <br/>
        - CSS styling and layout <br/>
        - JavaScript basics <br/>
        - DOM manipulation <br/>
        - Handling user events <br/>
        - Introduction to responsive design <br/>
        - Web development best practices <br/>
        By the end of this course, you will have a solid understanding of web development principles and be able to create static web pages with interactive features. Join us now and start your journey to becoming a web developer!"
    );

    // Retrieve the course ID from the URL parameter
    $course_id = $_GET['id'];

    // Fetch the course details from the database based on the course ID
    $query = "SELECT * FROM courses WHERE id = '$course_id'";
    $result = mysqli_query($conn, $query);
    $course = mysqli_fetch_assoc($result);

    // Fetch the instructors for the course from the 'instructor' table
    $instructors_query = "SELECT * FROM instructor WHERE course_id = '$course_id'";
    $instructors_result = mysqli_query($conn, $instructors_query);
    $instructors = mysqli_fetch_all($instructors_result, MYSQLI_ASSOC);

    // Check if the course ID exists in the array of course descriptions
    if (array_key_exists($course_id, $course_descriptions)) {
        $full_description = $course_descriptions[$course_id];
    } else {
        // Use the short description from the database if full description is not available
        $full_description = $course['description'];
    }

    // Close the database connection
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Course - <?php echo $course['title']; ?></title>
    <link rel="stylesheet" href="../css/user/view_course.css">
</head>
<body >

    <div class="container">

        <div class="course-info">
            <div class="course-details">
            <h1><?php echo $course['title']; ?></h1>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($course['thumbnail']); ?>" alt="Course Thumbnail" class="course-thumnail">
            </div>
            <p><?php echo $full_description; ?></p>
        </div>

        <div class="teacher-info">
            <h3>Instructors:</h3>
            <ul>
                <?php foreach ($instructors as $instructor) { ?>
                    <li><?php echo $instructor['name']; ?></li>
                <?php } ?>
            </ul>
        </div>
        
        <div class="other-info">
            <?php if ($course_id == 1) { ?>
                <h3>Course Duration:</h3>
                <p>3 weeks</p>

                <h3>Prerequisites:</h3>
                <p>Basic knowledge of programming</p>

                <h3>Course Objectives:</h3>
                <ul>
                    <li>Master Python programming fundamentals</li>
                    <li>Develop skills in data analysis and manipulation</li>
                    <li>Build real-world applications using Python</li>
                </ul>

                <h3>Learning Methods:</h3>
                <p>Video lessons, interactive quizzes, and hands-on projects</p>

                <h3>Certificate:</h3>
                <p>Earn a recognized certificate upon course completion</p>

                <h3>Testimonials:</h3>
                <blockquote>
                    "This course has been a game-changer for me. The instructors are knowledgeable and the content is well-structured." - John Doe
                </blockquote>

                <h3>Additional Features:</h3>
                <ul>
                    <li>Lifetime access to course materials</li>
                    <li>Access to a community forum for discussions and support</li>
                </ul>

                <h3>Fee and Registration:</h3>
                <p>Course fee: $<?php echo $course['price']; ?>.</p>

            <?php } elseif ($course_id == 13) { ?>
                <h3>Course Duration:</h3>
                <p>3 weeks</p>

                <h3>Prerequisites:</h3>
                <p>Basic knowledge of statistics and programming</p>

                <h3>Course Objectives:</h3>
                <ul>
                    <li>Understand the data science process and workflow</li>
                    <li>Perform data exploration and visualization</li>
                    <li>Apply statistical techniques for data analysis</li>
                    <li>Build and evaluate machine learning models</li>
                </ul>

                <h3>Learning Methods:</h3>
                <p>Hands-on projects, case studies, and assignments</p>

                <h3>Certificate:</h3>
                <p>Earn a certificate of completion</p>

                <h3>Testimonials:</h3>
                <blockquote>
                    "This course gave me the skillsI needed to kickstart my career in data science. Highly recommended!" - Jane Smith
                </blockquote>

                <h3>Additional Features:</h3>
                <ul>
                    <li>Access to a dedicated mentor for personalized guidance</li>
                    <li>Job placement assistance</li>
                </ul>

                <h3>Fee and Registration:</h3>
                <p>Course fee: $<?php echo $course['price']; ?>.</p>

            <?php } elseif ($course_id == 9) { ?>
                <h3>Course Duration:</h3>
                <p>3 weeks</p>

                <h3>Prerequisites:</h3>
                <p>No prior experience required</p>

                <h3>Course Objectives:</h3>
                <ul>
                    <li>Learn HTML, CSS, and JavaScript from scratch</li>
                    <li>Build responsive web pages</li>
                    <li>Implement interactive features using JavaScript</li>
                </ul>

                <h3>Learning Methods:</h3>
                <p>Step-by-step tutorials and coding exercises</p>

                <h3>Certificate:</h3>
                <p>Receive a certificate upon successful completion</p>

                <h3>Testimonials:</h3>
                <blockquote>
                    "I had zero knowledge of web development before taking this course. Now I can create my own websites!" - Sarah Johnson
                </blockquote>

                <h3>Additional Features:</h3>
                <ul>
                    <li>Access to a library of additional resources and tutorials</li>
                    <li>Opportunity to showcase your projects in a student showcase</li>
                </ul>

                <h3>Fee and Registration:</h3>
                <p>Course fee: $<?php echo $course['price']; ?>.</p>
            <?php } ?>
        </div>

        <a href="../index.php">Back</a>

    </div>
    
</body>
</html>