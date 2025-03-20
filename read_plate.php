<?php
require_once 'vendor/autoload.php';

use Google\Cloud\Vision\VisionClient;

//Api Key google
$vision = new Visionclient(['keyFile' => json_decode(file_get_contents("key/key.json"), true)]);

$image_path = $_FILES['file']['tmp_name'];


$image = $vision->image(file_get_contents($image_path), ['TEXT_DETECTION']);
$result = $vision->annotate($image);

$resultArray = $result->info();
$resultJson = json_encode($resultArray, JSON_PRETTY_PRINT);
$data = json_decode($resultJson, true);

$response = array();

if (isset($data)) {
    if (isset($data['textAnnotations'])) {
        $textAnnotations = $data['textAnnotations'];

        $matricula_pattern = '/[0-9]{4}\s[A-Za-z]{3}|[0-9]{4}[A-Za-z]{3}/';

        foreach ($textAnnotations as $annotation) {
            $description = $annotation['description'];
            if (preg_match($matricula_pattern, $description, $matches)) {
                $response['status'] = 1;
                $response['msg'] = $matches[0];
                // $response['msg'] = str_replace(' ', '', $matches[0]);
            }
        }
    } else {
        $response['status'] = 0;
        $response['msg'] = "No se detectaron matrículas en la imagen.";
    }
} else {
    $response['status'] = 0;
    $response['msg'] = "No se encontraron resultados en el JSON.";
}

echo json_encode($response);
