<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Starbattle</title>
    <style>
        table {
            border-collapse: collapse;
        }
        td {
            border: 1px solid black;
            width: 100px;
            height: 100px;
            text-align: center;
            font-size: 70px;
        }
    </style>
</head>
<body>      
    <script>
         const source = new EventSource('data.php');
         const lastTriedCell = { row: 0, col: 0 };

        // Listen for incoming messages and update the page
        source.onmessage = function(event) {
            const data = JSON.parse(event.data);

            switch(data.mode) {
                case 'grid':
                    drawPlayground(data.grid);
                    break;
                case 'done':
                    setCell('', lastTriedCell.row, lastTriedCell.col);
                    source.close();
                    break;
                case 'try':
                    setCell('', lastTriedCell.row, lastTriedCell.col);
                    lastTriedCell.row = data.row;
                    lastTriedCell.col = data.col;
                    setCell('?', data.row, data.col);
                    break;
                default:
                    setCell(data.mode, data.row, data.col);
            }
          
        };

        // Handle any errors with the connection
        source.onerror = function(event) {
            console.error("SSE connection error", event);
            source.close();
        };

        function setCell(mode, row, col) {
            const cell = document.getElementById(`${row}_${col}`);
            cell.textContent = mode;
        }

        function drawPlayground(grid) {
            const size = grid.length;
            const colors = [ 'red', 'green', 'blue', 'yellow', 'purple', 'orange', 'pink', 'brown', 'cyan'];
            const table = document.createElement('table');
            for (let i = 0; i < size; i++) {
                const row = document.createElement('tr');
                for (let j = 0; j < size; j++) {
                    const cell = document.createElement('td');
                    cell.id = `${i}_${j}`;
                    cell.style.backgroundColor = colors[ grid[i][j] ];

                    row.appendChild(cell);
                }
                table.appendChild(row);
            }
            document.body.appendChild(table);
        }
    </script>
</body>
</html>