**Deprecado**

-- ---

NiceReadme permite crear hermosa documentación en html y pdf basandose en archivos Markdown

Versión
-------

__v0.4.2__ stable

Features
--------

* Exportación a HTML
* Exportación a PDF

Licencia
-------
Software bajo licencia [MIT](http://opensource.org/licenses/mit-license.php)

Instalación
-----------

### Requerimientos

- PHP => 5.3
- [Composer](http://getcomposer.org)

### github

    cd /var/www
    git clone https://github.com/mostofreddy/nicereadme.git
    cd nicereadme
    composer install

### composer

    "require": {
        "php": ">=5.3.0",
        "mostofreddy/nicereadme": "0.*",
    }

Ejemplo
-------

    php nicereadme --target=/var/www/nicereadme/README.md --output=/var/www/nicereadme/doc --exportto=pdf --title="Documentation of NiceReadme" --filename="nicereadme" --copyright="Mostofreddy"

Demo
----

- [HTML Export](http://mostofreddy.github.io/nicereadme/)
- [PDF Export](http://mostofreddy.github.io/nicereadme/download/nicereadme_v040.pdf)

Roadmap & issues
----------------

[Roadmap & issues](https://github.com/mostofreddy/nicereadme/issues/milestones)
