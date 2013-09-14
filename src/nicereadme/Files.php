<?php
/**
 * Files
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
 * Clase de ayuda para copiar todos los archivos del template a la carpeta de destino
 *
 * @category  NiceReadme
 * @package   NiceReadme
 * @author    Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class Files
{
    /**
     * Transfiere el contenido de una carpeta a otra.
     * Si el parametro $delete es igual a true entonces borra el contenido de la carpeta destino
     * antes de copiar los archivos
     *
     * @param string $origen  ruta origen del template
     * @param string $destino ruta destinto
     * @param bool   $delete  indica si borra todos los archivos de destino antes de copiar
     *                        default: true
     *
     * @return void
     */
    public static function transfer($origen, $destino, $delete=true)
    {
        if ($delete) {
            \nicereadme\Files::delete($destino);
        }
        \nicereadme\Files::copy($origen, $destino);
    }
    /**
     * Copia todos los archivos de una carpeta a otra
     * Primero borra todo lo que haya en el destino
     *
     * @param string $origen  ruta origen del template
     * @param string $destino ruta destinto
     *
     * @return void
     */
    public static function copy($origen, $destino)
    {

        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($origen));
        while ($it->valid()) {
            if (!$it->isDot()) {
                if ($it->getSubPath() != '') {
                    if (!is_dir($destino.$it->getSubPath())) {
                        mkdir($destino.$it->getSubPath(), 0777, true);
                    }
                }
                if (!is_dir($it->key())) {
                    copy($it->key(), $destino.$it->getSubPathName());
                }
            }
            $it->next();
        }
    }
    /**
     * Borrar todos los archivos y directorios de un directorio
     *
     * @param string $destino path
     *
     * @return void
     */
    public static function delete($destino)
    {
        $it = new \DirectoryIterator($destino);
        while ($it->valid()) {
            if (!$it->isDot()) {
                if ($it->isDir()) {
                    \nicereadme\Files::delete($it->getPathname());
                    rmdir($it->getPathname());
                } else if ($it->isFile()) {
                    unlink($it->getPathname());
                }

            }
            $it->next();
        }
    }
}
