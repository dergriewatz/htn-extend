<?php

if (!defined('IN_HTN')) {
    die('Hacking attempt');
}

$javascript = '';
$bodytag = '';

include_once('config.php');

function menu_entry($url, $text, $help = '', $em = '', $emclass = '', $selftags = false, $inatag = '')
{
    // refaktorisierte Funktion *muaahaahhaahaaa*
    echo "\n".'<li><a href="'.$url.'"'.$inatag.'><strong>'.$text.'</strong>';
    if ($em != '') {
        if (!$selftags) {
            echo "\n".'<br /><em class="'.$emclass.'">'.$em.'</em>';
        } else {
            echo $em;
        }
    }
    echo '</a>';
    if ($help != '') {
        echo "\n".'<div class="help">'.$help.'</div>'."\n";
    }
    echo '</li>'."\n";
}

function menu_entry_select($text, $help = '', $em = '', $emclass = '', $selftags = false, $inatag = '')
{
    // refaktorisierte Funktion *muaahaahhaahaaa*
    echo "\n".'<li><a name="pcs"><strong>'.$text.'</strong>';
    if ($em != '') {
        if (!$selftags) {
            echo "\n".'<br /><em class="'.$emclass.'">'.$em.'</em>';
        } else {
            echo $em;
        }
    }
    echo '</a>';
    if ($help != '') {
        echo "\n".'<div class="help">'.$help.'</div>'."\n";
    }
    echo '</li>'."\n";
}

function basicheader($title)
{
    global $usr, $javascript, $STYLESHEET, $bodytag, $stylesheets;
    global $STYLESHEET_BASEDIR, $standard_stylesheet;
    $stylesheet = $STYLESHEET;
    if ($stylesheets[$stylesheet]['id'] == '' || $stylesheet == '') {
        $stylesheet = $standard_stylesheet;
    }

    $ts = time() + 1;
    echo '<?xml version="1.0" encoding="ISO-8859-1"?>'."\n";
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>'.$title.'</title>
<link rel="stylesheet" type="text/css" href="'.$STYLESHEET_BASEDIR.$stylesheet.'/style.css" />
<script type="text/javascript">var stm=new Date(); stm.setTime('.$ts.'000); var sltm=new Date();</script>
<script type="text/javascript" src="global.js"></script>
<link rel="SHORTCUT ICON" href="favicon.ico" />
'.$javascript.'
</head>
';
}

function basicfooter()
{
    echo '</body>
</html>
';
}

