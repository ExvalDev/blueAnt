<?php

enum Priorities: int
{
    case Priority1 = 4;
    case Priority2 = 5;
    case Priority3 = 6;


    // Method to get the color for a phase
    public function getColor(): string
    {
        return match ($this) {
            self::Priority1 => '#66FF66', // Green
            self::Priority2 => '#FFCC88', // Orange
            self::Priority3 => '#FF8888', // Red
        };
    }
}