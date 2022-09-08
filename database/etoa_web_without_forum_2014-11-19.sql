SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

SET
    time_zone = "+00:00";

CREATE TABLE `config` (
    `config_id` int(11) NOT NULL,
    `config_name` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
    `config_value` text COLLATE latin1_general_ci NOT NULL
) ENGINE = MyISAM DEFAULT CHARSET = latin1 COLLATE = latin1_general_ci;

INSERT INTO
    `config` (`config_id`, `config_name`, `config_value`)
VALUES
    (55, 'server_notice', ''),
    (130, 'buttons', ''),
    (
        59,
        'indexjscript',
        '<!-- Global site tag (gtag.js) - Google Analytics -->\r\n<script async src=\"https://www.googletagmanager.com/gtag/js?id=UA-4499873-4\"></script>\r\n<script>\r\n  window.dataLayer = window.dataLayer || [];\r\n  function gtag(){dataLayer.push(arguments);}\r\n  gtag(\'js\', new Date());\r\n\r\n  gtag(\'config\', \'UA-4499873-4\');\r\n</script>\r\n'
    ),
    (188, 'adds', ''),
    (220, 'news_board', '6'),
    (222, 'rules_thread', '9904'),
    (223, 'ts_link', 'https://discord.gg/w6pKn9c'),
    (227, 'status_board', '103'),
    (64, 'footer_js', ''),
    (221, 'rules_board', '10'),
    (228, 'support_board', '21');

CREATE TABLE IF NOT EXISTS `rounds` (
    `round_id` int(11) NOT NULL AUTO_INCREMENT,
    `round_name` varchar(255) NOT NULL,
    `round_url` varchar(255) NOT NULL,
    `round_active` int(1) NOT NULL DEFAULT '1',
    `round_startdate` int(30) NOT NULL DEFAULT '0',
    `round_status_online` int(1) unsigned NOT NULL DEFAULT '1',
    `round_status_ping` int(10) unsigned NOT NULL DEFAULT '0',
    `round_status_checked` int(10) unsigned NOT NULL DEFAULT '0',
    `round_status_changed` int(10) unsigned NOT NULL DEFAULT '0',
    `round_status_ip` varchar(15) NOT NULL DEFAULT '127.0.0.1',
    `round_status_port` int(5) unsigned NOT NULL DEFAULT '80',
    `round_status_error` varchar(250) NOT NULL DEFAULT ' ',
    `round_alturl` varchar(250) NOT NULL DEFAULT '',
    PRIMARY KEY (`round_id`),
    KEY `round_name` (`round_name`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 AUTO_INCREMENT = 54;

CREATE TABLE IF NOT EXISTS `texts` (
    `text_id` int(11) NOT NULL AUTO_INCREMENT,
    `text_keyword` varchar(255) NOT NULL,
    `text_text` text NOT NULL,
    `text_last_changes` int(50) NOT NULL DEFAULT '0',
    `text_name` varchar(50) NOT NULL,
    `text_author_id` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`text_id`),
    UNIQUE KEY `text_keyword` (`text_keyword`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 AUTO_INCREMENT = 11;
