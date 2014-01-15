<?
/**
 * Class Widget_Legend
 */
class Widget_Legend
{

	public static  function getEditLegend($text, $name){
        return "<div class='legend'><div style='float: left;'>" . $text . ": <span>" . $name . "</span></div><div class='mini_tool'><img src='/design/img/back.png' height='22' onclick='history.back()' title='Назад'><a id='save' href='#'><img src='/design/img/save.png' height='22' title='Сохранить'></a></div></div>";
    }


}