<?php

class Controller
{

    private $data;

    private function render(): void
    {
        extract($this->data);
        ob_start();
        include ROOT . '/view/page.php';
        echo ob_get_clean();
        die();
    }

    public function run()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            $post = json_decode(file_get_contents('php://input'), true);

            $request = new Request($post);
            $request->curlRequest();
            echo json_encode($request->getData(), JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
            die();
        }

        $this->data = [
            'year' => date('Y'),
            'uname' => php_uname(),
            'version' => phpversion()
        ];
        return $this->render();
    }
}
