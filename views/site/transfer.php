<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Внутренний перевод';
?>
<div class="site-index">

    <div class="body-content">
        <div class="row">
            <div class="col-lg-12">
                <h2>Внутренний перевод</h2>
                </br>
                <?php $form = ActiveForm::begin() ?>
                    <?= $form->field($operationHistory, 'sum')->textInput(array('placeholder' => 'Сумма')); ?>

                    <?= $form->field($operationHistory, 'toUserId')->textInput(array('placeholder' => 'ID Пользователя')); ?>

                    <div class="form-group">
                        <?= Html::submitButton('Ок', ['class' => 'btn btn-primary']) ?>
                    </div>
                <?php ActiveForm::end() ?>

            </div>

        </div>

    </div>
</div>
