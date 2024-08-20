<!DOCTYPE html>
<html>
<head>
    <title>Student Assessment</title>
    <link rel="stylesheet" href="style/styles.css">
    <style>
         .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }

        .btn-group {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.btn-secondary,.btn-back {
  background-color: #6c757d;
  border-color: #6c757d;
  color: #fff;
  padding: 5px 10px; /* Reduced padding to make the button smaller */
  font-size: 14px; /* Reduced font size to make the button smaller */
  border-radius: 5px;
  width: 60px; /* Reduced width to make the button smaller */
  margin: 0 10px;
}

.btn-secondary:hover {
  background-color: #5a6268;
  border-color: #5a6268;
  cursor: pointer;
}

.btn-secondary:active {
  background-color: #4d5357;
  border-color: #4d5357;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

.btn-back {
  background-color: #dc3545;
  border-color: #dc3545;
}

.btn-back:hover {
  background-color: #c82333;
  border-color: #c82333;
}

.btn-back:active {
  background-color: #b21f2a;
  border-color: #b21f2a;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

        @media (max-width: 576px) {
            .container {
                padding: 20px;
            }
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
            color:white;
        }
        @media (max-width: 576px) {
            .container {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }
        }
         @media print {
            #calculate-button {
        display: none;
           }
            header, footer {
                    display: none;
            }
            button {
                display: none;
            }
            @page {
                size: A4;
                margin: 0;
            }
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
    <div class="container"><br><br>
            <h2>Student Assessment</h2>
        <form method="POST" action="pink_book_mark_upload.php">
            <div class="form-group">
                <label for="semester">Select Semester:</label>
                <select id="semester" name="semester" class="form-control">
                    <option value="0">Select semester</option>
                    <option value="1">First Semester</option>
                    <option value="2">Second Semester</option>
                    <option value="3">Third Semester</option>
                    <option value="4">Fourth Semester</option>
                    <option value="5">Fifth Semester</option>
                    <option value="6">Sixth Semester</option>
                    <option value="7">Seventh Semester</option>
                    <option value="8">Eighth Semester</option>
                </select>
            </div>
                        <div class="form-group">
                <label for="subject">Select Subject:</label>
                <select id="subject" name="subject" class="form-control" required>
                    <option value="">Select Subject</option>
                    <!-- Options will be populated via JavaScript -->
                </select>
            </div>
            <div class="form-group">
                <h4>Select Assessment:</h4>
                <div class="assessment-group">
                    <label><input type="checkbox" name="assessment[]" value="Assignment1" class="assessment-checkbox"> Assignment 1
                    <input type="number" name="max_marks[Assignment1]" placeholder="Enter max marks" class="marks-input" style="display: none;">
                    <select name="co[Assignment1]" class="co-select" style="display: none;">
                        <option value="CO1">CO1</option>
                        <option value="CO2">CO2</option>
                        <option value="CO3">CO3</option>
                        <option value="CO4">CO4</option>
                        <option value="CO5">CO5</option>
                        <option value="CO6">CO6</option>
                    </select>
                    </label>
                </div>
                <div class="assessment-group">
                    <label><input type="checkbox" name="assessment[]" value="Assignment2" class="assessment-checkbox"> Assignment 2
                    <input type="number" name="max_marks[Assignment2]" placeholder="Enter max marks" class="marks-input" style="display: none;">
                    <select name="co[Assignment2]" class="co-select" style="display: none;">
                        <option value="CO1">CO1</option>
                        <option value="CO2">CO2</option>
                        <option value="CO3">CO3</option>
                        <option value="CO4">CO4</option>
                        <option value="CO5">CO5</option>
                        <option value="CO6">CO6</option>
                    </select>
                    </label>
                </div>
                <div class="assessment-group">
                    <label><input type="checkbox" name="assessment[]" value="unittest1" class="assessment-checkbox"> Unit Test 1
                    <input type="number" name="max_marks[unittest1]" placeholder="Enter max marks" class="marks-input" style="display: none;">
                    <select name="co[unittest1]" class="co-select" style="display: none;">
                        <option value="CO1">CO1</option>
                        <option value="CO2">CO2</option>
                        <option value="CO3">CO3</option>
                        <option value="CO4">CO4</option>
                        <option value="CO5">CO5</option>
                        <option value="CO6">CO6</option>
                    </select>
                    </label>
                </div>
                <div class="assessment-group">
                    <label><input type="checkbox" name="assessment[]" value="unittest2" class="assessment-checkbox"> Unit Test 2
                    <input type="number" name="max_marks[unittest2]" placeholder="Enter max marks" class="marks-input" style="display: none;">
                    <select name="co[unittest2]" class="co-select" style="display: none;">
                        <option value="CO1">CO1</option>
                        <option value="CO2">CO2</option>
                        <option value="CO3">CO3</option>
                        <option value="CO4">CO4</option>
                        <option value="CO5">CO5</option>
                        <option value="CO6">CO6</option>
                    </select>
                    </label>
                </div>
                <div class="assessment-group">
                    <label><input type="checkbox" name="assessment[]" value="Quiz1" class="assessment-checkbox"> Quiz 1
                    <input type="number" name="max_marks[Quiz1]" placeholder="Enter max marks" class="marks-input" style="display: none;">
                    <select name="co[Quiz1]" class="co-select" style="display: none;">
                        <option value="CO1">CO1</option>
                        <option value="CO2">CO2</option>
                        <option value="CO3">CO3</option>
                        <option value="CO4">CO4</option>
                        <option value="CO5">CO5</option>
                        <option value="CO6">CO6</option>
                    </select>
                    </label>
                </div>
                <div class="assessment-group">
                    <label><input type="checkbox" name="assessment[]" value="Quiz2" class="assessment-checkbox"> Quiz 2
                    <input type="number" name="max_marks[Quiz2]" placeholder="Enter max marks" class="marks-input" style="display: none;">
                    <select name="co[Quiz2]" class="co-select" style="display: none;">
                        <option value="CO1">CO1</option>
                        <option value="CO2">CO2</option>
                        <option value="CO3">CO3</option>
                        <option value="CO4">CO4</option>
                        <option value="CO5">CO5</option>
                        <option value="CO6">CO6</option>
                    </select>
                    </label>
                </div>
                <div class="assessment-group">
                    <label><input type="checkbox" name="assessment[]" value="Miniproject" class="assessment-checkbox"> Miniproject
                    <input type="number" name="max_marks[Miniproject]" placeholder="Enter max marks" class="marks-input" style="display: none;">
                    <select name="co[Miniproject]" class="co-select" style="display: none;">
                        <option value="CO1">CO1</option>
                        <option value="CO2">CO2</option>
                        <option value="CO3">CO3</option>
                        <option value="CO4">CO4</option>
                        <option value="CO5">CO5</option>
                        <option value="CO6">CO6</option>
                    </select>
                    </label>
                </div>
                <div class="assessment-group">
                    <label><input type="checkbox" name="assessment[]" value="Seminar" class="assessment-checkbox"> Seminar
                    <input type="number" name="max_marks[Seminar]" placeholder="Enter max marks" class="marks-input" style="display: none;">
                    <select name="co[Seminar]" class="co-select" style="display: none;">
                        <option value="CO1">CO1</option>
                        <option value="CO2">CO2</option>
                        <option value="CO3">CO3</option>
                        <option value="CO4">CO4</option>
                        <option value="CO5">CO5</option>
                        <option value="CO6">CO6</option>
                    </select>
                    </label>
                </div>
                <div class="assessment-group">
                    <label><input type="checkbox" name="assessment[]" value="Other" class="assessment-checkbox"> Other Assessment Tool
                    <input type="number" name="max_marks[Other]" placeholder="Enter max marks" class="marks-input" style="display: none;">
                    <select name="co[Other]" class="co-select" style="display: none;">
                        <option value="CO1">CO1</option>
                        <option value="CO2">CO2</option>
                        <option value="CO3">CO3</option>
                        <option value="CO4">CO4</option>
                        <option value="CO5">CO5</option>
                        <option value="CO6">CO6</option>
                    </select>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="scaledown">Scale Down Marks:</label>
                <input type="number" id="scaledown" name="scaledown" class="form-control" required>
            </div>
            <div class="btn-group">
            <button type="submit" class="btn btn-secondary btn-back" id="calculate-button">Submit</button><br><br>
            <button type="button" class="btn btn-secondary btn-back" onclick="window.history.back();">Back</button>
            </div>
        </form>
    </div>
    <footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const semesterSelect = document.getElementById('semester');
            const subjectSelect = document.getElementById('subject');

            semesterSelect.addEventListener('change', function () {
    const semester = this.value;

    fetch('fetch_subjects.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'semester=' + encodeURIComponent(semester)
    })
    .then(response => response.json())
    .then(data => {
        subjectSelect.innerHTML = '';
        if (data.error) {
            console.error('Error fetching subjects:', data.error);
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'Error fetching subjects';
            subjectSelect.appendChild(option);
        } else if (data.length > 0) {
            data.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.sub_name;
                subjectSelect.appendChild(option);
            });
            // Update the subject select element's value to the first subject's ID
            subjectSelect.value = data[0].id;
        } else {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'No subjects available';
            subjectSelect.appendChild(option);
        }
    })
    .catch(error => console.error('Error fetching subjects:', error));
});
subjectSelect.addEventListener('change', function () {
        const subjectId = this.value;
        // You can use the subjectId value here to perform any necessary actions
    });

            document.querySelectorAll('.assessment-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    let marksInput = this.nextElementSibling;
                    marksInput.style.display = this.checked ? 'inline-block' : 'none';
                    let coSelect = marksInput.nextElementSibling;
                    coSelect.style.display = this.checked ? 'inline-block' : 'none';
                });
            });
        });
    </script>
</body>
</html>
