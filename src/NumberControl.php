<?php

/**
 * @package   yii2-number
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2018
 * @version   1.0.3
 */

namespace kartik\number;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\base\InputWidget;

/**
 * Number control widget
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since  1.0
 */
class NumberControl extends InputWidget
{
    /**
     * @var array masked input plugin options
     */
    public $maskedInputOptions = [];

    /**
     * @inheritdoc
     */
    public $pluginName = 'numberControl';

    /**
     * @var array the HTML attributes for the base model input that will be saved typically to database. The following
     * special options are recognized:
     * - `type`: _string_, whether to generate a 'hidden' or 'text' input. Defaults to 'hidden'.
     * - `label`: _string_, any label to be placed before the input. Will be only displayed if 'type' is 'text'.
     */
    public $options = [];

    /**
     * @var array the HTML attributes for the displayed masked input
     */
    public $displayOptions = [];

    /**
     * @var array the HTML attributes for the container in which the hidden save input will be rendered
     */
    public $saveInputContainer = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->initWidget();
        $this->registerAssets();
        echo $this->getDisplayInput() . "\n" . $this->getSaveInput();
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        NumberControlAsset::register($view);
        $id = $this->options['id'];
        $this->pluginOptions = [
            'displayId' => $this->displayOptions['id'],
            'maskedInputOptions' => $this->maskedInputOptions,
        ];
        $this->registerPlugin($this->pluginName, "jQuery('#{$id}')");
    }

    /**
     * Initializes the widget
     */
    protected function initWidget()
    {
        if (!isset($this->displayOptions['id'])) {
            $this->displayOptions['id'] = $this->options['id'] . '-disp';
        }
        if (!isset($this->displayOptions['class'])) {
            $this->displayOptions['class'] = 'form-control';
        }
        if (isset($this->disabled) && $this->disabled) {
            $this->displayOptions['disabled'] = true;
        }
        if (isset($this->readonly) && $this->readonly) {
            $this->displayOptions['readonly'] = true;
        }
        $formatter = Yii::$app->formatter;
        $defaultOptions = [
            'alias' => 'numeric',
            'digits' => 2,
            'groupSeparator' => isset($formatter->thousandSeparator) ? $formatter->thousandSeparator : ',',
            'radixPoint' => isset($formatter->decimalSeparator) ? $formatter->decimalSeparator : '.',
            'autoGroup' => true,
            'autoUnmask' => false,
        ];
        $this->maskedInputOptions = array_replace_recursive($defaultOptions, $this->maskedInputOptions);
    }

    /**
     * Generates the display input.
     *
     * @return string
     */
    protected function getDisplayInput()
    {
        $name = ArrayHelper::remove($this->displayOptions, 'name', $this->displayOptions['id']);
        return Html::textInput($name, $this->value, $this->displayOptions);
    }

    /**
     * Generates the save input.
     *
     * @return string
     */
    protected function getSaveInput()
    {
        $type = ArrayHelper::remove($this->options, 'type', 'hidden');
        $out = $this->getInput($type . 'Input');
        if ($type === 'text') {
            if (!isset($this->options['tabindex'])) {
                $this->options['tabindex'] = 10000;
            }
            $out = ArrayHelper::remove($this->options, 'label', '') . $out;
        } else {
            Html::addCssStyle($this->saveInputContainer, 'display:none');
        }
        return Html::tag('div', $out, $this->saveInputContainer);
    }
}
