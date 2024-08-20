<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Learning Checklist</title>
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

    @media print {
      header {
        position: fixed;
        width: 100%;
      }
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
  <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', (event) => {
    // Populate form fields with localStorage data
    document.querySelector('input[name="courseCoordinatorName"]').value = localStorage.getItem('courseCoordinatorName') || '';
    document.querySelector('input[name="courseCoordinatorName"]').disabled = true; // Disable input field
    document.querySelector('input[name="courseCoordinatorDesign"]').value = localStorage.getItem('courseCoordinatorDesign') || '';
    document.querySelector('input[name="courseCoordinatorDesign"]').disabled = true; // Disable input field
    document.querySelector('input[name="streamExpertName"]').value = localStorage.getItem('streamExpertName') || '';
    document.querySelector('input[name="streamExpertName"]').disabled = true; // Disable input field
    document.querySelector('input[name="streamExpertDesign"]').value = localStorage.getItem('streamExpertDesign') || '';
    document.querySelector('input[name="streamExpertDesign"]').disabled = true; // Disable input field

    // Initialize signature pads and manage signatures
    const courseCoordinatorCanvas = document.getElementById('courseCoordinatorSignaturePad');
    const streamExpertCanvas = document.getElementById('streamExpertSignaturePad');
    const courseCoordinatorSignaturePad = new SignaturePad(courseCoordinatorCanvas);
    const streamExpertSignaturePad = new SignaturePad(streamExpertCanvas);

    // Save signatures to localStorage on form submit
    document.querySelector('form').addEventListener('submit', (e) => {
      e.preventDefault();
      localStorage.setItem('courseCoordinatorSignature', courseCoordinatorSignaturePad.toDataURL());
      localStorage.setItem('streamExpertSignature', streamExpertSignaturePad.toDataURL());
    });

    // Load and display signatures from localStorage
    const courseCoordinatorSignature = localStorage.getItem('courseCoordinatorSignature');
    if (courseCoordinatorSignature) {
      const img = new Image();
      img.src = courseCoordinatorSignature;
      img.onload = () => courseCoordinatorSignaturePad.fromDataURL(courseCoordinatorSignature);
    }

    const streamExpertSignature = localStorage.getItem('streamExpertSignature');
    if (streamExpertSignature) {
      const img = new Image();
      img.src = streamExpertSignature;
      img.onload = () => streamExpertSignaturePad.fromDataURL(streamExpertSignature);
    }

    // Clear signature pads functionality
    document.getElementById('clearCourseCoordinatorSignature').addEventListener('click', () => {
      courseCoordinatorSignaturePad.clear();
    });

    document.getElementById('clearStreamExpertSignature').addEventListener('click', () => {
      streamExpertSignaturePad.clear();
    });
  });
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
  <h2><center>Learning Checklist</center></h2>
  <form method="post" enctype="multipart/form-data">
    <center>
      <table id="checklistTable">
        <tr>
          <th><center>Tasks</center></th>
          <th><center>Check (√)</center></th>
        </tr>
        <tr>
          <td colspan="2"><b><center>CO Appropriateness and Consistency</center></b></td>
        </tr>
        <tr>
          <td>Are they written using action verbs to specify definite observable behavior?</td>
          <td class="checkmark">√</td>
        </tr>
        <tr>
          <td>Does the language describe students’ rather than teachers' behavior?</td>
          <td class="checkmark">√</td>
        </tr>
        <tr>
          <td>Do the outcomes clearly describe and define the expected abilities knowledge values and attitudes of a students of the course?</td>
          <td class="checkmark">√</td>
        </tr>
        <tr>
          <td>Is it possible to collect accurate and reliable data for each outcome?</td>
          <td class="checkmark">√</td>
        </tr>
        <tr>
          <td>Have the Forbidden Four and Vague Qualifiers been avoided?</td>
          <td class="checkmark">√</td>
        </tr>
        <tr>
          <td>Has a proper assessment tool been adopted for the assessment?</td>
          <td class="checkmark">√</td>
        </tr>
        <tr>
          <td>Are they written as per Bloom’s HOT Skills?</td>
          <td class="checkmark">√</td>
        </tr>
        <tr>
          <td>Are the COs consistent with the POs and/or PSOs? (Reflective/words from POs)</td>
          <td class="checkmark">√</td>
        </tr>
        <tr>
          <td>Are the CO and PO/PSO Mapping and Justification appropriate?</td>
          <td class="checkmark">√</td>
        </tr>
        <tr>
          <td>Are the targets fixed scientifically?</td>
          <td class="checkmark">√</td>
        </tr>
        <tr>
          <td>If mapped to first 4 POs are complex engineering problems taken care?</td>
          <td class="checkmark">√</td>
        </tr>
        <tr>
          <td colspan="2"><b><center>Programme curriculum</center></b></td>
        </tr>
        <tr>
          <td>Are the comments/suggestion of the CAYm1 included effectively?</td>
          <td class="checkmark">√</td>
        </tr>
        <tr>
          <td>Has the curriculum gap identified been addressed effectively?</td>
          <td class="checkmark">√</td>
        </tr>
      </table>
    </center>
    <h3>Comments, if any:</h3>
    <textarea rows="3" name="comments" placeholder="Enter comments"></textarea>
    <br><br>
    <h3>Name of Course Co-ordinator:
      <input type="text" name="courseCoordinatorName" placeholder="Enter Course Co-ordinator Name" style="width: 30%;height:33px;">
    </h3>
    <h3>Designation:
      <input type="text" name="courseCoordinatorDesign" placeholder="Enter Course Co-ordinator Designation" style="width: 30%;height:33px;">
    </h3>
    <h3>Signature:</h3>
    <canvas id="courseCoordinatorSignaturePad" class="signature-pad" width="400" height="200" style="border: 1px solid #000;"></canvas>
    <button type="button" id="clearCourseCoordinatorSignature">Clear</button>
    <br><br>
    <h3>Stream Expert's Name:
      <input type="text" name="streamExpertName" placeholder="Enter Stream Expert Name" style="width: 30%; height:33px;">
    </h3>
    <h3>Designation:
      <input type="text" name="streamExpertDesign" placeholder="Enter Stream Expert Designation" style="width: 30%;height:33px;">
    </h3>
    <h3>Signature:
      <canvas id="streamExpertSignaturePad" class="signature-pad" width="400" height="200" style="border: 1px solid #000;"></canvas>
      <button type="button" id="clearStreamExpertSignature">Clear</button>
    </h3>
    <br><br>
    <div class="button-container">
      <button onclick="window.print();">Print</button>
    </div>
  </form>
</body>
</html>