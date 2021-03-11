<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction</title>
</head>

<body>
    <form method="post" action="/action-transaction">
        @csrf()
        <label for="">Nama barang</label>
        <input name="name" type="text" value="sampo">
        <label for="">Jumblah</label>
        <input name="qty" type="number" value="2">
        <label for="">harga</label>
        <input name="price" type="number" value="500000">
        <button type="submit">Submit</button>
    </form>
</body>

<table border="1" cellspading="10">
    <tr>
        <th>id</th>
        <th>Name</th>
        <th>qty</th>
        <th>price</th>
        <th>action</th>
    </tr>
    @foreach($data as $dt)
    <tr>
        <td>{{$dt['id']}}</td>
        <td>{{$dt['name']}}</td>
        <td>{{$dt['qty']}}</td>
        <td>{{$dt['price']}}</td>
        <td>
            <a href="/delete/{{$dt['id']}}">delete</a>
        </td>
    </tr>
    @endforeach
</table>

</html>