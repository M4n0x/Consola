<!DOCTYPE HTML>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <base href="<?php echo $WEBROOT; ?>">
        <link rel="stylesheet" href="./css/bootstrap.min.css" media="all"/>
        <link rel="stylesheet" href="./css/font-awesome.css" />
        <link rel="stylesheet" href="./css/basic.css" />
        <?php if (!empty($stylesheets)): ?>
            <?php foreach ($stylesheets as $stylesheet): ?>
                <link rel="stylesheet" href="./css/<?= $stylesheet ?>" />
            <?php endforeach; ?>
        <?php endif; ?>
        <title><?= (Config::get("Global", "title") . ((empty($titre)) ? "" : " - " . $titre)) ?></title>
    </head>
    <body>
        <div class="global">
            <?php if (Session::get("flash")->hasMessages()): ?>
                <div class="alert-box">
                    <?php foreach (Session::get("flash")->getMessages() as $type => $messages): ?>
                        <div id="modal-alert" class="msg msg-<?= $type; ?>" >
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <div class="clearfix">
                                <div class="icon"><i></i></div>
                                <ul class="msg-list">
                                    <?php foreach ($messages as $message): ?>
                                        <li><?= $message ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="wrapper">
                <div class="container-fluid" style="height: 100%;">
                    <div class="row-fluid" style="">
                        <!-- TERMINAL -->
                        <div class="col-sm-12 col-xs-12 col-md-10">
                            <div class="consola-entries"></div>
                        </div>
                        <div class="col-md-2 hidden-sm hidden-xs">
                            <div class="consola-sidebar-menu">
                                <ul class="consola-options" role="menu">
                                    <li class="active"><a href="user"><span class="fa fa-fw fa-user fa-2x"></span> Utilisateur</a></li>
                                    <li><a href="#"><span class="fa fa-fw fa-wrench fa-2x"></span> Outils</a></li>
                                    <li><a href="#"><span class="fa fa-fw fa-newspaper-o fa-2x"></span> News</a></li>
                                    <li><a href="#"><span class="fa fa-fw fa-eye fa-2x"></span> Admin</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-xs-12 input-col-terminal">
                            <div class="left-inner-glyph input-terminal">
                                <i class="fa fa-terminal"></i>
                                <input type="text" class="form-control" spellcheck="false"/>

                            </div>
                            <div class="console-option btn-group dropup">
                                    <button type="button" data-toggle="dropdown" aria-expanded="false">
                                        <span class="fa fa-cog fa-fw"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                      <li><a href="#">Action</a></li>
                                      <li><a href="#">Another action</a></li>
                                      <li><a href="#">Something else here</a></li>
                                      <li class="divider"></li>
                                      <li><a href="#">Separated link</a></li>
                                    </ul>
                                </div>
                        </div>

                    </div>    
                </div>
            </div>
        </div> <!-- #global -->
        <script type="text/javascript" src="./js/jquery.js"></script>
        <script type="text/javascript" src="./js/bootstrap.min.js"></script>
        <script type="text/javascript" src="./js/global.js"></script>
        <?php if (!empty($javascripts)): ?>
            <?php foreach ($javascripts as $javascript): ?>
                <script type="text/javascript" src="./js/<?= $javascript ?>"></script>
            <?php endforeach; ?>
        <?php endif; ?>
    </body>
</html>
