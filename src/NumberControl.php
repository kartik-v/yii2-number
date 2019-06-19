<?php

/**
 * @package   yii2-number
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2018 - 2019
 * @version   1.0.6
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
     * - `type`: _string_, HTML input type. Defaults to 'text'. Note that the `saveInputContainer` settings will control
     *    the display of the saved input.
     * - `label`: _string_, any label to be placed before the input.
     */
    public $options = [];

    /**
     * @var string the name of the model attribute to read/write the masked number format data and is applicable only
     * when using with a model. If not set a normal native HTML text control will be generated as the display input.
     */
    public $displayAttribute;

    /**
     * @var array the HTML attributes for the displayed masked input
     */
    public $displayOptions = [];

    /**
     * @var array the HTML attributes for the container in which the saved input will be rendered. This container
     * is set to hidden display by default via the `style` configuration.
     */
    public $saveInputContainer = ['style' => 'display:none'];

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
        if (!empty($this->displayAttribute) && $this->hasModel()) {
            return Html::activeTextInput($this->model, $this->displayAttribute, $this->displayOptions);
        }
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
        $type = ArrayHelper::remove($this->options, 'type', 'text');
        if (!isset($this->options['tabindex'])) {
            $this->options['tabindex'] = 10000;
        }
        $label = ArrayHelper::remove($this->options, 'label', '') ;
        if ($this->hasModel()) {
            $out = Html::activeInput($type, $this->model, $this->attribute, $this->options);
        } else {
            $out = Html::input($type, $this->name, $this->value, $this->options);
        }
        $out = $label . $out;
        return Html::tag('div', $out, $this->saveInputContainer);
    }
}
