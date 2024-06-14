<?php
declare(strict_types=1);

use App\Parser;
use GuzzleHttp\Client;

require_once dirname(__DIR__).'/vendor/autoload.php';
require_once dirname(__DIR__).'/src/Parser.php';
?>

<html>
<form method="post" action="index.php"> <input type="text" name="url" placeholder="URL" /> <input type="submit" value="Го"/></form>

<?php
if ($_POST) {
    $client = new Client();
    $parser = new Parser(new Client(), $_POST['url']);
    try {
        $images = $parser->parse();
        $col = 0;
        $total = 0;
        echo "<div style='flex-direction: column'>";
        foreach ($images as $path => $size) {
            if ($col++ == 0) {
                echo "<div style='flex-direction: row'>";
            }
            echo sprintf("<img src=\"%s%s\" style=\"width:100px;height:100px;\"/>", $_POST['url'], $path);
            $total += $size;
            if ($col % 4 == 0) {
                echo "</div>";
                $col = 0;
            }
        }
        if (count($images) % 4 != 0) {
            echo "</div>";
        }
        echo sprintf("<span>На странце обнаружено %d изображений, на %01.2f Мб</span>", count($images), round($total / 1048576, 2));
        echo "</div>";
    } catch (Exception $exception) {
        echo "<div>Something went wrong while parsing the images.</div>";
    }
}
?>
</html>