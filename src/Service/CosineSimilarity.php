<?php

namespace App\Service;

class CosineSimilarity
{

public function cosine_similarity($vector1, $vector2) {
    $dotProduct = array_sum(array_map(function($x, $y) {
        return $x * $y;
    }, $vector1, $vector2));

    $magnitude1 = sqrt(array_sum(array_map(function($x) {
        return $x * $x;
    }, $vector1)));

    $magnitude2 = sqrt(array_sum(array_map(function($x) {
        return $x * $x;
    }, $vector2)));

    if ($magnitude1 == 0 || $magnitude2 == 0) {
        return 0;
    }

    return round(($dotProduct / ($magnitude1 * $magnitude2))*100, 2);
}

}