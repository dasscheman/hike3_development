<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use app\components\CustomAlertBlock;

/**
 * @var dektrium\user\Module $module
 */
?>


<div class="row">
    <div class="col-xs-12">
        <?php
        echo CustomAlertBlock::widget([
            'type' => CustomAlertBlock::TYPE_ALERT,
            'useSessionFlash' => true,
            'delay' => false,
        ]); ?>
    </div>
</div>
