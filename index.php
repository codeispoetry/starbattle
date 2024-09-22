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
    <div style="display: flex">
        <div id="fieldsTested" style="margin-right: 40px"></div> 
        <div id="starsPlaced"></div>   
    </div>
    <script>
         const source = new EventSource('data.php');
         const lastTriedCell = { row: -1, col: 0 };
         let fieldsTested = 0;
         let starsPlaced = 0;

        // Listen for incoming messages and update the page
        source.onmessage = function(event) {
            const data = JSON.parse(event.data);

            switch(data.mode) {
                case 'grid':
                    drawPlayground(data.grid);
                    console.time('solving');
                    break;
                case 'done':
                    console.timeEnd('solving');
                    clearLastTriedCell();
                    source.close();
                    break;
                case '*':
                    starsPlaced++;
                    document.getElementById('starsPlaced').textContent = `stars placed: ${starsPlaced}`;
                    incrementTryCounter();
                    setCell(data.mode, data.row, data.col);
                    break;
                case 'try':
                    clearLastTriedCell();
                    lastTriedCell.row = data.row;
                    lastTriedCell.col = data.col;
                    setCell('?', data.row, data.col);
                    incrementTryCounter();
                    break;
                default:
                    incrementTryCounter();
                    setCell(data.mode, data.row, data.col);
            }
          
        };

        function clearLastTriedCell(){
            if(lastTriedCell.row === -1) return;
            setCell('', lastTriedCell.row, lastTriedCell.col);
        }

        // Handle any errors with the connection
        source.onerror = function(event) {
            console.error("SSE connection error", event);
            source.close();
        };

        function incrementTryCounter(){
            fieldsTested++;
            document.getElementById('fieldsTested').textContent = `fields tested: ${fieldsTested}`;
        }

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