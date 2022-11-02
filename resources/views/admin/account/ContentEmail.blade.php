<!DOCTYPE html>
<html>
<head>
    <title>ItsolutionStuff.com</title>
</head>
<body>
<h1>{{ $mailData['title'] }}</h1>
<p>{{ $mailData['body'] }}</p>

<p>{{'Mật khẩu của bạn là : '  }}  <span>{{$mailData['password']}}</span>  {{' - chức vụ hiện tại là : ' . $mailData['chucvu'] }}</p>

<p>Thank you</p>
</body>
</html>
