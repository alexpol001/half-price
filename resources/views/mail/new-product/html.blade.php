<?php

/* @var $this yii\web\View */
/* @var $model array */

?>
<div class="send">
    <table style="width: 100%">
        <tr>
            <td style='padding: 10px; border: #e9e9e9 1px solid;'><b>Тема</b></td>
            <td style='padding: 10px; border: #e9e9e9 1px solid;'>{{$model->getTheme()->title}}</td>
        </tr>
        <tr>
            <td style='padding: 10px; border: #e9e9e9 1px solid;'><b>Имя</b></td>
            <td style='padding: 10px; border: #e9e9e9 1px solid;'>{{$model->name}}</td>
        </tr>
        <tr>
            <td style='padding: 10px; border: #e9e9e9 1px solid;'><b>Email</b></td>
            <td style='padding: 10px; border: #e9e9e9 1px solid;'>{{$model->email}}</td>
        </tr>
        <tr>
            <td style='padding: 10px; border: #e9e9e9 1px solid;'><b>Сообщение</b></td>
            <td style='padding: 10px; border: #e9e9e9 1px solid;'>{{$model->message}}</td>
        </tr>
    </table>
</div>

