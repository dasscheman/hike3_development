<?php

namespace app\components;

use kartik\widgets\AlertBlock;
use kartik\alert\Alert;

/**
 * CustomPagination is used to force a maximum number of pages.
 * In the Pagination class the pageCount is read-only, in this class it is made
 * read and write property.
 * @property int $pageCount Number of pages. This property is read and write.
 */
class CustomAlertBlock extends AlertBlock
{
    const TYPE_ROUTE = 'alert-route';
    const TYPE_QUESTION = 'alert-question';
    const TYPE_HINT = 'alert-qr';
    const TYPE_QR = 'alert-hint';
    const TYPE_POST = 'alert-post';

    public $alertSettings =
        [
            'route' => ['type' => SELF::TYPE_ROUTE],
            'question' => ['type' => SELF::TYPE_QUESTION],
            'hint' => ['type' => SELF::TYPE_HINT],
            'qr' => ['type' => SELF::TYPE_QR],
            'post' => ['type' => SELF::TYPE_POST],
            'error' => ['type' => Alert::TYPE_DANGER],
            'success' => ['type' => Alert::TYPE_SUCCESS],
            'info' => ['type' => Alert::TYPE_INFO],
            'warning' => ['type' => Alert::TYPE_WARNING],
            'primary' => ['type' => Alert::TYPE_PRIMARY],
            'default' => ['type' => Alert::TYPE_DEFAULT]
        ];
}