function createlayout_top($title = 'HackTheNet', $nomenu = false)
{
    global $usr, $javascript, $STYLESHEET, $bodytag, $localhost, $FILE_REQUIRES_PC, $pc, $pcid;
    global $server, $transfer_ts, $t_limit_server;
    if ($usr['sid'] != '') {
        $sid = '&amp;sid='.$usr['sid'];
    }
    $stylesheet = $STYLESHEET;
    if (@is_dir('styles/'.$stylesheet) == false || $stylesheet == '') {
        $stylesheet = 'crystal';
    }

    basicheader($title);
    //$sql['system']=db_query('SELECT * FROM system');
    //$system=mysql_fetch_array($sql['system']);
    echo '<body'.$bodytag.'>
<div class="header">
<h1>HackTheNet (Version LAST MAN STANDING) Runde PHP 4.2</h1>
'.$ads;
    if ($nomenu == false) {
        echo '<ul class="navigation">
';

        if ($sid != '') {
            // INGAME ITEMS

            menu_entry(
                'game.php?m=start'.$sid,
                '&Uuml;bersicht',
                '&Uuml;bersicht &uuml;ber alles Wichtige auf einen Blick.'
            );

            if ($usr['newmail'] > 0) {
                $hw = ($usr['newmail'] == 1 ? '' : 's');
                $hw = 'Du hast '.$usr['newmail'].' neue Message'.$hw;
                if ($newsysmsgs > 0) {
                    $hw .= '';
                }
            }
            if ($newsysmsgs > 0) {
                $hw2 = ($newsysmsgs == 1 ? '' : 's');
                $hw .= 'Du hast '.$newsysmsgs.' neue System-Message'.$hw2;
            }
            menu_entry(
                'mail.php?m=start'.$sid,
                'Messages',
                'Hier kannst du Nachrichten verwalten und neue verfassen.',
                $hw,
                'new-messages'
            );

            $numberofpcs = count(explode(',', $usr['pcs']));
            $url = ($numberofpcs > 1 ? 'game.php?m=pcs'.$sid : 'game.php?a=selpc&amp;pcid='.$usr['pcs'].$sid);
            $help = ($numberofpcs > 1 ? 'Hier kommst du zu deinen PCs.' : 'Hier kommst du direkt zu deinem PC.');
            if ($usr['dropmenu'] == 1) {
                if ($FILE_REQUIRES_PC && $pc['id'] == $pcid) {
                    /* PC-Dropdownmenu - by Schaelle */
                    require_once("pc-dropmenu.php");
                } else {
                    menu_entry($url, 'Computer', $help);
                }
            } else {
                menu_entry('game.php?a=start'.$sid, 'Computer', $help);
            }
            $help = ((int)$usr['cluster'] > 0 ? 'Hier kannst du dich &uuml;ber den aktuellen Stand deines Clusters informieren' : 'Hier kannst du einen neuen Cluster gr&uuml;nden oder einem existierenden beitreten.');
            menu_entry('cluster.php?a=start'.$sid, 'Cluster', $help);

            menu_entry(
                'game.php?m=subnet'.$sid,
                'Subnet',
                'Hier kannst du die Computer in deinem oder einem anderen Subnet einsehen.'
            );
            menu_entry('game.php?m=notiz'.$sid, 'Notiz', 'Hier kannst du eine Notiz schreiben.');
            menu_entry('game.php?m=pc_suche'.$sid, 'PC Suche', 'Hier kannst du PCs nach bestimmten Kriterien suchen.');
            menu_entry('user.php?a=config'.$sid, 'Optionen');
            menu_entry('ranking.php?m=ranking'.$sid, 'Rangliste');
            menu_entry('game.php?m=kb'.$sid, 'Hilfe', 'Hier findest du die Hilfe zum Spiel.');
            if ($usr['stat'] >= 1000) {
                menu_entry('user.php?a=admincenter'.$sid, 'Adminbereich');
            };

        } else {
            // PUBLIC ITEMS
            menu_entry('pub.php', 'Startseite', 'HackTheNet-Startseite (Log In)');
            menu_entry('http://forum.hackthenet.org/', 'Forum', 'Das offizielle HTN-Forum');
            menu_entry('pub.php?d=impressum', 'Impressum');
            menu_entry('pub.php?d=credits', 'HTN-Team');
            menu_entry('pub.php?d=stats', 'Statistik', 'Statistische Daten &uuml;ber das Spiel');
            menu_entry('pub.php?d=rules', 'Spielregeln', 'Wer die nicht beachtet, fliegt!');
            menu_entry('pub.php?d=faq', 'FAQ', 'H&auml;ufig gestellte Fragen zu HTN');
            menu_entry('pub.php?d=newpwd', 'Neues Passwort', 'Ein neues Passwort f&uuml;r deinen Account anfordern');
            menu_entry('pub.php?a=register', 'Registrieren', 'Einen HackTheNet-Account anlegen');
        }
        // COMMON ITEMS
        menu_entry('http://web43.mwi5.de/Beta-Tests/', 'Forum');

        if ($sid != '') {
            // INGAME ITEMS 2
            menu_entry(
                'login.php?a=logout'.$sid,
                'Log Out',
                'Hier kannst du dich abmelden.',
                '<br />Account: <em>'.$usr['name'].'</em>',
                '',
                true
            );
        } else {
            // PUBLIC ITEMS 2

        }
    }

    echo '</ul>
</div>

<!-- NAVI ENDE -->
';
}

function createlayout_bottom()
{
    global $starttime, $_query_cnt;
    if ($starttime > 0) {
        $time = (float)calc_time($starttime, 0, 10);
        if ($time < 1) {
            $time = number_format($time * 1000, 1, '.', ',').' ms';
        } else {
            $time = number_format($time, 2, ',', '.').' s';
        }
    }
    echo '
  <div id="generation-time">'.$time.' / '.(int)$_query_cnt.'</div>
  <div id="server-time">'.nicetime3(0, '').'</div>';
    if ($usr['bigacc'] != 'yes') {
        echo '
  <br><br>
  <div align="center"><p>Mirror by <a href="http://www.fun-synchro.de">www.fun-synchro.de</a> and <a href="http://www.beta-tests.net">www.beta-tests.net</a></p></div>
  <br>
  <div align="center">
  <!-- Begin Nedstat Basic code -->
  <!-- Title: HackTheNet - Game -->
  <!-- URL: http://www.htn-game.tk/ -->
  <script language="JavaScript" type="text/javascript" src="http://m1.nedstatbasic.net/basic.js">
  </script>
  <script language="JavaScript" type="text/javascript" >
  <!--
    nedstatbasic("ADX+vwO8AuAbdJlnUVP4nYSvj3uA", 0);
  // -->
  </script>
  <noscript>
  <a target="_blank" href="http://www.nedstatbasic.net/stats?ADX+vwO8AuAbdJlnUVP4nYSvj3uA"><img
  src="http://m1.nedstatbasic.net/n?id=ADX+vwO8AuAbdJlnUVP4nYSvj3uA"
  border="0" width="18" height="18"
  alt="Nedstat Basic - Kostenlose web site statistiken
  Pers�nliche Homepage webseite Z�hler"></a><br>
  <a target="_blank" href="http://www.nedstatbasic.net/">Kostenlose Z�hler</a>
  </noscript>
  <!-- End Nedstat Basic code -->
  </div>
  ';
    }
    basicfooter();
}

?>