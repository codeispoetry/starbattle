<?php

namespace Starbattle;

class Starbattle
{
    /*
        The grid comes from outside the class. This array holds the areas of the playground.
        Within the class, it is a two-dimensional array with rows and columns, holding the
        indexnumber of an area.
    */
    private $grid;

    /*
        This array holds the positions of the stars. Is has the same dimensions as grid, of course
    */
    private $stars;

    /*
        Initializes the stars-array
    */
    public function __construct($gridString)
    {
        $this->grid = $this->convertGrid($gridString);
        $this->stars = $this->grid;
        // Initialize the stars grid with empty cells
        foreach ($this->stars as $row => $cells) {
            foreach ($cells as $col => $cell) {
                $this->stars[$row][$col] = '';
            }
        }

        // Draw the grid.
        echo "data: " . json_encode(['mode' => 'grid', 'grid' => $this->grid ]) . "\n\n";
        flush();
    }

    /*
        Convert the string, that represents the grid into a
        two-dimensional array for internal use
        @return array The grid
    */
    private function convertGrid($gridString)
    {
        $grid = [];
        $rows = explode(',', $gridString);
        foreach ($rows as $row => $columns) {
            $grid[$row] = str_split($columns);
        }

        return $grid;
    }

    /*
        Recurive function.
        Loops over every column in every row.
        Tries to set a star and detects, if all star could be set.
        @param $row The row, over which columns is being looped.
    */
    public function solve($row = 0)
    {
        // The base case: All stars set in last row
        if ($row === count($this->grid)) {
            if ($this->countStars() === count($this->grid)) {
                // Solution found
                echo "data: " . json_encode(['mode' => 'done']) . "\n\n";
            }
            return;
        }

        for ($c = 0; $c < count($this->grid[0]); $c++) {
            if ($this->setStar($row, $c)) {
                $this->solve($row + 1);
                // If I come back here, no solution was found. So remove the star.
                $this->removeStar($row, $c);
            }
        }
    }

    /*
        @return The number of stars, that are set - right or wrong
    */
    private function countStars()
    {
        $count = 0;
        foreach ($this->stars as $row) {
            foreach ($row as $cell) {
                if ($cell === '*') {
                    $count++;
                }
            }
        }
        return $count;
    }

    /*
        Removes a star from the grid.
        @param $row The coordinates in row and colum
        @param $c The column.
    */
    private function removeStar($row, $col)
    {
        $this->stars[$row][$col] = '';
        $this->send('', $row, $col);
    }

    /*
        Sets a star to the grid, if it is allowed only.
        @param $row The coordinates in row and colum
        @param $c The column.
        @return bool False, if no star could be set, true otherwise.
    */
    private function setStar($row, $col)
    {
        if (! $this->isAllowed($row, $col)) {
            $this->send('try', $row, $col);
            return false;
        }
        $this->stars[$row][$col] = '*';
        $this->send('*', $row, $col);
        return true;
    }

    /*
        Checks, if a star is allowed to be set at a square.
        There must be no other star in that row, columns, area or as direct neighbour.
        @return bool
    */
    private function isAllowed($row, $col)
    {
        // is a star in the same row?
        for ($i = 0; $i < count($this->grid[$row]); $i++) {
            if ($this->stars[$row][$i] === '*') {
                return false;
            }
        }

        // is a star in the same column?
        for ($i = 0; $i < count($this->grid); $i++) {
            if ($this->stars[$i][$col] === '*') {
                return false;
            }
        }

        // is a star in the same color?
        $color = $this->grid[$row][$col];
        foreach ($this->grid as $r => $cells) {
            foreach ($cells as $c => $cell) {
                if ($this->stars[$r][$c] === '*' && $this->grid[$r][$c] === $color) {
                    return false;
                }
            }
        }

        // is a star a neighbor?
        $neighbors = [
            [$row - 1, $col - 1],
            [$row - 1, $col],
            [$row - 1, $col + 1],
            [$row, $col - 1],
            [$row, $col + 1],
            [$row + 1, $col - 1],
            [$row + 1, $col],
            [$row + 1, $col + 1]
        ];
        foreach ($neighbors as $neighbor) {
            $r = $neighbor[0];
            $c = $neighbor[1];
            if (
                $r >= 0 &&
                $r < count($this->grid) &&
                $c >= 0 &&
                $c < count($this->grid[$r]) &&
                $this->stars[$r][$c] === '*'
            ) {
                return false;
            }
        }

        return true;
    }

    private function send($mode, $row, $col)
    {
        $data = [
            'mode' => $mode,
            'row' => $row,
            'col' => $col
        ];

        echo "data: " . json_encode($data) . "\n\n";
        flush();
        usleep(DELAY_IN_SECONDS * 1000000);
    }
}
