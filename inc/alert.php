        <style type="text/css">
        .alert {
          padding: 15px;
          margin-bottom: 20px;
          border: 1px solid transparent;
          border-radius: 4px;
        }
        .alert h4 {
          margin-top: 0;
          color: inherit;
        }
        .alert .alert-link {
          font-weight: bold;
        }
        .alert > p,
        .alert > ul {
          margin-bottom: 0;
        }
        .alert > p + p {
          margin-top: 5px;
        }
        .alert-success {
          color: #3c763d;
          background-color: #dff0d8;
          border-color: #d6e9c6;
        }
        .alert-success hr {
          border-top-color: #c9e2b3;
        }
        .alert-success .alert-link {
          color: #2b542c;
        }
        .alert-info {
          color: #31708f;
          background-color: #d9edf7;
          border-color: #bce8f1;
        }
        .alert-info hr {
          border-top-color: #a6e1ec;
        }
        .alert-info .alert-link {
          color: #245269;
        }
        </style>
        <?php 
            if($lang == "sk"){
        ?>
        <div class="alert alert-info">
            Bola vydaná nová aplikácia WebDril dostupná na <strong><a href="http://web.drilapp.com">web.drilapp.com</a></strong>, 
            ktorá postune <strong>nahradí</strong> tieto stránky.
            <br/>

            <ul>
                <li>V novej verzii už nie je potrebné manuálne importovať slovíčka do Android aplikácie, automaticky sa synchronizujú.</li>
                <li>Dáta sú lepšie/prehladnejšie strutkúrované Učebnice/Lekcie/Slovíčka</li>
                <li>Nová verzia stránok je výrazne rýchlejšia</li>
            </ul>    
        </div>
        <?php 
            }else{
        ?>
            <div class="alert alert-info">
                The new version of this page was released. The web dril is available on <strong><a href="http://web.drilapp.com">web.drilapp.com</a></strong>.
                <br/>
                What's new?
                <ul>
                    <li>Synchronization with Android application</li>
                    <li>Better data organization</li>
                    <li>Improved performance and user interface</li>
                </ul>    
            </div>
        <?php 
            }
        ?>