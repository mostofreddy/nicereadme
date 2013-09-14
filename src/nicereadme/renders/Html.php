<?php
/**
 * Html
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
namespace nicereadme\renders;
use Sunra\PhpSimple\HtmlDomParser;
/**
 * Clase encargada de renderizar el readme a html
 *
 * @category  NiceReadme
 * @package   NiceReadme
 * @author    Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class Html
{
    protected $config = array();
    protected $content = '';
    /**
     * Setea la configuracion del objeto
     *
     * @param array $config array de configuracion
     *
     * @access public
     * @return nicereadme\renders\Html
     */
    public function config(array $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Exporta el contenido del archivo readme a un formato html
     *
     * @param string $content contenido a renderizar
     *
     * @access public
     * @return void
     */
    public function render($content)
    {
        $template = realpath(__DIR__."/../")."/templates/".$this->config['template']."/";

        $this->content = $content;
        // Copia los archivos del template
        \nicereadme\Files::transfer($template, $this->config['output']);
        // Instancia Twig
        $twig = $this->getFactoryTwig();
        // Genera el indice si el flag index esta activo
        $index = $this->renderIndex();
        // Genera el file
        $this->renderFile(
            $twig,
            array(
                'doc' => $this->content.$this->extraHtml(),
                'title' => $this->config['title'],
                'index' => $index
            )
        );
    }
    /**
     * Factory de Twig
     *
     * @return \Twig_Environment
     */
    protected function getFactoryTwig()
    {
        \Twig_Autoloader::register();
        $loader = new \Twig_Loader_String();
        return new \Twig_Environment($loader);
    }
    /**
     * Renderiza el indice
     *
     * @return void
     */
    protected function renderIndex()
    {
        $index = array();

        if (array_key_exists('index', $this->config) && $this->config['index'] === true) {
            $html = \HtmlDomParser::str_get_html($this->content, true, true, 'UTF-8', false);
            $elems = $html->find("h1, h2, h3, h4, h5, h6");

            foreach ($elems as $el) {
                $ancleName = preg_replace('/[^a-z0-9]/i', '', strtolower(trim($el->innertext)));
                $hxContent = trim($el->innertext);
                $el->innertext .= "<a name=".$ancleName."></a>";

                $spaces = (intval($el->tag[1]) * 3) - 3;
                $spaces = str_repeat("&nbsp;", $spaces);
                $index[] = "<a href=#".trim($ancleName).">".$spaces.$hxContent."</a>";
            }

            $this->content = $html->save();
        }

        return $index;
    }

    /**
     * Agrega extra html al template
     *
     * @access protected
     * @return string
     */
    protected function extraHtml()
    {
        return $this->footer().$this->forkme();
    }
    /**
     * extraFooter
     *
     * @access protected
     *
     * @return mixed Value.
     */
    protected function footer()
    {
        $date = date("r");
        $year = date("Y");
        $copy = (array_key_exists('copyright', $this->config))?'- '.$this->config['copyright']:'';
        $footer = <<<FOOTER
<div id="footerphpmdtohtml">
    <p class="">
        Documentation is powered by <a href="https://github.com/mostofreddy/nicereadme">nicereadme</a>
        and generated on $date. <br/>
        © Copyright $year $copy
    </p>
</div>
FOOTER;
        return $footer;
    }
    /**
     * forkme
     *
     * @access protected
     *
     * @return mixed Value.
     */
    protected function forkme()
    {
        $fork = '';
        $link = (array_key_exists('forkme', $this->config))?$this->config['forkme']:'';
        if ($link != '') {
            $fork = <<<FORK
<div class="forkme">
    <a href="$link" >
        <img style="position: absolute; top: 0; right: 0; border: 0;"
            src="https://s3.amazonaws.com/github/ribbons/forkme_right_red_aa0000.png" alt="Fork me on GitHub"
        />
    </a>
</div>
FORK;
        }
        return $fork;
    }
    /**
     * Renderiza el index
     *
     * @param object $twig objeto twig
     * @param array  $data array con las variables a pasar a twig
     *
     * @return string
     */
    protected function renderFile($twig, array $data)
    {
        $template = realpath(__DIR__."/../")."/templates/".$this->config['template']."/";

        $data = $twig->render(
            file_get_contents($template."index.html"),
            $data
        );
        file_put_contents($this->config['output']."index.html", html_entity_decode($data));
    }
}
