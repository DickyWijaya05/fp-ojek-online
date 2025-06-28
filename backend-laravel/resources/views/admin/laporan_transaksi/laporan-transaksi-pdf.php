<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Transaksi</title>
  <style>
    body {
      font-family: sans-serif;
      font-size: 12px;
      margin: 20px;
      color: #333;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      border: 1px solid #aaa;
      padding: 8px 6px;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
    }
    .status-lunas {
      color: green;
      font-weight: bold;
    }
    .status-belum {
      color: orange;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <h2>Laporan Transaksi</h2>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Pelanggan</th>
        <th>Driver</th>
        <th>Tanggal</th>
        <th>Jarak (km)</th>
        <th>Tarif</th>
        <th>Metode</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($laporan as $item)
      <tr>
        <td>{{ $item['id'] }}</td>
        <td>{{ $item['pelanggan'] }}</td>
        <td>{{ $item['driver'] }}</td>
        <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y H:i') }}</td>
        <td>{{ number_format($item['jarak'], 1) }}</td>
        <td>Rp {{ number_format($item['tarif'], 0, ',', '.') }}</td>
        <td>{{ $item['metode'] }}</td>
        <td class="{{ $item['status'] === 'Lunas' ? 'status-lunas' : 'status-belum' }}">
          {{ $item['status'] }}
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
