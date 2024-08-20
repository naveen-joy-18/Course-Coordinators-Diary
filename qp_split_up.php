<?php
session_start();
include('connection/dbconfig.php');

// Fetch semester from SESSION or POST request
$semester = isset($_SESSION['semester']) ? $_SESSION['semester'] : (isset($_POST['semester']) ? $_POST['semester'] : '');

$subjectsResult = [];
if ($semester) {
    // Convert semester to integer
    $semesterInt = (int)filter_var($semester, FILTER_SANITIZE_NUMBER_INT);

    // Fetch subjects from the database for the selected semester
    $subjectsQuery = "SELECT sub_code, sub_name FROM subjects WHERE semester = $semesterInt";
    $subjectsResult = mysqli_query($conn, $subjectsQuery);
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['subject'])) {
    // Get form data
    $sub_code = $_POST['subject'];
    $which_ia = $_POST['which-ia'];
    $total_marks = $_POST['total-marks'];
    $created_at = date('Y-m-d H:i:s');

    // Fetch subject name based on the selected sub_code
    $subjectQuery = "SELECT sub_name FROM subjects WHERE sub_code='$sub_code'";
    $subjectResult = mysqli_query($conn, $subjectQuery);
    $subjectRow = mysqli_fetch_assoc($subjectResult);
    $sub_name = $subjectRow['sub_name'];

   // Collect questions data
$questions_data = [];
$co_data = [];
for ($i = 1; $i <= 4; $i++) {
    $coKey = "Q{$i}";
    if (isset($_POST[$coKey])) {
        $co_data[$coKey] = $_POST[$coKey];
    }
    foreach (['a', 'b', 'c', 'd'] as $part) {
        $questionKey = "Q{$i}{$part}";
        $marksKey = "Q{$i}{$part}-marks";
        if (isset($_POST[$questionKey]) && !empty($_POST[$marksKey])) {
            $questions_data[$questionKey] = $_POST[$marksKey];
        }
    }
}
$_SESSION['co_data'] = $co_data;
// Define CO totals dynamically based on selected COs and their associated marks
$co_totals = [];
$max_marks = [];

// Iterate over questions Q1 to Q4
for ($i = 1; $i <= 4; $i++) {
    $coKey = "Q{$i}";
    if (isset($_POST[$coKey])) {
        $co = $_POST[$coKey];
        $total_marks = 0;
        foreach (['a', 'b', 'c', 'd'] as $part) {
            $marksKey = "Q{$i}{$part}-marks";
            if (isset($_POST[$marksKey]) && !empty($_POST[$marksKey])) {
                $marks = (int)$_POST[$marksKey];
                $total_marks += $marks;
            }
        }

        // Store maximum marks for "or" questions
        if ($i <= 2) {
            if (!isset($max_marks['Q1_Q2']) || $total_marks > $max_marks['Q1_Q2']['marks']) {
                $max_marks['Q1_Q2'] = ['co' => $co, 'marks' => $total_marks];
            }
        } else {
            if (!isset($max_marks['Q3_Q4']) || $total_marks > $max_marks['Q3_Q4']['marks']) {
                $max_marks['Q3_Q4'] = ['co' => $co, 'marks' => $total_marks];
            }
        }
    }
}

// Accumulate the maximum marks for COs
foreach ($max_marks as $max) {
    $co = $max['co'];
    $marks = $max['marks'];
    if (isset($co_totals[$co])) {
        $co_totals[$co] += $marks;
    } else {
        $co_totals[$co] = $marks;
    }
}

// Convert CO totals to JSON
$co_totals_json = json_encode($co_totals);



    // Check if record already exists
    $checkQuery = "SELECT * FROM qp_split_up WHERE semester='$semesterInt' AND sub_code='$sub_code' AND which_ia='$which_ia'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Update existing record
        $updateQuery = "UPDATE qp_split_up SET total_marks='$total_marks', questions_data='".json_encode($questions_data)."', co_data='".json_encode($co_data)."', co_totals='$co_totals_json', created_at='$created_at' 
                        WHERE semester='$semesterInt' AND sub_code='$sub_code' AND which_ia='$which_ia'";
        if (mysqli_query($conn, $updateQuery)) {
            $_SESSION['message'] = 'Data successfully updated.';
        } else {
            $_SESSION['message'] = 'Error updating data: ' . mysqli_error($conn);
        }
    } else {
        // Insert new record
        $insertQuery = "INSERT INTO qp_split_up (semester, sub_code, sub_name, which_ia, total_marks, questions_data, co_data, co_totals, created_at) 
                        VALUES ('$semesterInt', '$sub_code', '$sub_name', '$which_ia', '$total_marks', '".json_encode($questions_data)."', '".json_encode($co_data)."', '$co_totals_json', '$created_at')";
        if (mysqli_query($conn, $insertQuery)) {
            $_SESSION['message'] = 'Data successfully saved.';
        } else {
            $_SESSION['message'] = 'Error saving data: ' . mysqli_error($conn);
        }
    }

    // Store data in session
    $_SESSION['questions_data_json'] = json_encode($questions_data);
    $_SESSION['co_data_json'] = json_encode($co_data);
    $_SESSION['co_totals_json'] = $co_totals_json;
    $_SESSION['subject'] = $sub_code;
    $_SESSION['which_ia'] = $which_ia;

    // Redirect to marks_upload.php with the selected semester
    header('Location: marks_upload.php?semester=' . $semester . '&sub_code=' . $sub_code . '&which_ia=' . $which_ia);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Paper Split-Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/styles.css">
    <style>
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn-small {
            flex: 1;
            margin: 0 5px;
        }

        .btn-small:first-child {
            margin-left: 0;
        }

        .btn-small:last-child {
            margin-right: 0;
        }

        .btn-back {
            margin-top: 20px;
            width: 100%;
        }
        @media (max-width: 576px) {
            .btn-group {
                flex-direction: column;
            }
            .btn-small {
                width: 100%;
                margin: 5px 0;
            }
            .btn-back {
                width: 100%;
            }
        }

        .marks {
            display: none;
            margin-top: 10px;
        }

        .btn-primary, .btn-secondary {
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-primary:hover, .btn-secondary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .custom-select {
            position: relative;
        }

        .custom-select select {
            padding-right: 30px;
        }

        .custom-select::after {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }
    </style>
</head>
<body>
<header class="header">
    <nav class="nav">
        <div class="logo-container">
  <img src="image/logo.png" alt="Logo" class="logo-image">
        </div>
        <div class="logo">Course Coordinator's Diary</div>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
        </ul>
    </nav>
</header>
    <div class="container">
            <h1>Question Paper Split-Up</h1>
        <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-info">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
        }
        ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="semester">Semester:</label>
                <input type="text" id="semester" name="semester" class="form-control" value="<?php echo htmlspecialchars($semester); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="subject">Subject:</label>
                <select id="subject" name="subject" class="form-control custom-select" required>
                    <option value="">Select Subject</option>
                    <?php
                    if ($semester && mysqli_num_rows($subjectsResult) > 0) {
                        while ($row = mysqli_fetch_assoc($subjectsResult)) {
                            echo "<option value='{$row['sub_code']}'>{$row['sub_code']} - {$row['sub_name']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="which-ia">Which IA:</label>
                <select id="which-ia" name="which-ia" class="form-control custom-select" required>
                    <option value="">Select IA</option>
                    <option value="1">IA 1</option>
                    <option value="2">IA 2</option>
                    <option value="3">IA 3</option>
                </select>
            </div>
            <div class="form-group">
                <label for="total-marks">Total Marks:</label>
                <input type="number" id="total-marks" name="total-marks" class="form-control" min="1" max="30" required>
            </div>
            <div class="form-group">
                <label for="questions">Questions:</label>
                <div id="questions">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="question">
                            <div class="d-flex align-items-center mb-2">
                                <p class="mb-0">Question <?php echo $i; ?>:</p>
                                <div class="custom-select ms-3" style="width: auto;">
                                    <select name="Q<?php echo $i; ?>" class="form-control" required>
                                        <option value="">Select CO</option>
                                        <option value="CO1">CO1</option>
                                        <option value="CO2">CO2</option>
                                        <option value="CO3">CO3</option>
                                        <option value="CO4">CO4</option>
                                        <option value="CO5">CO5</option>
                                    </select>
                                </div>
                            </div>
                            <?php foreach (['a', 'b', 'c', 'd'] as $part): ?>
                                <label>
                                    <input type="checkbox" name="Q<?php echo $i . $part; ?>" value="<?php echo $part; ?>" class="question-checkbox">
                                    <?php echo $part; ?>)
                                </label>
                                <input type="number" name="Q<?php echo $i . $part; ?>-marks" class="marks form-control" placeholder="Enter marks for <?php echo $part; ?>" min="1" max="10">
                            <?php endforeach; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary btn-small">Submit</button>
                <a href="view_qp_split_data.php" class="btn btn-secondary btn-small">View Inserted Data</a>
            </div>
            <button type="button" class="btn btn-secondary btn-back" onclick="window.history.back();">Back</button>
        </form>
    </div>
    <footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script>
        document.querySelectorAll('.question-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                let marksInput = this.parentElement.nextElementSibling;
                if (this.checked) {
                    marksInput.style.display = 'block';
                } else {
                    marksInput.style.display = 'none';
                }
            });
        });

        // Validate total marks on form submission
        document.querySelector('form').addEventListener('submit', function(event) {
            let totalMarks = parseInt(document.getElementById('total-marks').value);
            let questions = document.querySelectorAll('.question');
            let valid = true;

            questions.forEach(function(question) {
                let totalMaxMarks = 0;
                let checkboxes = question.querySelectorAll('.question-checkbox:checked');
                checkboxes.forEach(function(checkbox) {
                    let marksInput = checkbox.parentElement.nextElementSibling;
                    if (marksInput.value) {
                        totalMaxMarks += parseInt(marksInput.value);
                    }
                });

                if (totalMaxMarks !== totalMarks / 2) {
                    valid = false;
                }
            });

            if (!valid) {
                alert('Enter correct marks for each question.');
                event.preventDefault();
            }
        });
    </script>
</body>
</html>