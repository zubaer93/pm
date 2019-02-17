<html>
    <head>
        <style>
            div.container {
                width: 100%;
            }
            hr {
                border: 0;
                height: 1px;
                background-image: -webkit-linear-gradient(left, transparent, rgb(0, 128, 1), transparent);
            }
            header, footer {
                padding: 1em;
                background-color: white;
                clear: left;
                text-align: center;
            }
            /*            header{
                            border-bottom: 1px solid #8ab933;
                        }*/
            footer{
                background: linear-gradient(to bottom,#00800100 0%,#2626262b 100%);
                color: #777;
            }
            nav {
                float: left;
                max-width: 160px;
                margin: 0;
                padding: 1em;
            }
            .color {
                color:rgb(0, 128, 1);

            }
            a{
                text-decoration: none;
            }

            nav ul {
                list-style-type: none;
                padding: 0;
            }

            nav ul a {
                text-decoration: none;
            }

            article {
                background-color: #fdfdfd;
                padding: 1em;
                overflow: hidden;
            }
        </style>
    </head>
    <body>

        <div class="container">

            <header>
                <a href="<?= $this->Url->build('/', true) ?>" >
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQUBcWjdqkJndDK_18Y8gsdY16HPgguUOL4-qdeWsJ1wHFIPjzX" alt="StockGitter Logo" width="150" title="Logo">
                </a>
            </header>
            <hr>
            <article>
                <h1><?= __('Dear') . ' ' . $to_first_name . ' ' . $to_last_name ?></h1>
                <p><a class="color" href="<?= $siteUrl . '/USD/user/' . $from_username ?>"><?= $full_name ?></a>
                <?= __(' has shared a strategy with you! To view this strategy, click ') ?><a class="color" href="<?= $url ?>"><?= $analysis ?></a> or you can login to stockgitter at anytime and look for shared strategies under My Portfolio.</p>

                <p>Thank You</p>
                <p><span class="color"><?= __('StockGitter '); ?> </span><?= __('Team'); ?></p>
            </article>
            <footer>&copy; <?= __(' All Rights Reserved, StockGitter LTD'); ?> </footer>

        </div>

    </body>
</html>