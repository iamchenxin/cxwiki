<?php
/**
 * DokuWiki Default Template 2012
 *
 * @link     http://dokuwiki.org/template
 * @author   Anika Henke <anika@selfthinker.org>
 * @author   Clarence Lee <clarencedglee@gmail.com>
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

if (!defined('DOKU_INC')) die(); /* must be run from within DokuWiki */
header('X-UA-Compatible: IE=edge,chrome=1');

$hasSidebar = page_findnearest($conf['sidebar']);
$showSidebar = $hasSidebar && ($ACT=='show');

function personal_sidebar()
{
    $usersidebar = "user:";
    $usera =$_SERVER['REMOTE_USER'];
    if($usera){
        $usersidebar = $usersidebar.$usera.":";
    }else{
        $usersidebar = $usersidebar."unlog:"; // show the unlog sidebar
    }
    $usersidebar = $usersidebar .'sidebar'; //$conf['sidebar']  but i do not kown how to ref global val
    $tmp = tpl_include_page($usersidebar, true, false);
    if(!$tmp){ // use this method to make at mostly time ,tpl_include_page will excute once
        $userpage="user:".$INFO['client'];
        echo <<<END
<a title="$usersidebar" class="wikilink1" href="/$usersidebar?do=edit">create your sidebar</a><br/><span>Link code for sidebar: [[:$usersidebar]]</span><br/>
<a title="$userpage" class="wikilink1" href="/$userpage?do=edit">create your homepage</a><br/><span>Link code for userpage: [[:$userpage]]</span><br/>
END;
        tpl_include_page("user:none:".'sidebar', true, false); // user do not create his sidebar.
    }
}
?><!DOCTYPE html>
<html lang="<?php echo $conf['lang'] ?>" dir="<?php echo $lang['direction'] ?>" class="no-js">
<head>
    <meta charset="utf-8" />
    <title><?php tpl_pagetitle() ?> [<?php echo strip_tags($conf['title']) ?>]</title>
    <script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>
    <?php tpl_metaheaders() ?>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <?php echo tpl_favicon(array('favicon', 'mobile')) ?>
    <?php tpl_includeFile('meta.html') ?>
</head>

<body>
    <!--[if lte IE 7 ]><div id="IE7"><![endif]--><!--[if IE 8 ]><div id="IE8"><![endif]-->
    <div id="dokuwiki__site"><div id="dokuwiki__top" class="site <?php echo tpl_classes(); ?> <?php
        echo ($showSidebar) ? 'showSidebar' : ''; ?> <?php echo ($hasSidebar) ? 'hasSidebar' : ''; ?>">

        <?php include('tpl_header.php') ?>

            <div id="xxrightbar">
                <?php
                $ffbar= plugin_load("helper","ajaxpeon");
                $ffbar->make_searchbox();
                ?>
            </div>
            <div class="xxcontenthook rawedges" id="xxsidebar">

                <?php if(1): ?><!--[ $showSidebar ] -->
                    <!-- ********** ASIDE ********** -->
                    <div id="dokuwiki__aside"><div class="pad aside include group">
                            <h3 class="toggle"><?php echo $lang['sidebar'] ?></h3>
                            <div class="content">
                                <?php tpl_flush() ?>
                                <?php tpl_includeFile('sidebarheader.html') ?>
                                <?php personal_sidebar() ?>
                                <?php tpl_includeFile('sidebarfooter.html') ?>
                            </div>
                        </div></div><!-- /aside -->
                <?php endif; ?>
            </div>
            <div id="backtotop"><a href="#dokuwiki__content" id="realtotop"><img src="<?php echo tpl_basedir(); ?>images/totop.png"/></a></div>
        <div class="wrapper group" id="xxexpandcon">

            <!-- ********** CONTENT ********** -->
            <div id="dokuwiki__content"><div class="pad group">


                    <div class="xxtranslation">
                            <?php
                            $translation = plugin_load('helper','translation');
                            if ($translation) echo $translation->showTranslations();
                            ?>
                    </div>
                    <div id="xxtoolpop"></div>

                    <div id="xxexpand" onclick="xxexpandcontent()"><img src="<?php echo tpl_basedir(); ?>images/expand.png"/></div>
                <div class="pageId">
                    <span><?php echo hsc($ID) ?></span>
                </div>

                <div class="page group">
                    <?php tpl_flush() ?>
                    <?php tpl_includeFile('pageheader.html') ?>
                    <!-- wikipage start -->
                    <?php tpl_content() ?>
                    <!-- wikipage stop -->
                    <?php tpl_includeFile('pagefooter.html') ?>
                </div>

                <div class="docInfo"><?php tpl_pageinfo() ?></div>

                <?php tpl_flush() ?>
            </div></div><!-- /content -->

            <hr class="a11y" />

            <!-- PAGE ACTIONS -->
            <div id="dokuwiki__pagetools">
                <h3 class="a11y"><?php echo $lang['page_tools']; ?></h3>
                <div class="tools">
                    <ul>
                        <?php
                            $data = array(
                                'view'  => 'main',
                                'items' => array(
                                    'edit'      => tpl_action('edit',      true, 'li', true, '<span>', '</span>'),
                                    'revert'    => tpl_action('revert',    true, 'li', true, '<span>', '</span>'),
                                    'revisions' => tpl_action('revisions', true, 'li', true, '<span>', '</span>'),
                                    'backlink'  => tpl_action('backlink',  true, 'li', true, '<span>', '</span>'),
                                    'subscribe' => tpl_action('subscribe', true, 'li', true, '<span>', '</span>'),
                                    'top'       => tpl_action('top',       true, 'li', true, '<span>', '</span>')
                                )
                            );

                            // the page tools can be amended through a custom plugin hook
                            $evt = new Doku_Event('TEMPLATE_PAGETOOLS_DISPLAY', $data);
                            if($evt->advise_before()){
                                foreach($evt->data['items'] as $k => $html) echo $html;
                            }
                            $evt->advise_after();
                            unset($data);
                            unset($evt);
                        ?>
                    </ul>
                </div>
            </div>
        </div><!-- /wrapper -->

        <?php include('tpl_footer.php') ?>
    </div></div><!-- /site -->

    <div class="no"><?php tpl_indexerWebBug() /* provide DokuWiki housekeeping, required in all templates */ ?></div>
    <div id="screen__mode" class="no"></div><?php /* helper to detect CSS media query in script.js */ ?>
    <!--[if ( lte IE 7 | IE 8 ) ]></div><![endif]-->
</body>
</html>