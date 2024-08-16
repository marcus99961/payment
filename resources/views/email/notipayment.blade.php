<!DOCTYPE html>
<html>
<head>
    <title>This mail is from Payment System, Inya Lake Hotel</title>
    <style>
    table, th, td {
  border: 1px solid black;
  padding: 3px;
  border-collapse: collapse;
}
</style>
</head>
<body>

<h3>Payment Description - {{$request['description']}}</h3>
Dear AP/Finance,</br>
Please raise this payment that I prepare. This payment amount is {{ $request['amount'] }} to Supplier Name - {{ $request['supplier'] }}. </br>
You can go and find in <a href="http://payment.ilh">http://payment.ilh</a> with Admin Network. </br>
</body>
</html>

