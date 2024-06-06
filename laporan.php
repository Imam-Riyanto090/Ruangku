<?php
include 'config.php'; // Sesuaikan dengan file konfigurasi Anda

// Query untuk mengambil data dari tabel transaksi untuk pendapatan total
$query_total = "SELECT DATE_FORMAT(tanggal_transaksi, '%d') AS tanggal_transaksi, SUM(harga_total) AS total_harga FROM transaksi GROUP BY DATE(tanggal_transaksi)";
$result_total = mysqli_query($conn, $query_total);

$data_total = array();
while ($row_total = mysqli_fetch_assoc($result_total)) {
    $data_total[] = $row_total;
}

$data_total_json = json_encode($data_total);

// Query untuk mengambil data dari tabel transaksi untuk pendapatan alat
$query_transaksi = "SELECT DATE_FORMAT(tanggal_transaksi, '%Y-%m-%d') AS tanggal_transaksi, alat FROM transaksi";
$result_transaksi = mysqli_query($conn, $query_transaksi);

$data_alat = array();

// Menghitung pendapatan alat berdasarkan tanggal transaksi
while ($row_transaksi = mysqli_fetch_assoc($result_transaksi)) {
    $tanggal_transaksi = $row_transaksi['tanggal_transaksi'];
    $alat_list = explode(', ', $row_transaksi['alat']);

    if (!isset($data_alat[$tanggal_transaksi])) {
        $data_alat[$tanggal_transaksi] = 0;
    }

    foreach ($alat_list as $alat) {
        // Query untuk mendapatkan harga alat berdasarkan nama alat
        $query_harga = "SELECT harga_alat FROM alat WHERE nama_alat = '$alat'";
        $result_harga = mysqli_query($conn, $query_harga);
        if ($row_harga = mysqli_fetch_assoc($result_harga)) {
            // Menambahkan @ untuk menonaktifkan pesan error
            @$data_alat[$tanggal_transaksi] += $row_harga['harga_alat'];
        }
    }
}

$data_alat_json = json_encode($data_alat);

// Query untuk mengambil data dari tabel transaksi untuk pendapatan ruangan
$query_ruangan = "SELECT DATE_FORMAT(tanggal_transaksi, '%Y-%m-%d') AS tanggal_transaksi, n_ruangan, durasi_sewa FROM transaksi";
$result_ruangan = mysqli_query($conn, $query_ruangan);

$data_ruangan = array();

// Menghitung pendapatan ruangan berdasarkan tanggal transaksi
while ($row_ruangan = mysqli_fetch_assoc($result_ruangan)) {
    $tanggal_transaksi = $row_ruangan['tanggal_transaksi'];
    $n_ruangan = $row_ruangan['n_ruangan'];
    $durasi_sewa = intval($row_ruangan['durasi_sewa']); // Konversi durasi_sewa ke nilai numerik

    // Query untuk mendapatkan harga ruangan berdasarkan nama ruangan
    $query_harga_ruangan = "SELECT harga_ruangan FROM ruangan WHERE nama_ruangan = '$n_ruangan'";
    $result_harga_ruangan = mysqli_query($conn, $query_harga_ruangan);
    if ($row_harga_ruangan = mysqli_fetch_assoc($result_harga_ruangan)) {
        $harga_ruangan = $row_harga_ruangan['harga_ruangan'];
        $pendapatan_ruangan = $harga_ruangan * $durasi_sewa;

        if (!isset($data_ruangan[$tanggal_transaksi])) {
            $data_ruangan[$tanggal_transaksi] = 0;
        }
        $data_ruangan[$tanggal_transaksi] += $pendapatan_ruangan;
    }
}

$data_ruangan_json = json_encode($data_ruangan);
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
    <h1>Grafik Pendapatan Alat</h1>
    <canvas id="alatChart" width="800" height="400"></canvas>
    <h1>Grafik Pendapatan Ruangan</h1>
    <canvas id="ruanganChart" width="800" height="400"></canvas>

    <script>
    // Mengambil data dari PHP menggunakan AJAX
    var data_total_json = <?php echo $data_total_json; ?>;
    var data_alat_json = <?php echo $data_alat_json; ?>;
    var data_ruangan_json = <?php echo $data_ruangan_json; ?>;
    
    // Memisahkan tanggal_transaksi dan total_harga dari data total
    var labels_total = data_total_json.map(function(item) {
        return item.tanggal_transaksi;
    });
    var values_total = data_total_json.map(function(item) {
        return item.total_harga;
    });

    // Membuat grafik untuk pendapatan total menggunakan Chart.js
    var ctx_total = document.getElementById('transaksiChart').getContext('2d');
    var transaksiChart = new Chart(ctx_total, {
        type: 'line',
        data: {
            labels: labels_total,
            datasets: [{
                label: 'Pendapatan Total', // Judul sumbu y
                data: values_total,
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
                        labelString: 'Pendapatan Total' // Judul sumbu y
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

    // Memproses data untuk grafik pendapatan alat
    var labels_alat = Object.keys(data_alat_json);
    var values_alat = Object.values(data_alat_json);

    // Membuat grafik untuk pendapatan alat menggunakan Chart.js
    var ctx_alat = document.getElementById('alatChart').getContext('2d');
    var alatChart = new Chart(ctx_alat, {
        type: 'line',
        data: {
            labels: labels_alat,
            datasets: [{
                label: 'Pendapatan Alat', // Judul sumbu y
                data: values_alat,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Pendapatan Alat' // Judul sumbu y
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

    // Memproses data untuk grafik pendapatan ruangan
    var labels_ruangan = Object.keys(data_ruangan_json);
    var values_ruangan = Object.values(data_ruangan_json);

    // Membuat grafik untuk pendapatan ruangan menggunakan Chart.js
    var ctx_ruangan = document.getElementById('ruanganChart').getContext('2d');
    var ruanganChart = new Chart(ctx_ruangan, {
        type: 'line',
        data: {
            labels: labels_ruangan,
            datasets: [{
                label: 'Pendapatan Ruangan', // Judul sumbu y
                data: values_ruangan,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Pendapatan Ruangan' // Judul sumbu y
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
    <form action="dashboardmanager.php" method="get">
        <button type="submit">Kembali ke Dashboard</button>
    </form>
</body>
</html>
