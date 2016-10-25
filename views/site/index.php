<?php

$this->title = 'Список пользователей';

echo yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            'balance',

            [
                'header' => 'Действие',
                'attribute' => 'id',
                'format' => 'html',
                'value' => function($model) {
                    return '<a href = "' . \yii\helpers\Url::toRoute(['transfer', 'id' => $model->id]) . '">внутренний перевод</a>';
                }
            ],
        ],
    ]);
?>
