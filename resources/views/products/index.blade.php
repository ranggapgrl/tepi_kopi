<!DOCTYPE html>
<html>
<head>
    <title>Daftar Produk</title>
</head>
<body>

<h2>Daftar Produk Tepi Kopi</h2>

<a href="{{ route('products.create') }}">
    Tambah Produk
</a>

<table border="1" cellpadding="10">

<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Kategori</th>
    <th>Harga</th>
    <th>Stok</th>
</tr>

@foreach($products as $product)

<tr>

<td>{{ $loop->iteration }}</td>

<td>{{ $product->name }}</td>

<td>{{ $product->category->name }}</td>

<td>Rp {{ number_format($product->price) }}</td>

<td>{{ $product->stock }}</td>

</tr>

@endforeach

</table>

</body>
</html>