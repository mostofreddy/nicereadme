<?php
/**
 * Pdf
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
* Clase encargada de renderizar el readme a pdf
 *
 * @category  NiceReadme
 * @package   NiceReadme
 * @author    Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class Pdf
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
        $mpdf = new \mPDF('c', 'A4', 10, 'Helvetica');

        //$mpdf->mirrorMargins = 1;
        $mpdf->SetDisplayMode('fullpage', 'single');

        //configuracion
        $mpdf->defaultheaderfontsize = 8;
        $mpdf->defaultheaderfontstyle = "I";
        $mpdf->defaultfooterfontsize = 8;
        $mpdf->defaultfooterfontstyle = "I";

        $mpdf->allow_charset_conversion = true;
        $mpdf->ignore_invalid_utf8 = true;

        //header
        $mpdf->setHeader($this->config['title']);
        //footer
        $date = $date = date("r");
        $footer = "Documentation is powered by nicereadme and generated on $date";
        $mpdf->SetFooter($footer.' | | {PAGENO} / {nb}');

        // $mpdf->h2toc = array('H2'=>0);
        $mpdf->h2bookmarks = array(
            "H1" => 0,
            "H2" => 1,
            "H3" => 2,
            "H4" => 3,
            "H5" => 4,
            "H6" => 5
        );

        //contenido
        $mpdf->WriteHTML($this->extraHtml().$content);

        //metadata
        $mpdf->SetTitle($this->config['title']);
        $mpdf->SetAuthor($this->config['copyright']);

        //output
        $mpdf->Output(realpath($this->config['output'])."/file.pdf");
    }

    /**
     * extraHtml
     *
     * @access protected
     *
     * @return mixed Value.
     */
    protected function extraHtml()
    {
        $title = $this->config['title'];
        $html = <<<TXT
<br/><br/><br/>
<div style="text-align:center;font-weight:bold;font-size:30px;">$title</div>
<pagebreak />
TXT;
        //<indexinsert cols="2" offset="5" usedivletters="on" div-font-size="15" gap="5" font="Trebuchet" div-font="sans-serif" links="on" />
        //<pagebreak />
        return $html;

    }
}
