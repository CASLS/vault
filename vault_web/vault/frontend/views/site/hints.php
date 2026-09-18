<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Hints';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<p>
    This page demonstrates the hints pattern used by VAuLT experiences: a title for a puzzle,
    followed by one or more progressively-revealing hints, and a final "Need the answer?"
    reveal. Replace the example section below with your own experience's content, or copy it
    to add more.
</p>

<h3><u>Example Puzzle</u></h3>
<table class="hints_table table table-striped" style="margin-bottom:60px">
    <tr>
        <td>
            <h4>Need a hint?</h4>

            <button onclick="$('#hint_1a').slideToggle();" type="button" class="hints_buttons btn btn-info">SHOW <span class="glyphicon glyphicon-chevron-down"></span></button>

            <div id="hint_1a" style="display: none;">
                <hr>
                <p>
                    This is where your first hint goes.
                </p>
            </div>
        </td>
    </tr>

    <tr>
        <td>
            <h4>Need another hint?</h4>

            <button onclick="$('#hint_1b').slideToggle();" type="button" class="hints_buttons btn btn-info">SHOW <span class="glyphicon glyphicon-chevron-down"></span></button>

            <div id="hint_1b" style="display: none;">
                <hr>
                <p>
                    This is where a second, more direct hint goes.
                </p>
            </div>
        </td>
    </tr>

    <tr>
        <td>
            <h4>Need the answer?</h4>

            <button onclick="$('#hint_1c').slideToggle();" type="button" class="hints_buttons btn btn-info">SHOW <span class="glyphicon glyphicon-chevron-down"></span></button>

            <div id="hint_1c" style="display: none;">
                <hr>
                <p>
                    This is where the answer goes.
                </p>
            </div>
        </td>
    </tr>
</table>
