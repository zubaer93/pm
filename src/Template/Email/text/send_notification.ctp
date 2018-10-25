<!DOCTYPE html>    
<html>
    <head>
        <style>
            div.container {
                width: 100%;
                border: 1px solid gray;
            }

            header, footer {
                padding: 1em;
                background-color: white;
                clear: left;
                text-align: center;
            }
            header{
                border-bottom: 1px solid #313131;
            }
            footer{
                background: linear-gradient(to bottom, #555555 0%,#313131 100%);
                color: rgba(255,255,255,0.6);
            }
            nav {
                float: left;
                max-width: 160px;
                margin: 0;
                padding: 1em;
            }

            nav ul {
                list-style-type: none;
                padding: 0;
            }

            nav ul a {
                text-decoration: none;
            }

            article {
                background-color: rgba(0,0,0,0.05);
                padding: 1em;
                overflow: hidden;
            }
        </style>
    </head>
    <body>

        <div class="container">

            <header>
                <a href="<?= $this->Url->build('/', true) ?>" >
                    <img src="<?= $this->Url->build('frontend_theme/img/_smarty/stockgitter_header_logo.jpg') ?>" alt="StockGitter Logo" width="150">
                </a>
            </header>

            <article>
                <h1><?= __('Dear') . ' ' . $to_first_name . ' ' . $to_last_name ?></h1>
                <p>London is the capital city of England. It is the most populous city in the  United Kingdom, with a metropolitan area of over 13 million inhabitants.</p>
                <p>Standing on the River Thames, London has been a major settlement for two millennia, its history going back to its founding by the Romans, who named it Londinium.</p>

                <p><?= __('StockGitter Team'); ?></p>
            </article>
            <footer>&copy; <?= __(' All Rights Reserved, StockGitter LTD'); ?> </footer>

        </div>

    </body>
</html>