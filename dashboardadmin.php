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

            for (var i = 0; i < columns - 1; i++) {
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

            for (var i = 0; i < cells.length - 1; i++) {
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
                    cells[i].setAttribute('data-original-value', cells[i].innerHTML);
                }
            });
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Data Operator dan Manager</h1>
        <div class="message"><?= isset($message) ? $message : '' ?></div>

        <?php
        $types = ['operator', 'manager', 'ruangan', 'alat'];

        foreach ($types as $type) {
            $sql = "SELECT * FROM $type";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<h2>" . ucfirst($type) . "</h2>";
                echo "<table id='$type-table'>";
                echo "<tr>";
                $fieldNames = [];
                while ($fieldInfo = $result->fetch_field()) {
                    $fieldName = $fieldInfo->name;
                    $fieldNames[] = $fieldName;
                    echo "<th data-name='$fieldName'>$fieldName</th>";
                }
                echo "<th>Actions</th>";
                echo "</tr>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr id='{$type}-{$row["id_$type"]}'>";
                    foreach ($fieldNames as $fieldName) {
                        echo "<td data-name='$fieldName'>{$row[$fieldName]}</td>";
                    }
                    echo "<td>";
                    echo "<button onclick=\"editRow('{$type}-{$row["id_$type"]}', '$type')\">Edit</button>";
                    echo "<button onclick=\"deleteRow('{$type}-{$row["id_$type"]}', '$type')\">Delete</button>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "<button onclick=\"addRow('$type')\">Add</button>";
            } else {
                echo "No data found for $type.";
            }
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
