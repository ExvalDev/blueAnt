<?php

enum Phase: int
{
    case Phase1 = 1;
    case Phase2 = 2;
    case Phase3 = 3;


    case Phase5 = 5;

    case Phase6 = 6;


    // Method to get the color for a phase
    public function getColor(): string
    {
        return match ($this) {
            self::Phase1 => '#66FF66', // Green
            self::Phase2 => '#FFCC88', // Orange
            self::Phase3 => '#FF8888', // Red
            self::Phase5 => '#FF8888', // Red
            self::Phase6 => '#FF8888', // Red

        };
    }
}