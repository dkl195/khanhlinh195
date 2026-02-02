<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bảng cửu chương</title>
    <style>
        table {
            border-collapse: collapse;
        }
        td, th {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<table>
    <tr>
        <th>Bảng</th>
        <?php
        for ($i = 1; $i <= 9; $i++) {
            echo "<th>$i</th>";
        }
        ?>
    </tr>

    <?php
    for ($i = 2; $i <= 9; $i++) {
        echo "<tr>";
        echo "<th>$i</th>";
        for ($j = 1; $j <= 9; $j++) {
            echo "<td>" . ($i * $j) . "</td>";
        }
        echo "</tr>";
    }
    ?>
</table>

</body>
</html>
