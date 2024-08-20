<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Registration</title>
    <link rel="stylesheet" href="style/styles.css">
    <style>
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            display: inline-block;
        }

        .button:hover {
            background-color: #0056b3;
        }
        @media (max-width: 600px) {
            .input-group input {
                flex: 1 1 100%;
            }

            button {
                padding: 8px 16px;
            }
        }
        .input-group {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .input-group label {
            flex: 1 1 100%;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .input-group input {
            flex: 1 1 calc(50% - 10px);
            padding: 10px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .subject {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fafafa;
        }

        .add-subject {
            margin-top: 20px;
            text-align: center;
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
    <h1>Subject Registration</h1>
    <form id="iaForm" action="submit_subjects.php" method="post">
        <div id="subjectsContainer">
            <div class="subject">
                <h2>Subject 1</h2>
                <div class="input-group">
                    <label for="Sem">Semester:</label>
                    <input type="number" name="Sem[]" placeholder="Enter semester no" required>
                </div>
                <div class="input-group">
                    <label for="SubjectCode">Subject Code:</label>
                    <input type="text" name="SubjectCode[]" placeholder="Enter subject code" required>
                </div>
                <div class="input-group">
                    <label for="SubjectName">Subject Name:</label>
                    <input type="text" name="SubjectName[]" placeholder="Enter subject name" required>
                </div>
                <div class="input-group">
                    <label for="totalIAMarks">Total IA Marks:</label>
                    <input type="number" name="totalIAMarks[]" placeholder="Enter total IA marks" required>
                </div>
                <h2>IA Marks Split</h2>
                <div class="input-group">
                    <label for="numberOfInternals">Number of Internals:</label>
                    <input type="number" name="numberOfInternals[]" placeholder="Enter number of internals" required>
                </div>
                <div class="input-group">
                    <label for="maxMarksOfInternal">Max Marks of Each Internal:</label>
                    <input type="number" name="maxMarksOfInternal[]" placeholder="Enter max marks of each internal" required>
                </div>
                <div class="input-group">
                    <label for="theoryIA">Theory IA Marks:</label>
                    <input type="number" name="theoryIA[]" placeholder="Enter theory IA marks" required>
                </div>
                <div class="input-group">
                    <label for="miniProject">Mini Project Marks (Optional):</label>
                    <input type="number" name="miniProject[]" placeholder="Enter mini project marks">
                </div>
                <div class="input-group">
                    <label for="avg">Average Marks :</label>
                    <input type="number" name="avg[]" placeholder="Enter avg marks" required>
                </div>
            </div>
        </div>
        <div class="add-subject">
            <button type="button" onclick="addSubject()">Add Another Subject</button>
        </div>
        <div class="add-subject">
            <button type="submit">Submit</button>
            <a href="view_inserted_data.php" class="button">View Inserted Data</a>
        </div>
    </form>
</div>

<footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>

<script>
    function addSubject() {
        const container = document.getElementById('subjectsContainer');
        const subjectCount = container.getElementsByClassName('subject').length + 1;
        const newSubject = document.createElement('div');
        newSubject.className = 'subject';
        newSubject.innerHTML = `
            <h2>Subject ${subjectCount}</h2>
            <div class="input-group">
                <label for="Sem">Semester:</label>
                <input type="number" name="Sem[]" placeholder="Enter semester no" required>
            </div>
            <div class="input-group">
                <label for="SubjectCode">Subject Code:</label>
                <input type="text" name="SubjectCode[]" placeholder="Enter subject code" required>
            </div>
            <div class="input-group">
                <label for="SubjectName">Subject Name:</label>
                <input type="text" name="SubjectName[]" placeholder="Enter subject name" required>
            </div>
            <div class="input-group">
                <label for="totalIAMarks">Total IA Marks:</label>
                <input type="number" name="totalIAMarks[]" placeholder="Enter total IA marks" required>
            </div>
            <h2>IA Marks Split</h2>
            <div class="input-group">
                <label for="numberOfInternals">Number of Internals:</label>
                <input type="number" name="numberOfInternals[]" placeholder="Enter number of internals" required>
            </div>
            <div class="input-group">
                <label for="maxMarksOfInternal">Max Marks of Each Internal:</label>
                <input type="number" name="maxMarksOfInternal[]" placeholder="Enter max marks of each internal" required>
            </div>
            <div class="input-group">
                <label for="theoryIA">Theory IA Marks:</label>
                <input type="number" name="theoryIA[]" placeholder="Enter theory IA marks" required>
            </div>
            <div class="input-group">
                <label for="miniProject">Mini Project Marks (Optional):</label>
                <input type="number" name="miniProject[]" placeholder="Enter mini project marks">
            </div>
            <div class="input-group">
                <label for="avg">Avg Marks</label>
                <input type="number" name="avg[]" placeholder="Enter avg marks">
            </div>
        `;
        container.appendChild(newSubject);
    }
</script>

</body>
</html>
