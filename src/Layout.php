<?php namespace Hpolthof\LaravelAPI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class Layout
{
    protected $httpStatus = 200;
    protected $data = [];
    protected $errorMessage = null;

    public function __construct($data = [], $httpStatus = 200, $errorMessage = null)
    {
        $this->data = $data;
        $this->httpStatus = $httpStatus;
        $this->errorMessage = $errorMessage;
    }

    /**
     * @param $message
     * @param int $httpStatus
     * @return Response
     */
    public static function responseMessage($message, $httpStatus = 200)
    {
        $layout = new static([], $httpStatus, $message);
        return $layout->getResponse();
    }

    /**
     * @return array
     */
    public function render()
    {
        $header = [
            'request' => [
                'location' => request()->url(),
                'method' => request()->method(),
                'parameters' => request()->all(),
            ],
            'response' => [
                'status' => $this->httpStatus,
                'error' => $this->errorMessage,
                'timestamp' => date('Y-m-d H:i:s'),
            ],
        ];

        $content = $this->renderData($this->data);
        return json_encode(compact('header', 'content'), JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return new Response($this->render(), $this->httpStatus, [
            'Content-type' => 'application/javascript',
        ]);
    }

    /**
     * @param mixed $source
     * @return mixed
     */
    protected static function renderData($source)
    {
        if (is_array($source) || $source instanceof Collection) {
            $data = [];
            foreach ($source as $item) {
                $data[] = static::renderData($item);
            }
            return $data;
        } elseif ($source instanceof Model) {
            $data = Binding::render($source);
            return $data;
        } else {
            $data = $source;
            return $data;
        }
    }
}