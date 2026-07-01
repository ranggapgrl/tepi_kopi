<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>
</head>
<body>

<h2>Tambah Produk</h2>

<form action="{{ route('products.store') }}" method="POST">
    @csrf

    <label>Nama Produk</label><br>
    <input type="text" name="name"><br><br>

    <label>Kategori</label><br>
    <select name="category_id">
        @foreach($categories as $category)
            <option value="{{ $category->id }}">
                {{ $category->name }}
            </option>
        @endforeach
    </select><br><br>

    <label>Deskripsi</label><br>
    <textarea name="description"></textarea><br><br>

    <label>Harga</label><br>
    <input type="number" name="price"><br><br>

    <label>Stok</label><br>
    <input type="number" name="stock"><br><br>

    <button type="submit">
        Simpan
    </button>

</form>

</body>
</html>