<h3>Общий рейтинг работодателя</h3>
<?php
echo '<table>';
    foreach ($data as $r) {
    echo '<tr>';
        echo '<td>' . $r['rate'] . '</td>';
        echo '<td>' . $r['rate_neg'] . '</td>';
        echo '<td>' . $r['descr'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
if (!$is_save) {
    echo '<h3>Вы уже сделали оценку !</h3>';
}
?>