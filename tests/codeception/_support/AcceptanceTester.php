<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/

class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

   /**
    * Define custom actions here
    */

    /*
    * Lukte niet om de orginele functie te gebruiken, daarom deze fix:
    * http://stackoverflow.com/questions/33868818/yii2-codeception-invalid-routing
    */
    public function amOnPageCustom($url)
    {
       $page = \Yii::$app->getUrlManager()->createUrl($url);
       return $this->amOnPage($page);
    }
}
