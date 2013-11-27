<?php
/**
 * Application
 *
 * PHP version 5.3
 *
 * Copyright (c) 2013 mostofreddy <mostofreddy@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category  NiceReadme
 * @package   NiceReadme
 * @author    Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
namespace nicereadme;
/**
 * Clase base para generar un HTML formateado desde un archivo Markdown
 *
 * @category  NiceReadme
 * @package   NiceReadme
 * @author    Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class Application extends \cliptools\Cli
{
    const VERSION="0.4.0";

    protected $deps = array(
        'md' => '\nicereadme\Md',
        'renders' => array(
            'pdf' => '\nicereadme\renders\Pdf',
            'html' => '\nicereadme\renders\Html'
        )
    );
    /**
     * Devuelve el texto de ayuda
     *
     * @return string
     */
    protected function help()
    {
        $this->head();
        $this->writer->nl();
        $txt = <<<TXT
Usage:
 php bin/phpmd2html.php [hi] --target=/paht/to/file.md --output=/path/to/output [--index] [--title="title"] [--template=simple]


TXT;
        $txt .= $this->opts->usage();
        return $txt;
    }
    /**
     * Devuelve la version actual de la libreria
     *
     * @return string
     */
    protected function version()
    {
        $this->head();
        return '';
    }
    /**
     * Bootstrap
     *
     * @return void
     */
    public function run()
    {
        parent::run();
        //muestra la cabecera
        $this->head();
        $this->writer->nl();

        // Valida los datos de entrada
        if ($this->validations() == false) {
            $this->writer->nl();
            exit;
        }

        //exporta la data
        $this->export();

        // Done!
        $this->writer->nl()
            ->write(" ")->colorize(" DONE! ", "white", "green")
            // ->nl()
            ->write(" Files were stored in: ")
            ->colorize($this->opts->get('output'), "white")
            ->nl();
    }

    /**
     * Exporta la data
     *
     * @access protected
     * @return void
     */
    protected function export()
    {
        // Render del archivo MD a HTML sin formato
        $this->writer->write(" > rendering md")->nl();
        $md = $this->deps['md'];
        $content = $md::render($this->opts->get("target"));

        $this->writer->write(" > export to ".$this->opts->get('exportto'))->nl();

        $class = $this->deps['renders'][$this->opts->get('exportto')];

        $render = new $class();

        $render->config($this->opts->get());

        $render->render($content);
    }

    /**
     * Imprime la cabecera
     *
     * @return void
     */
    protected function head()
    {
        $this->writer->colorize("NiceReadme ".static::VERSION." by Mostofreddy", "yellow")->nl();
    }
    /**
     * Valida los valores de entrada
     *
     * @return bool
     */
    protected function validations()
    {
        //valida el target
        if ($this->opts->get('target') == null || $this->opts->get('target') == '') {
            $this->writer->colorize(" Error ", "white", "red")
                ->write(" No se indico el target ");
            return false;
        }
        if (!is_readable($this->opts->get('target'))) {
            $this->writer->colorize(" Error ", "white", "red")
                ->write(" El target no se puede leer ");
            return false;
        }
        //valida output
        if ($this->opts->get('output') == null || $this->opts->get('output') == '') {
            $this->writer->colorize(" Error ", "white", "red")
                ->write(" No se indico el output ");
            return false;
        } else {
            $output = $this->opts->get('output');
            if ($output[strlen($output) - 1] != '/') {
                $this->opts->set('output', $output.'/');
            }
        }

        if (!is_writable($this->opts->get('output'))) {
            $this->writer->colorize(" Error ", "white", "red")
                ->write(" No se puede escribir en el output ");
            return false;
        }
        //valida el template
        $template = __DIR__."/templates/".$this->opts->get("template");
        if (!is_dir($template)) {
            $this->writer->colorize(" Error ", "white", "red")
                ->write(" No definio un template válido ");
            return false;
        }
        if (!is_readable($template)) {
            $this->writer->colorize(" Error ", "white", "red")
                ->write(
                    " El directorio del template '".$this->opts->get("template")."'' no tiene permisos de lectura "
                );
            return false;
        }
        //valida el tipo de exportacion
        if (!array_key_exists($this->opts->get('exportto'), $this->deps['renders'])) {
            $this->writer->colorize(" Error ", "white", "red")
                ->write(" El formato de exportación es incorrecto. Las opciones válidas son: pdf, html ");
            return false;
        }

        if ($this->opts->get('exportto') == 'pdf') {
            $aux = $this->opts->get('filename');
            if (empty($aux)) {
                $this->writer->colorize(" Error ", "white", "red")
                    ->write(" No definio el nombre del archivo pdf ");
                return false;
            }
        }
        return true;
    }
}
