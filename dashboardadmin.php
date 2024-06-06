<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update']) || isset($_POST['add']) || isset($_POST['delete'])) {
        $type = $_POST['type'];
        $valid_types = ['operator', 'manager', 'ruangan', 'alat'];

        if (in_array($type, $valid_types)) {
            if (isset($_POST['delete'])) {
                $id = $_POST['id'];
                $sql = "DELETE FROM $type WHERE id_$type = $id";
            } else {
                $fields = [];
                $values = [];
                foreach ($_POST as $key => $value) {
                    if ($key != 'type' && $key != 'id' && $key != 'update' && $key != 'add') {
                        $fields[] = $key;
                        $values[] = "'$value'";
                    }
                }

                if (isset($_POST['update'])) {
                    $id = $_POST['id'];
                    $updateFields = [];
                    foreach ($_POST as $key => $value) {
                        if ($key != 'type' && $key != 'id' && $key != 'update') {
                            $updateFields[] = "$key = '$value'";
                        }
                    }

                    $updateFields = implode(', ', $updateFields);
                    $sql = "UPDATE $type SET $updateFields WHERE id_$type = $id";
                } else {
                    $fields = implode(', ', $fields);
                    $values = implode(', ', $values);
                    $sql = "INSERT INTO $type ($fields) VALUES ($values)";
                }
            }

            if ($conn->query($sql) === TRUE) {
                $message = isset($_POST['update']) ? "Record updated successfully" : (isset($_POST['add']) ? "New record created successfully" : "Record deleted successfully");
            } else {
                $message = "Error: " . $conn->error;
            }

            // Redirect to the same page to prevent form resubmission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $message = "Invalid type";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Operator dan Manager</title>
    <link rel="stylesheet" href="dashboardadmin.css">
    <script>
        function editRow(rowId, type) {
            var row = document.getElementById(rowId);
            var cells = row.getElementsByTagName('td');

            for (var i = 1; i < cells.length - 1; i++) {
                var cellContent = cells[i].innerText;
                var dataOriginalValue = cells[i].getAttribute('data-original-value');
                cells[i].innerHTML = '<input name="' + cells[i].getAttribute('data-name') + '" type="text" value="' + cellContent + '" data-original-value="' + dataOriginalValue + '">';
            }

            var actionCell = cells[cells.length - 1];
            actionCell.innerHTML = '<button onclick="saveRow(\'' + rowId + '\', \'' + type + '\')">Save</button>';
            actionCell.innerHTML += '<button onclick="cancelEdit(\'' + rowId + '\', \'' + type + '\')">Cancel</button>';
        }

        function saveRow(rowId, type) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';

            var row = document.getElementById(rowId);
            var cells = row.getElementsByTagName('td');

            for (var i = 1; i < cells.length - 1; i++) {
                var input = cells[i].getElementsByTagName('input')[0];
                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = input.name;
                hiddenInput.value = input.value;
                form.appendChild(hiddenInput);
            }

            var hiddenType = document.createElement('input');
            hiddenType.type = 'hidden';
            hiddenType.name = 'type';
            hiddenType.value = type;
            form.appendChild(hiddenType);

            var hiddenId = document.createElement('input');
            hiddenId.type = 'hidden';
            hiddenId.name = 'id';
            hiddenId.value = rowId.split('-')[1];
            form.appendChild(hiddenId);

            var hiddenUpdate = document.createElement('input');
            hiddenUpdate.type = 'hidden';
            hiddenUpdate.name = 'update';
            hiddenUpdate.value = '1';
            form.appendChild(hiddenUpdate);

            document.body.appendChild(form);
            form.submit();
        }

        function cancelEdit(rowId, type) {
            var row = document.getElementById(rowId);
            var cells = row.getElementsByTagName('td');
            
            for (var i = 1; i < cells.length - 1; i++) {
                cells[i].innerHTML = cells[i].getAttribute('data-original-value');
            }

            var actionCell = cells[cells.length - 1];
            actionCell.innerHTML = '<button onclick="editRow(\'' + rowId + '\', \'' + type + '\')">Edit</button>';
            actionCell.innerHTML += '<button onclick="deleteRow(\'' + rowId + '\', \'' + type + '\')">Delete</button>';
        }

        function addRow(type) {
            var table = document.getElementById(type + '-table');
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount - 1);
            row.id = 'new-' + type;

            var columns = table.rows[0].cells.length;

            for (var i = 1; i < columns - 1; i++) {
                var cell = row.insertCell(i);
                var name = table.rows[0].cells[i].getAttribute('data-name');
                cell.innerHTML = '<input name="' + name + '" type="text">';
            }

            var actionCell = row.insertCell(columns - 1);
            actionCell.innerHTML = '<button onclick="saveNewRow(\'' + type + '\')">Save</button>';
            actionCell.innerHTML += '<button onclick="cancelNewRow(\'' + type + '\')">Cancel</button>';
        }

        function saveNewRow(type) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';

            var row = document.getElementById('new-' + type);
            var cells = row.getElementsByTagName('td');

            for (var i = 1; i < cells.length - 1; i++) {
                var input = cells[i].getElementsByTagName('input')[0];
                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = input.name;
                hiddenInput.value = input.value;
                form.appendChild(hiddenInput);
            }

            var hiddenType = document.createElement('input');
            hiddenType.type = 'hidden';
            hiddenType.name = 'type';
            hiddenType.value = type;
            form.appendChild(hiddenType);

            var hiddenAdd = document.createElement('input');
            hiddenAdd.type = 'hidden';
            hiddenAdd.name = 'add';
            hiddenAdd.value = '1';
            form.appendChild(hiddenAdd);

            document.body.appendChild(form);
            form.submit();
        }

        function cancelNewRow(type) {
            var table = document.getElementById(type + '-table');
            var row = document.getElementById('new-' + type);
            table.deleteRow(row.rowIndex);
        }

        function deleteRow(rowId, type) {
            if (confirm("Are you sure you want to delete this row?")) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';

                var hiddenType = document.createElement('input');
                hiddenType.type = 'hidden';
                hiddenType.name = 'type';
                hiddenType.value = type;
                form.appendChild(hiddenType);

                var hiddenId = document.createElement('input');
                hiddenId.type = 'hidden';
                hiddenId.name = 'id';
                hiddenId.value = rowId.split('-')[1];
                form.appendChild(hiddenId);

                var hiddenDelete = document.createElement('input');
                hiddenDelete.type = 'hidden';
                hiddenDelete.name = 'delete';
                hiddenDelete.value = '1';
                form.appendChild(hiddenDelete);

                document.body.appendChild(form);
                form.submit();
            }
        }

        window.onload = function() {
            var rows = document.querySelectorAll('table tr');
            rows.forEach(function(row) {
                var cells = row.getElementsByTagName('td');
                for (var i = 1; i < cells.length - 1; i++) {
                    cells[i].setAttribute('data-original-value', cells[i].innerText);
                }
            });
        };
    </script>
