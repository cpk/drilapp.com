<?php 
    session_start(); 
    if(!isset($_GET['s']))	{ $_GET['s'] = 1; }else{  $_GET['s'] = intval( $_GET['s'] ); }

    require_once "admin/config.php";
    require_once "admin/inc/fnc.main.php";
    require_once "admin/page/fnc.page.php";
    require_once "inc/fnc.php";
    require_once "inc/messageSource.php";

    function __autoload($class) {
            require_once 'admin/inc/class.'.$class.'.php';
    }
    
    try{
        $lang = (!isset($_GET['lang']) ? "sk" : $_GET['lang']);
        $conn = Database::getInstance($config['db_server'], $config['db_user'], $config['db_pass'], $config['db_name']);
        $meta = MAIN();
        $auth = new Authenticate($conn);
        if($meta["id_article"] == 20){
          $auth->logout();
          header('Location: ' . linker(16, 1, $lang));  
        }
        if(intval($meta['c_status']) == 0){
            die($meta['c_offline_msg']);
        }
	       $nav = printMenu(0, "",false);
        $catalogNav = null;
        
        if(isset($_GET['id'])){
            $_GET['id'] = (int)$_GET['id']; 
            $bookName = getBookName($_GET['id']);
            if($bookName == null){
                header("Location: /error/page.php?p=404");
            }
         }
        
?>
<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo (isset($_GET['id']) ? $bookName : $meta["title_${lang}"]).' - '. $meta['c_name']; ?></title>
    <meta charset="utf-8" />
    <meta name="robots" content="index,follow"/>
    <meta name="description" content="<?php  echo substr($meta["header_${lang}"], 0 , 200); ?>"/>
    <link rel="shortcut icon" href="/img/favicon.png" />
    <link href='http://fonts.googleapis.com/css?family=Signika' rel='stylesheet' type='text/css'>
    <!-- styles & js -->
    <link rel="stylesheet" href="/css/main.css" />
    <link rel="stylesheet" href="/css/slider.css" />
    <link rel="stylesheet" href="/css/jquery.lightbox-0.5.css" />
    <!--[if IE]>  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script> <![endif]-->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/json2/20110223/json2.js"></script>
    <script src="https://raw.github.com/andris9/jStorage/master/jstorage.js"></script>
    <script src="/js/jquery.slider.js"></script>
    <script src="/js/jquery.lightbox-0.5.min.js"></script>
    <script src="/js/jquery.hashchange.min.js"></script>
    <script src="/js/jquery.easytabs.min.js"></script>
    <script src="/js/scripts.js"></script>
    <script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-36345964-1']);
	  _gaq.push(['_trackPageview']);
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
</script>
</head>
<body>
    <div id="status"></div>
    <header>
        <div id="header" class="def-width">
            <a href="<?php echo linker(1, 1, $lang)?>" id="logo" title="DRIL app home page">
                <img src="/img/logo.png" alt="ANDROID DRIL APP" />
            </a>
            <?php echo printLangNav(); ?>
           <!-- <span id="news"><?php echo ($lang=="sk" ? "novinka" : "new")?><em>&darr;</em> </span> -->
            <nav>
                <ul>
                    <?php echo $nav; ?>
                </ul>
            </nav>
            <div id="vote" title="Web Dril 1.0"></div>
    	</div>
         

    </header>
    
    <div id="sliderbox">
        <div id="slider-w" class="def-width relative">
        <!-- Google Play hrefs -->

        <?php         //  print_r($_GET);
            include "inc/router.php";
        ?>


            
        </div>
    </div>
    <div class="line"></div>
    <div id="content">
        <div class="def-width relative">
            <span class="quote l"></span>
            <span class="quote r"></span>
            <ul id="slider-quote">
                <?php echo getReviews(); ?>
            </ul>
        </div>
    </div>
    <div class="line-b"></div>
    <div id="circles">
        <div class="bg-blue" id="main-keys">
          <div class="container">
            <div class="row">
              <?php 
              $article = getArticle("basic", 2, $lang); 
              ?>
                <a href="<?php echo linker(2, 1, $lang)?>" class="span4">
                <div class="circle-white">
                  <span style="background-image: url('/img/write.png');" class="icon"></span>
                  <h3 class="dotted-black"><?php echo $article[0]["title_${lang}"]?></h3>
                  <p><?php echo $article[0]["header_${lang}"]?></p>
                </div>
              </a>
             <?php $article = getArticle("basic",3, $lang); ?>
             <a href="<?php echo linker(3, 1, $lang)?>" class="span4">
                <div class="circle-white">
                  <span style="background-image: url('/img/chat.png');" class="icon"></span>
                  <h3 class="dotted-black"><?php echo $article[0]["title_${lang}"]?></h3>
                  <p><?php echo $article[0]["header_${lang}"]?></p>
                </div>
              </a>
                <?php $article = getArticle( "basic",5, $lang); ?>
                <a href="<?php echo linker(5, 1, $lang)?>" class="span4">
                <div class="circle-white">
                  <span style="background-image: url('/img/contact.png');" class="icon"></span>
                  <h3 class="dotted-black"><?php echo $article[0]["title_${lang}"]; ?></h3>
                  <p><?php echo $article[0]["header_${lang}"]?></p>
                </div>
              </a>
            </div>
          </div>
        </div>
    </div>
     <div class="line"></div>
     
     <footer>
         <div class="def-width relative">
             <p> <a href="http://www.peterjurkovic.sk" title="Android programátor"><?php echo date('Y').' &copy; '.($lang == "en" ? 'android programmer Peter Jurkovič' : 'android programátor Peter Jurkovič'); ?> </a></p>
             
             <a href="http://cs.wikipedia.org/wiki/HTML5" target="_blank" class="ext html5" title="HTML5 Based"></a>
             <a href="https://www.facebook.com/drilAnglictinaEfektivne" class="ext fb"></a>
             <a href="https://play.google.com/store/apps/details?id=sk.peterjurkovic.dril.de" class="ext play-de"></a>
             <a href="https://play.google.com/store/apps/details?id=sk.peterjurkovic.dril" class="ext play-en"></a>
             
             
             
             
             <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
            <a class="addthis_button_preferred_1"></a>
            <a class="addthis_button_preferred_2"></a>
            <a class="addthis_button_preferred_3"></a>
            <a class="addthis_button_preferred_4"></a>
            <a class="addthis_button_compact"></a>
            <a class="addthis_counter addthis_bubble_style"></a>
            </div>
            <script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4f7805435b56a706"></script>
            <!-- AddThis Button END -->
         </div>
     </footer>
   
</body>
</html>
<?php
    }catch(MysqlException $e){
        echo '<!DOCTYPE HTML><html><head><meta charset="utf-8" /></head><body>'.
             'Vyskytol sa problém s databázou, opráciu skúste zopakovať</body></html>';
    } 
?>
