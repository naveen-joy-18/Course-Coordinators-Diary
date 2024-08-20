<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Data Display</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      line-height: 1.6;
    }
    
    header {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 10px 0;
      border-bottom: 2px solid #000;
      margin-bottom: 20px;
    }

    header img {
      width: 100px;
      height: auto;
      margin-right: 20px;
    }

    header div {
      text-align: center;
    }

    header h1 {
      font-size: 1.8em;
      color: #8B4513;
      margin: 0;
    }

    header p {
      font-size: 1.1em;
      color: #555;
      margin: 0;
    }

    section, footer {
      margin-bottom: 20px;
    }

    table {
      width: 1000px;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    table, th, td {
      border: 1px solid #000;
    }

    th, td {
      padding: 8px;
      text-align: left;
    }

    .center {
      display: flex;
      justify-content: center;
    }

    .content {
      margin-bottom: 20px;
    }

    .button-container {
      text-align: center;
      margin-top: 20px;
    }

    .button-container button {
      padding: 10px 20px;
      font-size: 16px;
      background-color: #007bff;
      color: #fff;
      border: none;
      cursor: pointer;
      margin-right: 10px;
    }
    /* Ensure footer is visible in print mode */
    @media print {
      .button-container {
        display: none;
      }
    }
  </style>
</head>
<body>
<header>
    <img src="image/logo.png" alt="Yenepoya Institute of Technology">
    <div>
      <h1>Yenepoya Institute of Technology</h1>
      <p>N. H. 13, Thodar, Moodbidri - 574 225, Mangaluru, D. K.</p>
    </div>
  </header>
  
  <h2 style="text-align: center;">Outcome-Based Teaching<br>Course Committee</h2>
  <center>
    <table>
      <tr>
        <th><center>Field</center></th>
        <th><center>Value</center></th>
      </tr>
      <script>
        // Retrieve form data from localStorage
        const courseCode = localStorage.getItem('courseCode');
        const courseTitle = localStorage.getItem('courseTitle');
        const academicYear = localStorage.getItem('academicYear');
        const semester = localStorage.getItem('semester');
        const poMapped = localStorage.getItem('poMapped');
        const courseCoordinatorTitle = localStorage.getItem('courseCoordinatorTitle');
        const courseCoordinatorName = localStorage.getItem('courseCoordinatorName');
        const courseCoordinatorDesign = localStorage.getItem('courseCoordinatorDesign');
        const streamExpertTitle = localStorage.getItem('streamExpertTitle');
        const streamExpertName = localStorage.getItem('streamExpertName');
        const streamExpertDesign = localStorage.getItem('streamExpertDesign');
        const dateOfIntimation = localStorage.getItem('dateOfIntimation');
        const dateOfMeeting = localStorage.getItem('dateOfMeeting');
        const dateOfFormCompleted = localStorage.getItem('dateOfFormCompleted');
        
        // Populate the table with the retrieved data
        const table = document.querySelector('table');
        table.innerHTML += `
          <tr>
            <td>Course Code</td>
            <td>${courseCode}</td>
          </tr>
          <tr>
            <td>Course Title</td>
            <td>${courseTitle}</td>
          </tr>
          <tr>
            <td>Academic Year</td>
            <td>${academicYear}</td>
          </tr>
          <tr>
            <td>Semester</td>
            <td>${semester}</td>
          </tr>
          <tr>
            <td>POs Mapped</td>
            <td>${poMapped}</td>
          </tr>
          <tr>
            <td>Course Coordinator</td>
            <td>${courseCoordinatorTitle} ${courseCoordinatorName}</td>
          </tr>
          <tr>
            <td>Course Coordinator Designation</td>
            <td>${courseCoordinatorDesign}</td>
          </tr>
          <tr>
            <td>Stream Expert</td>
            <td>${streamExpertTitle} ${streamExpertName}</td>
          </tr>
          <tr>
            <td>Stream Expert Designation</td>
            <td>${streamExpertDesign}</td>
          </tr>
          <tr>
            <td>Date of Intimation</td>
            <td>${dateOfIntimation}</td>
          </tr>
          <tr>
            <td>Date of Meeting</td>
            <td>${dateOfMeeting}</td>
          </tr>
          <tr>
            <td>Date of Form Completed</td>
            <td>${dateOfFormCompleted}</td>
          </tr>
        `;
        
        // Clear localStorage after displaying the data (optional)
        localStorage.clear();
      </script>
    </table>
  </center>
  
  <div class="button-container">
    <button onclick="window.print();">Print</button>
  </div> 
</body>
</html>