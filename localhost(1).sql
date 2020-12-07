-- Adminer 4.7.6 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `delta` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `delta`;

DROP TABLE IF EXISTS `blacklist`;
CREATE TABLE `blacklist` (
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `raison` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `commande`;
CREATE TABLE `commande` (
  `id_transaction` text NOT NULL,
  `status` text NOT NULL,
  `articles` text NOT NULL,
  `user` text NOT NULL,
  `sexe_user` text NOT NULL,
  `nom_user` text NOT NULL,
  `prenom_user` text NOT NULL,
  `adresse_user` text NOT NULL,
  `ville_user` text NOT NULL,
  `postal_user` text NOT NULL,
  `date` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `commande` (`id_transaction`, `status`, `articles`, `user`, `sexe_user`, `nom_user`, `prenom_user`, `adresse_user`, `ville_user`, `postal_user`, `date`) VALUES
('7CR77610F0416781F',	'COMPLETED',	'a:4:{i:0;s:2:\"12\";i:1;s:2:\"12\";i:2;s:2:\"12\";i:3;s:2:\"12\";}',	'yiaourt',	'homme',	'Bois',	'Loïc',	'140 Rue de Versailles',	'St Just d\'Ardèche',	'07700',	'2020-05-24 15:25:27'),
('43M16510575572116',	'COMPLETED',	'a:1:{i:0;s:2:\"13\";}',	'yiaourt',	'homme',	'Bois',	'Loïc',	'140 Rue de Versailles',	'St Just d\'Ardèche',	'07700',	'2020-05-25 18:38:48');

DROP TABLE IF EXISTS `forum`;
CREATE TABLE `forum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` text CHARACTER SET latin1 NOT NULL,
  `ordre` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `forum` (`id`, `titre`, `ordre`) VALUES
(16,	'Projet Delta',	1),
(18,	'Arts et Multimédia',	2),
(33,	'Informatique et Programmation',	3);

DROP TABLE IF EXISTS `home`;
CREATE TABLE `home` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contenu` longtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `home` (`id`, `contenu`) VALUES
(13,	'<div id=\"center\">\r\n<pre>\r\n ██████████            ████   █████                         ███████████                          ███                     █████   \r\n░░███░░░░███          ░░███  ░░███                         ░░███░░░░░███                        ░░░                     ░░███    \r\n ░███   ░░███  ██████  ░███  ███████    ██████              ░███    ░███ ████████   ██████      █████  ██████   ██████  ███████  \r\n ░███    ░███ ███░░███ ░███ ░░░███░    ░░░░░███  ██████████ ░██████████ ░░███░░███ ███░░███    ░░███  ███░░███ ███░░███░░░███░   \r\n ░███    ░███░███████  ░███   ░███      ███████ ░░░░░░░░░░  ░███░░░░░░   ░███ ░░░ ░███ ░███     ░███ ░███████ ░███ ░░░   ░███    \r\n ░███    ███ ░███░░░   ░███   ░███ ███ ███░░███             ░███         ░███     ░███ ░███     ░███ ░███░░░  ░███  ███  ░███ ███\r\n ██████████  ░░██████  █████  ░░█████ ░░████████            █████        █████    ░░██████      ░███ ░░██████ ░░██████   ░░█████ \r\n░░░░░░░░░░    ░░░░░░  ░░░░░    ░░░░░   ░░░░░░░░            ░░░░░        ░░░░░      ░░░░░░       ░███  ░░░░░░   ░░░░░░     ░░░░░  \r\n                                                                                            ███ ░███                             \r\n                                                                                           ░░██████                              \r\n                                                                                            ░░░░░░                               </pre>\r\n</div>\r\n\r\n<p style=\"text-align:center\">Salut, et bienvenue &agrave; tous sur &quot;<u>Le Projet Delta</u>&quot;,</p>\r\n\r\n<p style=\"text-align:center\">le projet Delta est un <strong>magasin d&#39;impr&eacute;ssions 3D</strong> ainsi qu&#39;un forum ou reigne <strong>partages</strong> et <strong>discussions</strong> sur des sujets artistiques et informatiques, pouvant servir &agrave; inspirer des projets et &agrave; les faire avancer &agrave; leur phase Delta !</p>\r\n\r\n<p style=\"text-align:center\">Le site est le projet du projet d&#39;un projet, lui m&ecirc;me, projet, du nom de Projet Delta !</p>\r\n');

DROP TABLE IF EXISTS `home_preview`;
CREATE TABLE `home_preview` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contenu` text CHARACTER SET utf8 NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `messagerie`;
CREATE TABLE `messagerie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_principal` varchar(110) COLLATE utf8_unicode_ci NOT NULL,
  `user_distant` text COLLATE utf8_unicode_ci NOT NULL,
  `objet` varchar(110) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_new` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `messagerie_comment`;
CREATE TABLE `messagerie_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg_id` int(11) NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `auteur` text COLLATE utf8_unicode_ci NOT NULL,
  `date` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `messagerie_comment` (`id`, `msg_id`, `comment`, `auteur`, `date`) VALUES
(1,	3,	'<p>Voici le tout premier commentaire de la messagerie portant le n°1 !!! <img src=\"emoticons/grin.png\" data-sceditor-emoticon=\":D\" alt=\":D\" title=\":D\"> <img src=\"emoticons/heart.png\" data-sceditor-emoticon=\"<3\" alt=\"<3\" title=\"<3\"><br></p><div>Des bisous, la bonne joirnée.<br></div><p><iframe src=\"https://www.youtube.com/embed/tXQzAApb4WM?wmode=opaque\" data-youtube-id=\"tXQzAApb4WM\" allowfullscreen=\"\" width=\"560\" height=\"315\" frameborder=\"0\"></iframe><br><br><br></p>',	'nemol',	'2018-10-21 19:01:58'),
(2,	3,	'<p>Voilààààà les commentaires !!! Reste les boutons modifier et supprimer un commentaire, à relier.<br></p>',	'nemol',	'2018-10-21 09:39:04'),
(3,	3,	'Commentaire supprimer par un administrateur.',	'test',	'2018-10-21 19:19:29'),
(4,	34,	'<p>Hey mec, elle arrive la caisse !</p><p><img src=\"https://media1.tenor.com/images/02b047c879d50f86981c72cd9d89f769/tenor.gif\"><br></p>',	'nemol',	'2018-11-05 20:27:03');

DROP TABLE IF EXISTS `onglet_special`;
CREATE TABLE `onglet_special` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `texte` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `couleur` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `onglet_special` (`id`, `texte`, `couleur`) VALUES
(1,	'PARTAGE',	'#F4E324'),
(2,	'DISCUSSIONS',	'#6FD500'),
(5,	'PROJET',	'#00ff00'),
(6,	'QUESTION',	'#278900'),
(8,	'HTML5',	'#F72F2F'),
(9,	'CSS3',	'#2B5AA3'),
(13,	'SCSS',	'#0042ff'),
(14,	'PHP5',	'#ff8400'),
(15,	'Javascript',	'#00ffea'),
(16,	'C/C++/C#',	'#000000'),
(17,	'Python',	'#ba00ff'),
(18,	'Logiciels',	'#FF0000');

DROP TABLE IF EXISTS `shop`;
CREATE TABLE `shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` text NOT NULL,
  `description` text NOT NULL,
  `prix` int(11) NOT NULL,
  `category` mediumtext NOT NULL,
  `image1` text NOT NULL,
  `image2` text NOT NULL,
  `image3` text NOT NULL,
  `image4` text NOT NULL,
  `image5` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `shop` (`id`, `titre`, `description`, `prix`, `category`, `image1`, `image2`, `image3`, `image4`, `image5`) VALUES
(12,	'Figurine de Rick avec la tête qui bouge',	'<p>blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla</p>\r\n',	5,	'3, 6',	'img/shop/Rick_large.gif',	'',	'',	'',	''),
(13,	'Grinder avec un motif',	'<p>blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla</p>\r\n',	5,	'8, 4, 5',	'img/shop/3D_printed_grinder_cannabis.jpg',	'',	'',	'',	''),
(14,	'Buste de Albert Einstein',	'<p>blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla blabla bla</p>\r\n',	10,	'3',	'img/shop/bust_1.jpg',	'',	'',	'',	''),
(15,	'Grinder avec un motif',	'<p>blablabla bla blalabla blablabla bla blalabla blablabla bla blalabla blablabla bla blalabla blablabla bla blalabla blablabla bla blalabla blablabla bla blalabla blablabla bla blalabla blablabla bla blalabla blablabla bla blalabla blablabla bla blalabla</p>\r\n',	5,	'5, 4, 8',	'img/shop/720X720-dfeb24e088247735c16f6d347e3d7f979633b846.jpg',	'',	'',	'',	''),
(16,	'figurine d\'une créature',	'<p>blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla blabla bla bla bla</p>\r\n',	10,	'3',	'img/shop/Beholder_large.jpg',	'',	'',	'',	'');

DROP TABLE IF EXISTS `shop_category`;
CREATE TABLE `shop_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `shop_category` (`id`, `name`) VALUES
(3,	'Arts'),
(4,	'Outils'),
(5,	'Utiles'),
(6,	'Jouets'),
(7,	'Bijoux'),
(8,	'Accessoires');

DROP TABLE IF EXISTS `sous_forum`;
CREATE TABLE `sous_forum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` text CHARACTER SET latin1 NOT NULL,
  `id_cat` int(11) NOT NULL,
  `description` text CHARACTER SET latin1 NOT NULL,
  `ordre` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `sous_forum` (`id`, `titre`, `id_cat`, `description`, `ordre`) VALUES
(28,	'Inforgraphie 2D et Dessins',	18,	'Discussions et partages d\'astuces, logiciels et de dessins artistique en 2D.',	0),
(23,	'Cahier des charges',	16,	'Le cahier des charges qui regroupe toutes les tâches à faire en programation sur le projet Delta !',	0),
(50,	'F.A.Q',	16,	'Vous pouvez poser vos questions sur le Projet-Delta, ici, nous tâcherons d\'y répondre.',	1),
(27,	'Infographie 3D et Animations',	18,	'Discussions d\'infographie 3D, partages d\'objets 3D imprimables, d\'astuces et de logiciels autour de la 3D.',	1),
(44,	'Web/Web Design',	33,	'Discussions de languages, Partages d\'astuces, de tutoriaux, de code Open Source sur la programmation Web/Web Design',	0),
(45,	'Compiler/GUI',	33,	'Discussions de languages, Partages d\'astuces, de tutoriaux, de code Open Source autour des languages tel que le C++/Python ...',	1),
(46,	'Musiques et Instruments',	18,	'Discussions et Partages de Musiques/Samples, d\'Astuces sur des Logiciels et Instruments musicaux.',	2),
(49,	'Système d\'exploitation',	33,	'Discussions autour des systèmes d\'exploitation, Tutoriaux, Résolutions de problèmes. (Balises obligatoire.)',	2);

DROP TABLE IF EXISTS `sous_forum_topic`;
CREATE TABLE `sous_forum_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_s_cat` int(11) NOT NULL,
  `auteur` text CHARACTER SET latin1 NOT NULL,
  `OSS` varchar(255) CHARACTER SET latin1 NOT NULL,
  `titre` text CHARACTER SET latin1 NOT NULL,
  `contenu` text CHARACTER SET latin1 NOT NULL,
  `contenu_mod` text CHARACTER SET latin1 NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `sous_forum_topic` (`id`, `id_s_cat`, `auteur`, `OSS`, `titre`, `contenu`, `contenu_mod`, `date`) VALUES
(12,	23,	'yiaourt',	'',	'Signalez un bug',	'<p>Bonjour, ce topic servira &agrave; signaler tout bug ou &eacute;rreurs pr&eacute;sent sur le site.<br />\r\n<br />\r\nDelta &eacute;tant une b&ecirc;ta, il se pourrais que vous rencontriez des bugs ou des &eacute;rreurs.<br />\r\nA+</p>\r\n',	'Bonjour, ce topic servira à signaler tout bug ou érreurs présent sur le site. \r\n\r\nDelta étant une bêta nous voulons un site dés plus propre pour le lancement de la delta de delta ! ;)\r\n\r\nMerci de votre attention, bonne joirné\'!',	'2020-03-11 22:00:04'),
(169,	28,	'yiaourt',	' 18',	'Gimp 2.10.18',	'<p>Plus la peine de le pr&eacute;sentez, Gimp est un excellent logiciel de dessin et de retouche d&#39;images et d&#39;autant plus qu&#39;il est gratuit et simple d&#39;utilisation.</p>\r\n\r\n<p><a href=\"https://www.gimp.org/\">GIMP (GNU Image Manipulation Progam)</a></p>\r\n\r\n<p><img src=\"http://www.leparisien.fr/resizer/KGKRZadDBirQ95EwMUEbQLzN76s=/932x582/arc-anglerfish-eu-central-1-prod-leparisien.s3.amazonaws.com/public/KWQG4LCMLX66HHCEHNPYFZFPYM.jpg\" style=\"height:486px; width:779px\" /></p>\r\n',	'',	'2020-04-26 18:57:18'),
(123,	46,	'yiaourt',	' 1',	'Un peu de musique!',	'<p>Yo, ce sujet servira de total d&eacute;positoire de morceaux de musique.</p>\r\n\r\n<p>Bon personnellement, j&#39;&eacute;coute pas mal de reggea dub ...</p>\r\n\r\n<p>Enjoy !</p>\r\n\r\n<div class=\"embededContent oembed-provider- oembed-provider-Soundcloud\" data-align=\"left\" data-maxheight=\"315\" data-maxwidth=\"560\" data-oembed=\"https://soundcloud.com/dawa-hifi/weed-specialist\" data-oembed_provider=\"Soundcloud\" data-resizetype=\"responsive\" data-title=\"weed specialist\" style=\"float: left;\"><iframe frameborder=\"no\" height=\"315\" scrolling=\"no\" src=\"https://w.soundcloud.com/player/?visual=false&amp;hide_related=false&amp;show_comments=false&amp;show_user=false&amp;show_reposts=false&amp;show_teaser=false&amp;&amp;url=https%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F45625211&amp;show_artwork=false&amp;maxwidth=560&amp;maxheight=315&amp;callback=jQuery331024979298204140665_1583859203156&amp;_=1583859203158\" title=\"weed specialist\" width=\"560\"></iframe></div>\r\n\r\n<p><iframe allowfullscreen=\"\" data-youtube-id=\"mvl-3qUQ6sE\" frameborder=\"0\" height=\"315\" src=\"https://www.youtube.com/embed/mvl-3qUQ6sE?wmode=opaque&amp;start=0\" width=\"560\"></iframe></p>\r\n',	'',	'2020-03-16 16:08:54'),
(167,	44,	'yiaourt',	'',	'Librairie Izmir pour des animations sur image',	'<div>Salut, je tiens &agrave; faire partager une applications &quot;css&quot; appeler &quot;Izmir&quot; permettant d&#39;animer vos images avec plusieurs animations de d&eacute;grader ou de bordure animer lorsque la souris passe sur l&#39;image le tout pouvant &ecirc;tre customiser (voir documentation)</div>\r\n\r\n<div>&nbsp;</div>\r\n\r\n<div>N&#39;&eacute;sitez pas &agrave; visiter leur site pour&nbsp; + documentations et demos : <a href=\"https://ciar4n.com/izmir/index.html\">- Izmir - ImageHover CSS Library</a></div>\r\n\r\n<div>&nbsp;</div>\r\n\r\n<div><u>Installation :</u></div>\r\n\r\n<div>&nbsp;</div>\r\n\r\n<div><a href=\"https://github.com/ciar4n/izmir\">Le fichier &quot;izmir.css&quot; &agrave; t&eacute;l&eacute;charger sur Github</a> -&gt; prenez &quot;izmir.css&quot; ou &quot;izmir.min.css&quot; pour une version plus &quot;minimaliste&quot;</div>\r\n\r\n<div>(je vous conseil de prendre izmir.css comme &ccedil;a vous pourrez modifier le fichier selon le design de votre site plus facilement que avec izmir.min.css)</div>\r\n\r\n<div>&nbsp;</div>\r\n\r\n<div><u>&eacute;tape 1 :</u> Ajoutez le fichier &quot;izmir.css&quot; dans votre projet web</div>\r\n\r\n<div>&nbsp;</div>\r\n\r\n<div><u>&eacute;tape 2 :</u> Ajoutez le lien html du fichier css (ci dessous) dans la balises &lt;head&gt; de votre html</div>\r\n\r\n<div>\r\n<pre>\r\n<code class=\"language-html\">&lt;link rel=\"stylesheet\" href=\"css/izmir.min.css\"&gt;\r\n</code></pre>\r\n\r\n<p><u>&eacute;tape 3 :</u> Appliquez les balises html (ci-dessous) sur une image, puis regarder la documentation pour customiser la couleur ou la taille des bordures leur espacement etc... ou bien l&#39;animation de la bordures, de l&#39;image ou du d&eacute;grader.</p>\r\n</div>\r\n\r\n<div>\r\n<pre>\r\n<code class=\"language-html\">&lt;figure class=\"c4-izmir\"&gt;\r\n  &lt;img src=\"https://source.unsplash.com/FaPxZ88yZrw/400x300\" alt=\"Sample Image\"&gt;\r\n  &lt;figcaption&gt;\r\n      &lt;h3&gt;\r\n        Some sample text\r\n      &lt;/h3&gt;\r\n    &lt;/div&gt;\r\n&lt;/figure&gt;\r\n</code></pre>\r\n\r\n<p>&nbsp;</p>\r\n</div>\r\n\r\n<div>(documentation : <a href=\"https://ciar4n.com/izmir/3A-custom-properties.html\">customisation css</a>, <a href=\"https://ciar4n.com/izmir/2C-image-animation.html\">animations image</a>, <a href=\"https://ciar4n.com/izmir/2B-border-animation.html\">animations bordure</a>, <a href=\"https://ciar4n.com/izmir/2E-overlay-style.html\">animations d&eacute;grader</a>, <a href=\"https://ciar4n.com/izmir/2D-text-animation.html\">animations texte</a> etc(...))</div>\r\n\r\n<div>&nbsp;</div>\r\n\r\n<div><br />\r\n&nbsp;</div>\r\n',	'',	'2020-03-10 18:05:00');

DROP TABLE IF EXISTS `tchat`;
CREATE TABLE `tchat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tchat` (`id`, `user`, `message`) VALUES
(1,	'yiaourt',	'Forum ➤ Cahier des charges ➤ Signalez un bug '),
(2,	'yiaourt',	'test'),
(3,	'user',	'&lt;script&gt;alert(\'hello\');&lt;/script&gt;'),
(4,	'yiaourt',	'wesh');

DROP TABLE IF EXISTS `topic_comment`;
CREATE TABLE `topic_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `comment` text CHARACTER SET latin1 NOT NULL,
  `auteur` text CHARACTER SET latin1 NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `topic_comment` (`id`, `topic_id`, `comment`, `auteur`, `date`) VALUES
(124,	12,	'<p><img alt=\"\" src=\"https://i.ibb.co/vht1DrM/Capture-d-cran-2020-03-09-17-50-01.png\" /></p>\r\n\r\n<p> </p>\r\n\r\n<p>Erreurs avec les onglets special pour sujet</p>\r\n\r\n<hr />\r\n<h1><u><strong>Edit :</strong></u> Problème résolus avec un script le 09/03/2020</h1>\r\n\r\n<p> </p>\r\n\r\n<pre>\r\n<code class=\"language-javascript\">var cb = document.querySelectorAll(\"[type=checkbox]\");\r\n\r\nvar i = 0,\r\n\r\n      l = cb.length;\r\n\r\nfor (; i&lt;l; i++)\r\n\r\n    cb[i].addEventListener(\"change\", function (){\r\n        if (document.querySelectorAll(\":checked\").length &gt; 3)\r\n            this.checked = false;\r\n    }, false);</code></pre>\r\n\r\n<p> </p>\r\n',	'yiaourt',	'2020-03-09 19:50:29');

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET utf8 NOT NULL,
  `pass` text CHARACTER SET utf8 NOT NULL,
  `mail` varchar(255) CHARACTER SET utf8 NOT NULL,
  `level` int(11) NOT NULL,
  `img_profil` text CHARACTER SET utf8 NOT NULL,
  `icone_img_profil` text CHARACTER SET utf8 NOT NULL,
  `ip` varchar(255) CHARACTER SET utf8 NOT NULL,
  `date_inscription` varchar(255) CHARACTER SET utf8 NOT NULL,
  `lastdat_TP` text CHARACTER SET utf8 NOT NULL,
  `lastdat_CM` text CHARACTER SET utf8 NOT NULL,
  `is_connect` int(2) NOT NULL,
  `panier` text COLLATE utf8_unicode_ci NOT NULL,
  `sexe` text COLLATE utf8_unicode_ci NOT NULL,
  `prenom` text COLLATE utf8_unicode_ci NOT NULL,
  `nom` text COLLATE utf8_unicode_ci NOT NULL,
  `adresse` text COLLATE utf8_unicode_ci NOT NULL,
  `ville` text COLLATE utf8_unicode_ci NOT NULL,
  `postal` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user` (`id`, `username`, `pass`, `mail`, `level`, `img_profil`, `icone_img_profil`, `ip`, `date_inscription`, `lastdat_TP`, `lastdat_CM`, `is_connect`, `panier`, `sexe`, `prenom`, `nom`, `adresse`, `ville`, `postal`) VALUES
(98,	'yiaourt',	'$2y$10$NHeTnws7roWCxrHYR35SBuSHlKn0YqsGoc7JFu6I98RRiJunT5pT6',	'yiaourt@gmail.com',	0,	'<img src=\'img/profil/98.jpg\' alt=\'Image de Profil\' align=\'middle\'>',	'<img id=\'icone\' src=\'img/profil/98.jpg\' alt=\'Image de Profil\' align=\'middle\'>',	'127.0.0.1',	'2002241216',	'a:8:{i:23;s:19:\"2020-03-11 22:00:04\";i:27;i:0;i:28;s:19:\"2020-04-26 18:57:18\";i:44;s:19:\"2020-03-10 18:05:00\";i:45;i:0;i:46;s:19:\"2020-03-16 16:08:54\";i:49;i:0;i:50;i:0;}',	'a:4:{i:12;s:19:\"2020-03-09 19:50:29\";i:169;s:19:\"2020-04-26 18:57:18\";i:123;s:19:\"2020-03-16 16:08:54\";i:167;N;}',	0,	'',	'homme',	'Loïc',	'Bois',	'140 Rue de Versailles',	'St Just d\'Ardèche',	'07700'),
(99,	'test',	'$2y$10$7CftKi.MnUm6kWfoUYbQ6O7/pUef6m5hhSQsSzxZ0yq3q1Mgc0j2q',	'test@test.test',	1,	'<img src=\'img/profil/99.jpg\' alt=\'Image de Profil\' align=\'middle\'>',	'<img id=\'icone\' src=\'img/profil/99.jpg\' alt=\'Image de Profil\' align=\'middle\'>',	'192.168.1.254',	'2002270658',	'a:5:{i:12;s:19:\"2020-03-11 22:00:04\";s:0:\"\";s:1:\"0\";i:169;s:19:\"2020-03-09 20:07:11\";i:167;s:19:\"2020-03-10 18:05:00\";i:123;s:19:\"2020-03-16 16:08:54\";}',	'a:4:{i:12;s:19:\"2020-03-09 19:50:29\";i:169;s:19:\"2020-03-09 20:07:11\";i:123;s:19:\"2020-03-16 16:08:54\";i:167;s:19:\"2020-03-10 18:05:00\";}',	0,	'',	'',	'',	'',	'',	'',	''),
(100,	'test2',	'$2y$10$CEmeDq7LgJwjmTHJkp4PueAX3EN/Mveksl9QXVX74L354K/DmPPta',	'test@test.test',	1,	'<img src=\'img/basic_img_profil.png\' alt=\'Image de Profil\' align=\'middle\'>',	'<img id=\'icone\' src=\'img/basic_img_profil.png\' alt=\'Image de Profil\' align=\'middle\'>',	'192.168.1.254',	'2003241145',	'a:8:{i:23;s:19:\"2020-03-11 22:00:04\";i:27;i:0;i:28;s:19:\"2020-03-09 20:07:11\";i:44;s:19:\"2020-03-10 18:05:00\";i:45;i:0;i:46;s:19:\"2020-03-16 16:08:54\";i:49;i:0;i:50;i:0;}',	'a:4:{i:12;s:19:\"2020-03-24 23:48:52\";i:169;s:19:\"2020-03-09 20:07:11\";i:123;s:19:\"2020-03-16 16:08:54\";i:167;s:19:\"2020-03-10 18:05:00\";}',	0,	'',	'',	'',	'',	'',	'',	''),
(101,	'user',	'$2y$10$v/VcCSqANXL2VvboiuXvf.pvH.MrzrBGN3d/Mxbj/21xqb43SgPY2',	'test@test.test',	1,	'<img src=\'img/profil/101.gif\' alt=\'Image de Profil\' align=\'middle\'>',	'<img id=\'icone\' src=\'img/profil/101.gif\' alt=\'Image de Profil\' align=\'middle\'>',	'192.168.1.254',	'2003241149',	'a:8:{i:23;s:19:\"2020-03-11 22:00:04\";i:27;i:0;i:28;s:19:\"2020-04-26 18:57:18\";i:44;s:19:\"2020-03-10 18:05:00\";i:45;i:0;i:46;s:19:\"2020-03-16 16:08:54\";i:49;i:0;i:50;i:0;}',	'a:4:{i:12;s:19:\"2020-03-09 19:50:29\";i:169;N;i:123;s:19:\"2020-03-16 16:08:54\";i:167;s:19:\"2020-03-10 18:05:00\";}',	0,	'',	'',	'',	'',	'',	'',	''),
(102,	'test42',	'$2y$10$U4qN4k5mbe27GLeoqXubAOA8l7Jh6B/GRL4wVPND/rdijTnXUexNm',	'test@test.test',	1,	'<img src=\'img/basic_img_profil.png\' alt=\'Image de Profil\' align=\'middle\'>',	'<img id=\'icone\' src=\'img/basic_img_profil.png\' alt=\'Image de Profil\' align=\'middle\'>',	'192.168.1.105',	'2005160250',	'a:8:{i:23;s:19:\"2020-03-11 22:00:04\";i:27;s:1:\"0\";i:28;s:19:\"2020-04-26 18:57:18\";i:44;s:19:\"2020-03-10 18:05:00\";i:45;s:1:\"0\";i:46;s:19:\"2020-03-16 16:08:54\";i:49;s:1:\"0\";i:50;s:1:\"0\";}',	'a:4:{i:12;s:19:\"2020-03-09 19:50:29\";i:169;s:19:\"2020-04-26 18:57:18\";i:123;s:19:\"2020-03-16 16:08:54\";i:167;s:19:\"2020-03-10 18:05:00\";}',	0,	'',	'',	'',	'',	'',	'',	''),
(103,	'user42',	'$2y$10$dx2lN5jTmqoX/Hu0DiRLzOgA08DU/u9AGhzfAln73bwL0WoE0V/5i',	'test@test.test',	1,	'<img src=\'img/basic_img_profil.png\' alt=\'Image de Profil\' align=\'middle\'>',	'<img id=\'icone\' src=\'img/basic_img_profil.png\' alt=\'Image de Profil\' align=\'middle\'>',	'192.168.1.105',	'2005160634',	'a:8:{i:23;s:19:\"2020-03-11 22:00:04\";i:27;s:1:\"0\";i:28;s:19:\"2020-04-26 18:57:18\";i:44;s:19:\"2020-03-10 18:05:00\";i:45;s:1:\"0\";i:46;s:19:\"2020-03-16 16:08:54\";i:49;s:1:\"0\";i:50;s:1:\"0\";}',	'a:4:{i:12;s:19:\"2020-03-09 19:50:29\";i:169;s:19:\"2020-04-26 18:57:18\";i:123;s:19:\"2020-03-16 16:08:54\";i:167;s:19:\"2020-03-10 18:05:00\";}',	0,	'a:2:{i:0;s:2:\"12\";i:1;s:2:\"12\";}',	'homme',	'Loïc',	'Bois',	'140 Rue de Versailles',	'St-Just d\'Ardèche',	'07700');

-- 2020-06-03 00:39:53
