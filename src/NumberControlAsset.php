<?php

/**
 * @package   yii2-number
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2018
 * @version   1.0.3
 */

namespace kartik\number;

use kartik\base\AssetBundle;

/**
 * Asset bundle for the [[NumberControl]] widget.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class NumberControlAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->depends = array_merge($this->depends, ['yii\widgets\MaskedInputAsset']);
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/number']);
        parent::init();
    }
}