<?php
$lines = file('https://www.foxtrot.com.ua/ru/shop/kharkov/noutbuki.html');
$linesArr = [];
$count = 0;
foreach ($lines as $line_num => $line) {
    // echo "Строка {$line_num}: " . htmlspecialchars($line) . "\n<br/>";
    if (str_contains($line, 'card__title')) {
        $model = explode('="', $line);
        $model = explode('">', $model[3]);
        $linesArr[$count]["model"] = $model[0];
    }
    if (str_contains($line, 'card-price')) {
        $price = explode('">', $line);
        $price = explode('₴', $price[1]);
        if (str_contains($lines[$line_num + 1], '₴')) {
            $price = explode(' ₴', $lines[$line_num + 1]);
            $price[0] = str_replace(" ", "", $price[0]);
            $linesArr[$count]["price"] = trim($price[0]);
        } else {
            $price[0] = str_replace(" ", "", $price[0]);
            $linesArr[$count]["price"] = trim($price[0]);
        }
        $count++;
    }
}
function cmp_price($a, $b)
{
    return ($a["price"] <=> $b["price"]);
}
uasort($linesArr, "cmp_price");

$fp = fopen('file.csv', 'w');

foreach ($linesArr as $fields) {
    fputcsv($fp, $fields);
}
fclose($fp);
