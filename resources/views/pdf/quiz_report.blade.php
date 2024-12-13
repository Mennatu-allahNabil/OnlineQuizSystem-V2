<!DOCTYPE html>
<html>
<head>
    <title>Quiz Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        main{
            width: 98%;
            height: 100%;
            margin-inline:auto;
            padding-inline: 50px;
        }
        body{
            margin: 0;
            padding: 20px;
            border: 2px dashed #0a53be;
        }
        .text-danger{
            color: red;
        }

    </style>
</head>
<body>
   <main>
          <h1 class="title">Quiz Report</h1>
          <h2>Quiz Title: <span>{{ $quizTitle }}</span></h2>

          <h4>Average Passing Percentage : {{$passPercent}} %</h4>
          @if($results->count())
              <table>
                  <thead>
                  <tr>
                      <th>User ID</th>
                      <th>User Email</th>
                      <th>Score(%)</th>
                      <th>Taken At</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($results as $result)
                      <tr>
                          <td>{{ $result->user_id }}</td>
                          <td>{{ $result->email }}</td>
                          <td>{{ $result->score }}</td>
                          <td>{{ $result->created_at_formatted }}</td>
                      </tr>
                  @endforeach
                  </tbody>
              </table>
          @else
              <h4 class="text-danger"> No Quiz Takers For This Quiz!</h4>
          @endif
   </main>
</body>
</html>
