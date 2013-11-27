<?php
/**
 * Template
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
use Sunra\PhpSimple\HtmlDomParser;
/**
 * Clase encargada de renderizar los templates
 *
 * @category  NiceReadme
 * @package   NiceReadme
 * @author    Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class Template
{
    protected $content = '';
    protected $pathtotemplate = '';
    protected $pathToOutput = '';
    protected $title = '';
    protected $copyright = '';
    protected $forkme = '';
    protected $index = false;
    /**
     * Setea si se crea un indice o no
     *
     * @param bool $index true/false
     *
     * @return \phpmdtohtml\Views
     */
    public function setIndex($index)
    {
        $this->index = $index;
        return $this;
    }
    /**
     * Setea el path donde se encuentran los templates
     *
     * @param string $path path absoluto a los templates
     *
     * @return \phpmdtohtml\Views
     */
    public function setPathToOutput($path)
    {
        $this->pathToOutput = realpath($path)."/";
        return $this;
    }
    /**
     * Setea el path donde se encuentran los templates
     *
     * @param string $path path absoluto a los templates
     *
     * @return \phpmdtohtml\Views
     */
    public function setPathToTemplate($path)
    {
        $this->pathtotemplate = realpath($path)."/";
        return $this;
    }
    /**
     * Setea el titulo
     *
     * @param string $title titulo
     *
     * @return \phpmdtohtml\Views
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    /**
     * Setea el contenido
     *
     * @param string $content contenido
     *
     * @return \phpmdtohtml\Views
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
    /**
     * setCopyright
     *
     * @param string $copyright copyright
     *
     * @access public
     * @return \phpmdtohtml\Views
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;
        return $this;
    }
    /**
     * setForkme
     *
     * @param string $fork fork
     *
     * @access public
     * @return \phpmdtohtml\Views
     */
    public function setForkme($fork)
    {
        $this->forkme = $fork;
        return $this;
    }
    /**
     * Renderiza la salida
     *
     * @return bool
     */
    public function render()
    {
        // Copia los archivos del template
        \nicereadme\Files::transfer($this->pathtotemplate, $this->pathToOutput);
        // Instancia Twig
        $twig = $this->getFactoryTwig();
        // Genera el indice si el flag index esta activo
        $index = $this->renderIndex();
        // Genera el file
        $this->renderFile(
            $twig,
            array(
                'doc' => $this->content.$this->footer(),
                'title' => $this->title,
                'index' => $index
            )
        );
    }
    /**
     * Renderiza el indice
     *
     * @return void
     */
    protected function renderIndex()
    {
        $html = HtmlDomParser::str_get_html($this->content, true, true, 'UTF-8', false);
        $index = array();

        if ($this->index === true) {
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
     * Renderiza el index
     *
     * @param object $twig objeto twig
     * @param array  $data array con las variables a pasar a twig
     *
     * @return string
     */
    protected function renderFile($twig, array $data)
    {
        $data = $twig->render(
            file_get_contents($this->pathtotemplate."index.html"),
            $data
        );
        file_put_contents($this->pathToOutput."index.html", html_entity_decode($data));
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
        $copy = $this->copyright;
        $footer = <<<FOOTER
<div id="footerphpmdtohtml">
    <p class="">
        Documentation is powered by <a href="https://github.com/mostofreddy/phpmdtohtml">phpmdtohtml</a>
        and generated on $date. <br/>
        © Copyright $year - $copy
    </p>
</div>
FOOTER;
        return $footer.$this->forkme();
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
        if ($this->forkme != '') {
            $link = $this->forkme;
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
}
