<?php
/**
 * Additional contents plugin.
 *
 * It allows to add add manage addition contents on pages.
 *
 * @author Prakai Nadee <prakai@rmuti.acth> pre-fork: version 1.1.0
 * @forked by Robert Isoski @robiso
 * @version 3.0.0
 */

global $Wcms;

if (defined('VERSION')) {
	$Wcms->addListener('js', 'loadAdditionContentsJS');
	$Wcms->addListener('css', 'loadAdditionContentsCSS');
	$Wcms->addListener('page', 'loadAdditionContentsEditableV2');
}


function loadAdditionContentsJS($args) {
    global $Wcms;
    if ($Wcms->loggedIn) {
        $script = <<<EOT
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha384-vk5WoKIaW/vJyUAd9n/wmopsmNhiy+L2Z+SBxGYnUkunIxVxAv/UtMOhba/xskxh" crossorigin="anonymous"></script>
        <script src="{$Wcms->url('plugins/additional-contents/js/script.js')}" type="text/javascript"></script>
EOT;
        $args[0].=$script;
    }
    return $args;
}

function loadAdditionContentsCSS($args) {
    global $Wcms;
    if ($Wcms->loggedIn) {
        $script = <<<EOT
        <link rel="stylesheet" href="{$Wcms->url('plugins/additional-contents/css/style.css')}" type="text/css" media="screen" charset="utf-8">
EOT;
        $args[0].=$script;
    }
    return $args;
}

function loadAdditionContentsEditableV2($contents) {
    global $Wcms;

    if ($contents[1]!='content') {
        return $contents;
    }
    $content = $contents[0];
    $target = 'pages';
    $page = $Wcms->currentPage;

    if ($Wcms->loggedIn) {
        $requestToken = $_POST['token'] ?? $_GET['token'] ?? null;
        if (isset($_POST['delac']) && $Wcms->hashVerify($requestToken)) {
            $key = $_POST['delac'];
            if (getContentV2($key)!==false) {
            	$tempArray = explode('content_', $key);
                $k = (int)end($tempArray);
                $Wcms->deletePageKey($Wcms->currentPageTree, 'addition_content_'.$k);
                $Wcms->deletePageKey($Wcms->currentPageTree, 'addition_content_show_'.$k);

                for ($i=$k+1 ;$i!==0; $i++) {
                    $addition_content = getContentV2('addition_content_'.$i);
                    $addition_content_show = (getContentV2('addition_content_show_'.$i)==='hide') ? 'hide':'show';
                    if (empty($addition_content)) {
                        break;
                    }
                    $Wcms->deletePageKey($Wcms->currentPageTree, 'addition_content_'.$i);
                    $Wcms->deletePageKey($Wcms->currentPageTree, 'addition_content_show_'.$i);
                    $Wcms->updatePage($Wcms->currentPageTree, 'addition_content_'.$k, $addition_content);
                    $Wcms->updatePage($Wcms->currentPageTree, 'addition_content_show_'.$k, $addition_content_show);
                    $k++;
                }
            }
            die;
        }
        if (isset($_POST['addac']) && $Wcms->hashVerify($requestToken)) {
            $key = $_POST['addac'];
            $content = $_POST['content'];
            list($_, $k) = explode('content_', $key);
            $bf_addition_content = getContentV2('addition_content_'.$k);
            $bf_addition_content_show = (getContentV2('addition_content_show_'.$k)=='hide') ? 'hide':'show';
            if (!empty($bf_addition_content)) {
                for ($i=$k+1 ;$i!=0; $i++) {
                    $addition_content = getContentV2('addition_content_'.$i);
                    $addition_content_show = (getContentV2('addition_content_show_'.$i)=='hide') ? 'hide':'show';
                    $key = 'addition_content_'.$i;
                    $Wcms->updatePage($Wcms->currentPageTree, $key, $bf_addition_content);
                    $key = 'addition_content_show_'.$i;
                    $Wcms->updatePage($Wcms->currentPageTree, $key, $bf_addition_content_show);
                    if (empty($addition_content)) {
                        break;
                    }
                    $bf_addition_content = $addition_content;
                    $bf_addition_content_show = $addition_content_show;
                    $k++;
                }
            }
            $key = 'addition_content_1';
            $content = $_POST['content'];
            $Wcms->updatePage($Wcms->currentPageTree, $key, $content);
            $key = 'addition_content_show_1';
            $content = 'hide';
            $Wcms->updatePage($Wcms->currentPageTree, $key, $content);
            die;
        }
        $content = '<div id="contents"  class="addition_contents">'.$content;
        $content.='
        <br /><b style="cursor: pointer;" value="1" class="content_plus" data-toggle="tooltip" title="Add new editable area">+ Add new editable area</b><br style="font-size: 1.1em;"/>';
        for ($i=1; $i!=0; $i++) {
            $addition_content = getContentV2('addition_content_'.$i);
            if (empty($addition_content)) {
                break;
            }
            $content.='<p></p>';
            $addition_content_show = getContentV2('addition_content_show_'.$i);
            $addition_content_show = ($addition_content_show) ? $addition_content_show:'show';
            $content.='
            <div class="addition_content">';
            if ($addition_content_show=='show') {
                $content.='
                <br /><b style="cursor:pointer" value="'.$i.'" class="toolbar content_hide" data-toggle="tooltip" title="Hide"> Hide editable area (currently visible)</b>';
            } else {
                $content.='
                <br /><b style="cursor:pointer" value="'.$i.'" class="toolbar content_show" data-toggle="tooltip" title="Show"> Show editable area to visitors (currently hidden)</b>';
            }
            $content.='
            <b  style="cursor: pointer; float: right;" value="'.$i.'" class="toolbar content_delete" data-toggle="tooltip" title="Remove editable area"> Remove editable area</b>
            </div>';
            $content.= '
            <hr />';
            $content.= $addition_content = $Wcms->editable('addition_content_'.$i, $addition_content, 'pages');
        }
        $content.= '</div>';
    } else {
        $content = '<div id="content">'.$content.'</div>';
        for ($i=1; $i!=0; $i++) {
            $addition_content = getContentV2('addition_content_'.$i);
            if (empty($addition_content)) {
                break;
            }
            $addition_content_show = getContentV2('addition_content_show_'.$i);
            $addition_content_show = ($addition_content_show) ? $addition_content_show:'show';
            if ($addition_content_show=='show')
                $content.='<hr /><div id="addition_content_'.$i.'">'.$addition_content.'</div>';
        }
    }
    $contents[0] = $content;
    return $contents;
}

function getContentV2($key, $page = false) {
    global $Wcms;
    $segments = $Wcms->currentPageExists
        ? $Wcms->getCurrentPageData()
        : ($Wcms->get('config','login') == $Wcms->currentPage
            ? (object) $Wcms->loginView()
            : (object) $Wcms->notFoundView());
    return $segments->$key ?? false;
}
