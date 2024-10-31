=== RUSH Seo Tags ===
Contributors: rushagency 
Donate link: https://rush-agency.ru/
Tags: SEO, tags,meta title, h1,descriptiop, meta
Requires PHP: 5.6
Requires at least: 4.0
Tested up to: 4.9.4
Stable tag: 1.0


== Description ==

**Плагин предназначен для изменения title, description и h1 страниц сайта.**
Работает на основе проверки URL-ов страниц.Для работы с h1 требуется использовать особые шорт-коды.	
Описание полей:
	1. url - По этому полю сравнивается текущая страница, если она совпадает, то происходит подстановка полей, перечисленных ниже, заносится без https://site.ru/ и без последнего слеша "/"
	2. title - Данные подставляются в тег <title>
	3. description - Данные подставляются в meta тег <meta name="description">
	4. h1 - для того, чтобы вывести данное поле, в нужном месте Вашего шаблона, необходимо использовать do_action( 'set_caption', '<tag>', '</tag>' ), где  tag -  h1, p, div ,span и т.д.


== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

<p>view this posts if you want more details about installing plugins: <a href="http://www.wpfasthelp.com/how-to-install-wordpress-plugins.htm">
http://www.wpfasthelp.com/how-to-install-wordpress-plugins.htm</a></p>

== Frequently Asked Questions ==

= 1. Зачем менять мета-теги =

Для высокого ранжирования сайта вашего интернет-сайта в поисковой выдаче. 

= 2. Что такое meta-теги =

meta-теги — это элементы (инструкции) разметки HTML-страниц, предназначенные для хранения и передачи данных предназначенной для браузеров и поисковых систем. 
Как правило, они размещаются в блоке <head> HTML-документа

= 3. Пакетное добавление данных = 

Пакетное добавление позволяет импортировать meta-теги из файла в БД

== Screenshots ==
1. screenshot-1 
2. screenshot-2    

== Changelog ==

= 1.0 =
* First version 

## Upgrade Notice ##

= 1.0 =
* First version 