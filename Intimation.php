<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Intimation Letter</title>
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
  <script>
    function loadFormData() {
      const fields = [
        'courseTitle', 'academicYear', 'semester', 'dateOfIntimation', 'dateOfMeeting', 'dateOfFormCompleted',
        'courseCoordinatorName', 'streamExpertName', 'outcome1', 'outcome2', 'outcome3', 'outcome4', 'outcome5'
      ];

      fields.forEach(field => {
        const elements = document.getElementsByName(field);
        if (elements.length > 0) {
          elements.forEach(element => {
            const value = localStorage.getItem(field);
            if (value !== null) {
              if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA' || element.tagName === 'SELECT') {
                element.value = value;
                element.setAttribute('readonly', true);
                if (element.tagName === 'SELECT') {
                  element.setAttribute('disabled', true);
                }
              }
            }
          });
        }
      });
    }

    window.onload = loadFormData;
  </script>
</head>
<body>
<header>
    <img src="image/logo.png" alt="Yenepoya Institute of Technology">
    <div>
      <h1>Yenepoya Institute of Technology</h1>
      <p>N. H. 13, Thodar, Moodbidri - 574 225, Mangaluru, D. K.</p>
    </div>
  </header>
  <h2><center>Department of Information Science & Engineering</center></h2>
  <p>
  <div class="left">
      <h4>
        Ref No: YIT/course title: <input type="text" name="courseTitle" style="width: 10%; height:33px;">
        /Course Meeting/Academic Year: <input type="text" name="academicYear" style="width: 10%; height:33px;">
        /<select name="SEM" style="width: 10%; height:33px;">
          <option value="even">Even</option>
          <option value="odd">Odd</option>
        </select>/
      </h4>
    </div>
    <div class="left">
        <h4>Date: <input type="date" style=" height:33px;" id="dateOfIntimation" name="dateOfIntimation" required></h4>
    </div>
  </p>
  <p><center><b>Intimation Letter</b></center></p>
  <p>
    Dear Sir/Madam,
  </p>
  <p>
    A meeting of Course coordinator of the course <input type="text" name="courseTitle" style="width: 10%; height:33px;"> and Subject expert is going to be convened on
    <input type="date" id="dateOfMeeting" style=" height:33px;" name="dateOfMeeting" required> in the department. All are hereby informed to attend.
  </p>
  <p>Agenda:<br><br>
    Agenda 1: Discussion on mapping of Course–Program Outcome and Program Specific Outcome and writing justification for the mapping.<br>
    Agenda 2: Discussion on the outcomes of the course.<br>
    Agenda 3: Discussion on mapping of the course outcomes to PO/PSO and writing justification for the mapping.<br>
    Agenda 4: Discussion on course plan.<br>
    Agenda 5: Discussion on the teaching and assessment strategies which ensure that the outcomes are achieved and demonstrated.<br>
    Agenda 6: Discussion on Internal test papers along with assessment methods and scheme of evaluation for each internal test.<br>
  </p>
  <br><br>
  <p>
    Course Coordinator<br>
    Department of ISE<br>
    YIT, Moodbidri<br>
  </p>
  <br><br><br>
  <p>
    Copy to:<br>
    1. Subject Expert<br>
    2. NBA Department Coordinator<br>
    3. Course Coordinator<br>
  </p>
  <div class="button-container">
    <button onclick="window.print();">Print</button>
  </div>
</body>
</html>
