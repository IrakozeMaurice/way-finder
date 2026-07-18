<?php

namespace App\Services;

class DijkstraService
{
    public function shortestPath($graph, $start, $end)
    {
        $dist = [];

        $previous = [];

        $queue = [];


        foreach ($graph as $node => $edges) {

            $dist[$node] = INF;

            $previous[$node] = null;

            $queue[$node] = true;

        }


        if (!isset($dist[$start])) {
            return [];
        }

        $dist[$start] = 0;


        while (!empty($queue)) {

            $current = null;

            $smallest = INF;

            foreach ($queue as $node => $value) {

                if ($dist[$node] < $smallest) {

                    $smallest = $dist[$node];

                    $current = $node;

                }

            }


            if ($current === null) {
                break;
            }


            if ($current == $end) {
                break;
            }


            unset($queue[$current]);


            if (!isset($graph[$current])) {
                continue;
            }


            foreach ($graph[$current] as $edge) {

                $neighbor = $edge['node'];

                $newDistance =

                    $dist[$current] +

                    $edge['distance'];


                if ($newDistance < $dist[$neighbor]) {

                    $dist[$neighbor] = $newDistance;

                    $previous[$neighbor] = $current;

                }

            }

        }


        $path = [];

        $node = $end;


        while ($node !== null) {

            array_unshift($path, $node);

            $node = $previous[$node];

        }


        if (count($path) == 1 && $path[0] != $start) {

            return [];

        }


        return [

            'path' => $path,

            'distance' => $dist[$end]

        ];

    }
}