<?php
include 'config.php'; // Sesuaikan dengan file konfigurasi Anda

// Query untuk mengambil data dari tabel transaksi
$query = "SELECT DATE_FORMAT(tanggal_transaksi, '%d') AS tanggal_transaksi, SUM(harga_total) AS total_harga FROM transaksi GROUP BY DATE(tanggal_transaksi)";
$result = mysqli_query($conn, $query);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

$data_json = json_encode($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="laporan.css">
</head>
<body>
    <h1>Grafik Transaksi</h1>
    <canvas id="transaksiChart" width="800" height="400"></canvas>

    <script>
    // Mengambil data dari PHP menggunakan AJAX
    var data_json = <?php echo $data_json; ?>;
    
    // Memisahkan tanggal_transaksi dan total_harga dari data
    var labels = data_json.map(function(item) {
        return item.tanggal_transaksi;
    });
    var values = data_json.map(function(item) {
        return item.total_harga;
    });

    // Membuat grafik menggunakan Chart.js
    var ctx = document.getElementById('transaksiChart').getContext('2d');
    var transaksiChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan', // Judul sumbu y
                data: values,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Pendapatan' // Judul sumbu y
                    },
                    ticks: {
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Tanggal' // Judul sumbu x
                    },
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 20, // Maksimal jumlah label yang ditampilkan
                        maxRotation: 45 // Rotasi label maksimal
                    }
                }]
            }
        }
    });
    </script>
</body>
</html>
