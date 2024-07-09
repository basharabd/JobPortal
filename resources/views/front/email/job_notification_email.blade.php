<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Job Notification Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .header {
            text-align: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            line-height: 1.6;
        }
        .content h1 {
            color: #333;
        }
        .details {
            background: #f9f9f9;
            padding: 10px;
            border-left: 5px solid #007bff;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            color: #888;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">

        <div class="content">
            <h1>Hello {{$mailData['employer']->name}}</h1>
            <p>We have a new job application for the position of <strong>{{$mailData['job']->title}}</strong>.</p>

            <div class="details">
                <h2>Employee Details:</h2>
                <p><strong>Name:</strong> {{$mailData['user']->name}}</p>
                <p><strong>Email:</strong> {{$mailData['user']->email}}</p>
                <p><strong>Mobile No:</strong> {{$mailData['user']->mobile}}</p>
            </div>
        </div>
        <div class="footer">
            <p>&copy; 2024 Job Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
