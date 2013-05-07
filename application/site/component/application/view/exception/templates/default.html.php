<?
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<!DOCTYPE HTML>
<html lang="<?= $language; ?>" dir="<?= $direction; ?>">
<head>
    <link rel="stylesheet" href="media://application/stylesheets/error.css" type="text/css" />
    <title><?= @text('Error').': '.$code; ?></title>
</head>
<body>

<div id="container">
    <div id="errorboxheader">
        <?= $message ?>
    </div>
    <div id="errorboxbody">
        <p><strong><?= @text('You may not be able to visit this page because of:'); ?></strong></p>
        <ol>
            <li><?= @text('An out-of-date bookmark/favourite'); ?></li>
            <li><?= @text('A search engine that has an out-of-date listing for this site'); ?></li>
            <li><?= @text('A mis-typed address'); ?></li>
            <li><?= @text('You have no access to this page'); ?></li>
            <li><?= @text('The requested resource was not found'); ?></li>
            <li><?= @text('An error has occurred while processing your request.'); ?></li>
        </ol>
        <p><strong><?= @text('Please try one of the following pages:'); ?></strong></p>
        <p>
        <ul>
            <li><a href="/" title="<?= @text('Go to the home page'); ?>"><?= @text('Home Page'); ?></a></li>
        </ul>
        </p>
        <p><?= @text('If difficulties persist, please contact the system administrator of this site.'); ?></p>
        <div id="techinfo">
            <p><?= $message ?></p>
            <p>
                <? if(count($trace)) : ?>
                <?= @template('default_backtrace.html'); ?>
                <? endif; ?>
            </p>
        </div>
    </div>
</div>