</head>
<body>
    <h2>Data Operator</h2>
    <table id="operator-table" border="1">
        <tr>
            <th>ID Operator</th>
            <th data-name="nama">Nama</th>
            <th data-name="username">Username</th>
            <th data-name="password">Password</th>
            <th data-name="email">Email</th>
            <th data-name="no_tlp">No. Telepon</th>
            <th data-name="alamat">Alamat</th>
            <th>Action</th>
        </tr>
        <?php
        $sql = "SELECT id_operator, nama, username, password, email, no_tlp, alamat FROM operator";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr id='operator-" . $row["id_operator"] . "'>";
                echo "<td>" . $row["id_operator"] . "</td>";
                echo "<td data-name='nama'>" . $row["nama"] . "</td>";
                echo "<td data-name='username'>" . $row["username"] . "</td>";
                echo "<td data-name='password'>" . $row["password"] . "</td>";
                echo "<td data-name='email'>" . $row["email"] . "</td>";
                echo "<td data-name='no_tlp'>" . $row["no_tlp"] . "</td>";
                echo "<td data-name='alamat'>" . $row["alamat"] . "</td>";
                echo "<td><button onclick=\"editRow('operator-" . $row["id_operator"] . "', 'operator')\">Edit</button> <button onclick=\"deleteRow('operator-" . $row["id_operator"] . "', 'operator')\">Delete</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Tidak ada data</td></tr>";
        }
        ?>
    </table>
   

    <h2>Data Manager</h2>
    <table id="manager-table" border="1">
        <tr>
            <th>ID Manager</th>
            <th data-name="nama_manager">Nama</th>
            <th data-name="username">Username</th>
            <th data-name="password">Password</th>
            <th data-name="email">Email</th>
            <th data-name="no_tlp">No. Telepon</th>
            <th data-name="alamat">Alamat</th>
            <th>Action</th>
        </tr>
        <?php
        $sql_manager = "SELECT id_manager, nama_manager, username, password, email, no_tlp, alamat FROM manager";
        $result_manager = $conn->query($sql_manager);

        if ($result_manager->num_rows > 0) {
            while($row_manager = $result_manager->fetch_assoc()) {
                echo "<tr id='manager-" . $row_manager["id_manager"] . "'>";
                echo "<td>" . $row_manager["id_manager"] . "</td>";
                echo "<td data-name='nama_manager'>" . $row_manager["nama_manager"] . "</td>";
                echo "<td data-name='username'>" . $row_manager["username"] . "</td>";
                echo "<td data-name='password'>" . $row_manager["password"] . "</td>";
                echo "<td data-name='email'>" . $row_manager["email"] . "</td>";
                echo "<td data-name='no_tlp'>" . $row_manager["no_tlp"] . "</td>";
                echo "<td data-name='alamat'>" . $row_manager["alamat"] . "</td>";
                echo "<td><button onclick=\"editRow('manager-" . $row_manager["id_manager"] . "', 'manager')\">Edit</button> <button onclick=\"deleteRow('manager-" . $row_manager["id_manager"] . "', 'manager')\">Delete</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Tidak ada data</td></tr>";
        }
        ?>
    </table>
   
    <h2>Data Ruangan</h2>
    <table id="ruangan-table" border="1">
        <tr>
            <th>ID Ruangan</th>
            <th data-name="nama_ruangan">Nama Ruangan</th>
            <th data-name="kapasitas">Kapasitas</th>
            <th data-name="status">Status</th>
            <th data-name="harga_ruangan">Harga Ruangan</th>
            <th>Action</th>
        </tr>
        <?php
        $sql_ruangan = "SELECT id_ruangan, nama_ruangan, kapasitas, status, harga_ruangan FROM ruangan";
        $result_ruangan = $conn->query($sql_ruangan);

        if ($result_ruangan->num_rows > 0) {
            while($row_ruangan = $result_ruangan->fetch_assoc()) {
                echo "<tr id='ruangan-" . $row_ruangan["id_ruangan"] . "'>";
                echo "<td>" . $row_ruangan["id_ruangan"] . "</td>";
                echo "<td data-name='nama_ruangan'>" . $row_ruangan["nama_ruangan"] . "</td>";
                echo "<td data-name='kapasitas'>" . $row_ruangan["kapasitas"] . "</td>";
                echo "<td data-name='status'>" . $row_ruangan["status"] . "</td>";
                echo "<td data-name='harga_ruangan'>" . $row_ruangan["harga_ruangan"] . "</td>";
                echo "<td><button onclick=\"editRow('ruangan-" . $row_ruangan["id_ruangan"] . "', 'ruangan')\">Edit</button> <button onclick=\"deleteRow('ruangan-" . $row_ruangan["id_ruangan"] . "', 'ruangan')\">Delete</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Tidak ada data</td></tr>";
        }
        ?>
    </table>
    

    <h2>Data Alat</h2>
    <table id="alat-table" border="1">
        <tr>
            <th>ID Alat</th>
            <th data-name="nama_alat">Nama Alat</th>
            <th data-name="quantity">Quantity</th>
            <th data-name="status">Status</th>
            <th data-name="harga_alat">Harga Alat</th>
            <th>Action</th>
        </tr>
        <?php
        $sql_alat = "SELECT id_alat, nama_alat, quantity, status, harga_alat FROM alat";
        $result_alat = $conn->query($sql_alat);

        if ($result_alat->num_rows > 0) {
            while($row_alat = $result_alat->fetch_assoc()) {
                echo "<tr id='alat-" . $row_alat["id_alat"] . "'>";
                echo "<td>" . $row_alat["id_alat"] . "</td>";
                echo "<td data-name='nama_alat'>" . $row_alat["nama_alat"] . "</td>";
                echo "<td data-name='quantity'>" . $row_alat["quantity"] . "</td>";
                echo "<td data-name='status'>" . $row_alat["status"] . "</td>";
                echo "<td data-name='harga_alat'>" . $row_alat["harga_alat"] . "</td>";
                echo "<td><button onclick=\"editRow('alat-" . $row_alat["id_alat"] . "', 'alat')\">Edit</button> <button onclick=\"deleteRow('alat-" . $row_alat["id_alat"] . "', 'alat')\">Delete</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Tidak ada data</td></tr>";
        }
        ?>
    </table>
   
    <form action="loginadmin.php" method="post">
        <input type="submit" value="Logout" class="logout-button">
    </form>
</body>
</html>

<?php $conn->close(); ?>
