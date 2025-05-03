<?php


namespace App\Services;


class PaginationPages
{
    public static function getPages($current, $total, $maxVisible = 9):array
    {
        $pages = [];

        if ($total <= $maxVisible) {
            for ($i = 1; $i <= $total; $i++) {
                $pages[] = $i;
            }
        } else {
            $half = floor($maxVisible / 2);
            $start = $current - $half;
            $end = $current + $half;

            if ($start < 1) {
                $start = 1;
                $end = $maxVisible;
            }

            if ($end > $total) {
                $end = $total;
                $start = $total - $maxVisible + 1;
            }

            for ($i = $start; $i <= $end; $i++) {
                $pages[] = $i;
            }
        }

        return $pages;
    }
}
