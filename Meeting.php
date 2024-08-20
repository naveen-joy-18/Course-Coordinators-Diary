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
      footer{
        display:none;
      }
    }

    input[readonly], select[disabled] {
      background-color: #f0f0f0;
      cursor: not-allowed;
    }

    canvas {
      border: 1px solid #000;
      margin-bottom: 10px;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
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

      // Initialize signature pads
      const courseCoordinatorCanvas = document.getElementById('courseCoordinatorSignaturePad');
      const streamExpertCanvas = document.getElementById('streamExpertSignaturePad');
      const courseCoordinatorSignaturePad = new SignaturePad(courseCoordinatorCanvas);
      const streamExpertSignaturePad = new SignaturePad(streamExpertCanvas);

      // Load signatures from localStorage
      const courseCoordinatorSignature = localStorage.getItem('courseCoordinatorSignature');
      if (courseCoordinatorSignature) {
        courseCoordinatorSignaturePad.fromDataURL(courseCoordinatorSignature);
      }

      const streamExpertSignature = localStorage.getItem('streamExpertSignature');
      if (streamExpertSignature) {
        streamExpertSignaturePad.fromDataURL(streamExpertSignature);
      }

      // Function to clear signature pads
      document.getElementById('clearCourseCoordinator').addEventListener('click', function () {
        courseCoordinatorSignaturePad.clear();
      });

      document.getElementById('clearStreamExpert').addEventListener('click', function () {
        streamExpertSignaturePad.clear();
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
      <h4>Date: <input type="date" style="height:33px;" name="dateOfIntimation" required></h4>
    </div>
  </p>
  <h4><center>Subject Expert and Course Co-ordinator Meeting</center></h4> <br>
  <p>
    Minutes Meeting: <br><br>
    Meeting of Subject expert, Course coordinator of course title:
    <input type="text" name="courseTitle" style="width: 10%; height:33px;">
    Course of the program bachelor of Engineering was held in the department on  
    <input type="date" id="dateOfFormCompleted" style="width: 10%; height:33px;" name="dateOfFormCompleted" required><br>
    The following members were present: 
    <section><center>
      <table>
        <tr>
          <th>Sl No</th>
          <th>Name of the faculty</th>
          <th>Designation</th>
          <th>Signature</th>
        </tr>
        <tr>
          <td>1</td>
          <td><input type="text" name="courseCoordinatorName" style="width: 96%; height:35px;"></td>
          <td>Course Co-ordinator</td>
          <td>
            <canvas id="courseCoordinatorSignaturePad" class="signature-pad" width="400" height="200"></canvas>
            <button id="clearCourseCoordinator">Clear</button>
        </td>
        </tr>
        <tr>
          <td>2</td>
          <td><input type="text" name="streamExpertName" style="width: 96%; height:35px;"></td>
          <td>Subject Expert</td>
          <td>
            <canvas id="streamExpertSignaturePad" class="signature-pad" width="400" height="200"></canvas>
            <button id="clearStreamExpert">Clear</button>
        </td>
        </tr>
      </table>
    </center></section> <br><br>   
    <div class="content">
      <p>Following deliberations were made:</p>
      <p><strong>Agenda 1:</strong> Discussion on mapping of Course-Program Outcome and Program Specific Outcome</p>
      <p><strong>Resolution 1:</strong>
        Course coordinator: <input type="text" style="width: 10%; height:33px;" name="courseCoordinatorName">
        suggested Program Outcome
        <select style="width: 10%; height:33px;">
          <option value="PO1">PO1</option>
          <option value="PO2">PO2</option>
          <option value="PO3">PO3</option>
          <option value="PO4">PO4</option>
          <option value="PO5">PO5</option>
        </select>
        Subject expert: <input type="text" style="width: 10%; height:33px;" name="streamExpertName">
        agreed for the same.
      </p>
      <p><strong>Agenda 2:</strong> Discussion on the outcomes of the course</p>
      <p><strong>Resolution 2:</strong>
        Course coordinator: <input type="text" name="courseCoordinatorName" style="width: 10%; height:33px;">
        presented the course outcomes of the course.
      </p>
    </div> 
    <div class="content">
      <p>The outcomes are :</p>
      <center>
        <table>
          <tr>
            <th>Course Outcome</th>
            <th>Description</th>
          </tr>
          <tr>
            <td>CO1</td>
            <td><input type="text" name="outcome1" style="width: 96%; height:33px;" placeholder="Enter the CO's"></td>
          </tr>
          <tr>
            <td>CO2</td>
            <td><input type="text" name="outcome2"style="width: 96%; height:33px;" placeholder="Enter the CO's"></td>
          </tr>
          <tr>
            <td>CO3</td>
            <td><input type="text" name="outcome3" style="width: 96%; height:33px;" placeholder="Enter the CO's"></td>
          </tr>
          <tr>
            <td>CO4</td>
            <td><input type="text" name="outcome4" style="width: 96%; height:33px;" placeholder="Enter the CO's"></td>
          </tr>
          <tr>
            <td>CO5</td>
            <td><input type="text" name="outcome5" style="width: 96%; height:33px;" placeholder="Enter the CO's"></td>
          </tr>
        </table>
      </center>
    </div>
    <div class="content">
        <p><strong>Agenda 3:</strong>Discussion on mapping of the course outcomes to PO/PSO and writing justification for each CO-PO mapping.</p>
        <p><strong>Resolution 3:</strong>
        Course coordinator presented the mapping of course outcomes and justifications
        for the subject <input type="text" name="courseTitle" style="width: 10%; height:33px;">
        <p><strong>Agenda 4:</strong>Discussion on course plan.</p>
        <p><strong>Resolution 4:</strong>Course plan of the course 
        <input type="text" name="courseTitle" style="width: 10%; height:33px;"> was presented by course coordinator.
        <p><strong>Agenda 5:</strong>Discussion on the teaching and assessment strategies which ensure that the outcomes are achieved and demonstrated.</p>
        <p><strong>Resolution 5:</strong>is decided in the meeting that following teaching and assessment tools is going to be incorporated for the course 
        <input type="text" name="courseTitle" style="width: 10%; height:33px;">
        <br>
        <script>
           function handleImageUpload(event) {
            const reader = new FileReader();
            reader.onload = function () {
              const imgElement = document.getElementById('uploadedImage');
              imgElement.src = reader.result;
              imgElement.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
          }
        </script>
        <div class="button-container">
        <label for="imageUpload">Upload Image:</label>
        <input type="file" id="imageUpload" accept="image/*" onchange="handleImageUpload(event)">
    </div>
    <div class="center">
        <img id="uploadedImage" src="" alt="Uploaded Image" style="display:none; width:10%; height:auto;">
    </div>
        <p><strong>Agenda 6:</strong> Discussion on Internal Exam papers and marking schemes for all the 3 internals.</p>
        <p><strong>Resolution 6:</strong>
        It is decided in the meeting that for each internal test 2 modules will be included and referring to the VTU semester end examination question papers, internal question papers will be set.
    </div>   
    <div class="button-container">
        <button onclick="window.print()">Print</button>
    </div>
</p>
</body>
</html>
