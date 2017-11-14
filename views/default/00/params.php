<?php

use common\models\Params;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Html;

echo Html::beginForm('', '', ['class' => 'form-horizontal']);

$rows = Params::find()->all();

foreach ($rows as $row) {
    echo Form::widget([
        'formName' => 'kvform',
        'columns' => 1,
        'attributeDefaults' => [
            'type' => Form::INPUT_TEXT,
            'labelOptions' => ['class' => 'col-md-2'],
            'inputContainer' => ['class' => 'col-md-6'],
            'container' => ['class' => 'form-group'],
        ],
        'attributes' => [// 2 column layout
            $row->p_name => ['label' => $row->p_label, 'value' => $row->p_value, 'type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '']],
        ]
    ]);
}
?>   
<div class="" style="text-align: right;">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
</div>

<?php
echo Html::endForm();
