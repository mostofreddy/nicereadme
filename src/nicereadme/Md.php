<?php
/**
 * Md
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
use \Michelf\Markdown;
/**
 * Wrapper de las clases Markdown.
 *
 * @category  NiceReadme
 * @package   NiceReadme
 * @author    Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class Md
{
    /**
     * Renderiza un archivo de formato md a html
     *
     * @param string $file path del archivo a renderizar
     *
     * @return string
     */
    public static function render($file)
    {
        $text = file_get_contents($file);
        return Markdown::defaultTransform($text);
    }
}
