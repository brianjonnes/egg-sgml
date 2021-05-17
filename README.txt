Please see https://killkilltheworld.org.za/xgml-ov-a for a demo of this package.

(a) Create [egg-sgml] directory within your project.
(b) Copy the latest stable api (4) and (1) from the directory containing this README into (a).
(c) Create [.htaccess] in your project containing at least:
RewriteEngine On
RewriteRule ^([^.]*)$ /egg-sgml/5/templates.php?t=%{REQUEST_URI} [QSA,END]
RewriteRule ^egg-sgml/[0-9]+ / [R,END]
RewriteBase /
(d) Create [templates_config.xml] containing this:
<main redirect-to-ssl sts-expire=333300 rootdoc=start extension=.xgml no-doctype/>
(f) We omit the natural function;
(g) We are looking for project contributors who don't think that everything is obvious. At present the only option is to contact the original author at https://killtheworld.co.za/contact-us if you cannot get things going with these notes.
(h) We cannot guarantee availability on platforms which weild arbitrary censorship powers.