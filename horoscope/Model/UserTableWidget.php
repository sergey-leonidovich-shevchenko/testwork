<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 2018-12-09
 * Time: 07:31
 */

class UserTableWidget
{
    /**
     * @param array $userList
     * @param int $countPage
     * @param int $currentPage
     * @param int $countRecordInPage
     * @return string HTML DOM
     */
    public static function widget(array $userList, int $countPage, int $currentPage, int $countRecordInPage): string
    {
        $html = '<table class="table-bordered w-100 mb-3">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Birthday</th>
                </tr>
            </thead>
            <tbody class="table-striped">';

        if (!count($userList)) {
             $html .= '
                <tr>
                    <h1>No data...</h1>
                </tr>';
        } else {
            foreach ($userList as $user) {
                $html .= '
                    <tr>
                        <td>' . $user->id . '</td>
                        <td>' . $user->name . '</td>
                        <td>' . $user->birthday . '</td>
                    </tr>';
            }
        }
        $html .= '
                </tbody>
            </table>';

        $html .= Pagination::widget($countPage, $currentPage, $countRecordInPage);

        return $html;
    }
}