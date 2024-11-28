<?php

enum Phase: int
{
    case Phase1 = 1;
    case Phase2 = 2;
    case Phase3 = 3;

    // Method to get the color for a phase
    public function getColor(): string
    {
        return match ($this) {
            self::Phase1 => '#66FF66', // Green
            self::Phase2 => '#FFCC88', // Orange
            self::Phase3 => '#FF8888', // Red
        };
    }
}