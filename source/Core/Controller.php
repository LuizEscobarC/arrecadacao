<?php

namespace Source\Core;

use avadim\FastExcelReader\Excel;
use Shuchkin\SimpleXLS;
use Source\Models\Lists;
use Source\Support\Message;
use Source\Support\SeoBuilder;

/**
 * FSPHP | Class Controller
 *
 * @author Robson V. Leite <cursos@upinside.com.br>
 * @package Source\Core
 */
class Controller
{
    /** @var View */
    protected $view;

    /** @var SeoBuilder */
    protected $seo;

    /** @var Message */
    protected $message;

    protected $response;

    /**
     * Controller constructor.
     * @param string|null $pathToViews
     */
    public function __construct(string $pathToViews = null)
    {
        $this->view = new View($pathToViews);
        $this->seo = new SeoBuilder();
        $this->message = new Message();
    }

    /**
     * @param int $code
     * @param string|null $type
     * @param string|null $message
     * @param string $rule
     * @return Controller
     */
    protected function call(int $code, string $type = null, string $message = null, string $rule = "error"): Controller
    {
        http_response_code($code);

        if (!empty($type)) {
            $this->response = [
                $rule => [
                    "type" => $type,
                    "message" => (!empty($message) ? $message : null)
                ]
            ];
        }
        return $this;
    }

    /**
     * @param array|null $response
     * @return Controller
     */
    protected function back(array $response = null): Controller
    {
        if (!empty($response)) {
            $this->response = (!empty($this->response) ? array_merge($this->response, $response) : $response);
        }

        echo json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return $this;
    }
}

?>
