<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Maxi blog du Van VW :)</title>
        <link rel="icon" 
              type="image/png" 
              href="images/logo.png">
        <script type="text/javascript" src="jQuery.js"></script>
        <script type="text/javascript" src="downloadScripts.js"></script>
        <script type="text/javascript" src="script.js"></script>

        <?php
        include('post.php');
        include('user.php');
        $user = new user();
        $user->create("g", "mdp");
        ?>
    </head>

    <body>
        <div id="bloc_page">
            <header> 

                <nav>
                    <div><a href="#"><img src="images/face.png" alt="Logo VW" id="logo" />Photos</a></div>
                    <div><a href="#"><img src="images/face_blue.png" alt="Logo VW" id="logo" />Trajet</a></div>
                    <div><a href="#"><img src="images/face_red.png" alt="Logo VW" id="logo" />Team</a></div>
                    <div><a href="#"><img src="images/face_green.png" alt="Logo VW" id="logo" />Van</a></div>
                    <div><a href="#"><img src="images/face_yellow.png" alt="Logo VW" id="logo" />Blog</a></div>
                </nav>
                <div id="banniere_image">
                    <div id="banniere_description">
                        Blog du VW bus...
                        <a href="#" class="bouton_rouge">
                            Voir l'article 
                            <img src="images/flecheblanchedroite.png" alt="" />
                        </a>
                    </div>
                </div>
            </header>
            <?php
            $post_db = new Posts();
            foreach ($post_db->post_tab as $row) {
                ?> <section class="post">
                    <article>
                        <h1><img src="images/ico_epingle.png" alt="Catégorie voyage"
                                 class="ico_categorie" /><?php echo $row[4]; ?></h1>
                        <legend>by user the <?php $row[2] ?></legend>
                        <p><?php echo $row[7]; ?></p>
                    </article>
                    <aside>
                        <?php
                        foreach ($row[6]->coms_tab as $com) {
                            ?>
                            <div class="comment">
                                <h1><?php echo $com[2] . ', the ' . $com[1]; ?> </h1>
                                <p> <?php echo $com[3]; ?> </p>
                            </div>
                            <?php
                        }
                        ?>

                        <div class="write">
                            <div class="fake_textarea">
                                <textarea class="write_comment" placeholder="write something"></textarea>
                                <div class="submit_comment">
                                    <input type="submit" value="post"/>
                                </div>
                            </div>
                        </div>
                    </aside>
                </section>
                <?php
            }
            ?>

            <section class="post">
                <article>
                    <h1>Vive l'aventure et la programation !</h1>
                    <legend>by guigui the 6.1 at 21:10</legend>
                    <p>Je suis un geek amoureux de mon van ;)</p>
                </article>

                <aside>
                    <div class="comment">
                        <h1>Funny fact</h1>
                        <p>
                            C'est la class, on geek un max n'est ce pas?
                        </p>
                        <p> 
                            Ce blog sera alimente par mes fautes d'orthographe et mon nouveau
                            Nexus4.... yeah yeah :)
                        </p>
                    </div>
                    <div class="comment">
                        <h1>Comment #2</h1>
                        <p>
                            C'est la class, on geek un max n'est ce pas?
                        </p>
                    </div>
                    <div class="write">
                        <div class="fake_textarea">
                            <textarea class="write_comment" placeholder="write something"></textarea>
                            <div class="submit_comment">
                                <input type="submit" value="post"/>
                            </div>
                        </div>
                    </div>
                </aside>
            </section>

            <section class="post">
                <article>
                    <h1>Paint your own car for under $200</h1>
                    <legend>by guigui the 6.1 at 20:45</legend>
                    <p>I have a 1971 VW Westfalia camper bus. It was in bad need of a paint job when I got it. Someone had painted it with what I believe to be house paint. No bueno :( I decided I had to take care of it. I figured I would do it cheaply, but I wanted it to not be an embarassment either. The reason I was so interested in d.i.y. and cheap is because I want to take it camping but not stress about scratches etc. I wasnt shooting for show-quality paint here.

                    </p><p>   After some googling I came across 2 really good articles on painting your own vehicle with "Rustoleum", so I figured I was game. The two sites are:

                    </p><p>   The $50 Paint Job  </p>
                    <p>   A Cheapskate’s Paint Job </p>
                </article>

                <aside>
                    <div class="write">
                        <div class="fake_textarea">
                            <textarea class="write_comment" placeholder="write something"></textarea>
                            <div class="submit_comment">
                                <input type="submit" value="post"/>
                            </div>
                        </div>
                    </div>
                </aside>
            </section>

            <footer>
                <div id="fact">
                    <h1>Dernier Pouletty fact</h1>
                    <p>Il a de nouveau dormis dehors!</p>
                    <p>le 12 mai à 23h12</p>
                </div>
                <div id="mes_photos">
                    <h1>Mes photos</h1>
                    <p><img src="images/photo1.jpg" alt="Photographie" /><img src="images/photo2.jpg" alt="Photographie" /><img src="images/photo3.jpg" alt="Photographie" /><img src="images/photo4.jpg" alt="Photographie" /></p>
                </div>
                <div id="mes_amis">
                    <h1>Mes amis</h1>
                    <ul>
                        <li><a href="#">Pupi le lapin</a></li>
                        <li><a href="#">Mr Baobab</a></li>
                        <li><a href="#">Kaiwaii</a></li>
                        <li><a href="#">Perceval.eu</a></li>
                    </ul>
                    <ul>
                        <li><a href="#">Belette</a></li>
                        <li><a href="#">Le concombre masqué</a></li>
                        <li><a href="#">Ptit prince</a></li>
                        <li><a href="#">Mr Fan</a></li>
                    </ul>
                </div>
            </footer>

    </body>
</html>

