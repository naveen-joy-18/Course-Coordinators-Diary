<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IOT-MOM Form</title>
  <link rel="stylesheet" href="style/styles.css">
  <script>
    function saveFormData() {
      const form = document.getElementById('checklistForm');
      const formData = new FormData(form);
      
      // Store form data in localStorage
      for (const [key, value] of formData.entries()) {
        localStorage.setItem(key, value);
      }
      
      // Redirect to Form.php
      window.location.href = 'Form.php';
    }

    function generateChecklist() {
      const form = document.getElementById('checklistForm');
      const formData = new FormData(form);
      
      // Store form data in localStorage
      for (const [key, value] of formData.entries()) {
        localStorage.setItem(key, value);
      }
      
      // Redirect to Checklist.php
      window.location.href = 'Checklist.php';
    }

    function generateIntimation() {
      const form = document.getElementById('checklistForm');
      const formData = new FormData(form);
      
      // Store form data in localStorage
      for (const [key, value] of formData.entries()) {
        localStorage.setItem(key, value);
      }
      
      // Redirect to Intimation.php
      window.location.href = 'Intimation.php';
    }

    function generateMeeting() {
      const form = document.getElementById('checklistForm');
      const formData = new FormData(form);
      
      // Store form data in localStorage
      for (const [key, value] of formData.entries()) {
        localStorage.setItem(key, value);
      }
      
      // Redirect to Meeting.php
      window.location.href = 'Meeting.php';
    }
  </script>
  <style>
    table {
      width: 1000px;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    table, th, td {
      border: 1px solid #000;
    }
    th, td {
      padding: 10px;
      text-align: left;
    }
    .center {
      display: flex;
      justify-content: center;
    }
    input[type="text"], input[type="date"], select {
      width: 100%;
      box-sizing: border-box;
      padding: 8px;
      border: 1px solid #ccc;
    }
    .button-container {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 20px;
    }
    .button-container button {
      background-color: #4CAF50;
      color: white;
      padding: 12px 24px;
      border: none;
      cursor: pointer;
      font-size: 1em;
    }
    .button-container button:hover {
      opacity: 0.8;
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
  <center>
  <br><br>
    <h2>Outcome-Based Teaching & Learning Checklist</h2>
    <p><strong>Course Committee</strong></p>
  </center>
  <div class="center">
    <form id="checklistForm" method="post">
      <table>
        <tr>
          <th>Course Code</th>
          <td><input type="text" name="courseCode" placeholder="Enter Course Code" required></td>
        </tr>
        <tr>
          <th>Course Title</th>
          <td><input type="text" name="courseTitle" placeholder="Enter Course Title" required></td>
        </tr>
        <tr>
          <th>Academic Year</th>
          <td><input type="text" name="academicYear" placeholder="Enter Academic Year" required></td>
        </tr>
        <tr>
          <th>Semester</th>
          <td>
            <select name="semester" required>
              <option value="even">Even</option>
              <option value="odd">Odd</option>
            </select>
          </td>
        </tr>
        <tr>
          <th>Instructors</th>
          <td>--</td>
        </tr>
        <tr>
          <th>Bloom’s Level Planned</th>
          <td>BTL-3</td>
        </tr>
        <tr>
          <th>POs Mapped</th>
          <td><input type="text" name="poMapped" placeholder="Enter POs Mapped" required></td>
        </tr>
        <tr>
          <th>Course Co-ordinator</th>
          <td>
            <select name="courseCoordinatorTitle" style="width: 80px;" required>
              <option value="Dr">Dr</option>
              <option value="Prof">Prof</option>
              <option value="Ms">Ms</option>
            </select>
            <input type="text" name="courseCoordinatorName" placeholder="Enter Course Co-ordinator Name" required>
          </td>
        </tr>
        <tr>
          <th>Course Co-ordinator Designation</th>
          <td><input type="text" name="courseCoordinatorDesign" placeholder="Enter Course Co-ordinator Designation" required></td>
        </tr>
        <tr>
          <th>Stream Expert</th>
          <td>
            <select name="streamExpertTitle" style="width: 80px;" required>
              <option value="Dr">Dr</option>
              <option value="Prof">Prof</option>
              <option value="Ms">Ms</option>
            </select>
            <input type="text" name="streamExpertName" placeholder="Enter Stream Expert Name" required>
          </td>
        </tr>
        <tr>
          <th>Stream Expert Designation</th>
          <td><input type="text" name="streamExpertDesign" placeholder="Enter Stream Expert Designation" required></td>
        </tr>
        <tr>
          <th>Date of Intimation</th>
          <td><input type="date" name="dateOfIntimation" required></td>
        </tr>
        <tr>
          <th>Date of Meeting</th>
          <td><input type="date" name="dateOfMeeting" required></td>
        </tr>
        <tr>
          <th>Date of Form Completed</th>
          <td><input type="date" name="dateOfFormCompleted" required></td>
        </tr>
      </table>
      <div class="button-container">
        <button type="button" onclick="saveFormData()">Generate Form</button>
        <button type="button" onclick="generateChecklist()">Generate Checklist</button>
        <button type="button" onclick="generateIntimation()">Generate Intimation</button>
        <button type="button" onclick="generateMeeting()">Generate Meeting</button>
      </div>
    </form>
  </div>
  </div>
  <footer>
    <img src="image/logo.png" alt="Logo" class="footer-logo">
    <p>&copy; 2024 Yitise2021.com. All rights reserved.</p>
</footer>
</body>
</html>