<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 2018-12-09
 * Time: 07:43
 */

class Pagination
{
    public static function widget(int $countRecord, int $currentPage, int $countRecordInPage): string
    {
        $html = '<nav>
            <ul class="pagination">';

        for ($i = 1, $iMax = ceil($countRecord / $countRecordInPage); $i <= $iMax; $i++) {
            $html .= '<li class="page-item' . ($currentPage === $i ? ' active' : ' ') . '">
                    <a class="page-link" href="/?page=' .  $i . '">' . $i . '</a>
                </li>';
        }

        $html .= '</ul>
        </nav>';

        return $html;
    }
}