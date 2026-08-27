<html>
    <head>
        <title>My App</title>
    </head>
    <body>
        <h1>Welcome to My App</h1>
        @foreach ($produk as $data )
        <h2>{{ $data->nama_barang }}</h2>
        <p>Price: {{ $data->harga_barang }}</p>
        <p>Category: {{ $data->kategori->nama_kategori }}</p>
        @endforeach
    </body>
</html>