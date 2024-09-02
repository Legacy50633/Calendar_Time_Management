<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV File Upload</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .upload-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .upload-container h1 {
            margin-bottom: 20px;
        }
        .upload-container input[type="file"] {
            margin-bottom: 10px;
        }
        .upload-container button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .upload-container button:hover {
            background-color: #0056b3;
        }
    </style>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="upload-container">
    <h1>Upload CSV File</h1>
    <form id="uploadForm" enctype="multipart/form-data">
        <input type="file" name="csv_file" id="csv_file" accept=".csv,.xlsx" required>
        <br>
        <button type="button" id="csvupload">Upload</button>
    </form>
</div>
<script>
    $(document).ready(function() {
        $('#csvupload').click(function() {
            const formData = new FormData($('#uploadForm')[0]);
            $.ajax({
                url: 'csv_upload.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert(response);
                },
                error: function() {
                    console.error('An error occurred while sending the data');
                }
            });
        });
    });
</script>
</body>
</html>
