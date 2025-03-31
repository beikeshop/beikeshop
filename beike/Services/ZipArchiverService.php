<?php

namespace Beike\Services;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;
/**
 * 目录ZIP打包工具类
 */
class ZipArchiverService
{
    /**
     * @var string 源目录路径
     */
    private $sourcePath;

    /**
     * @var string 输出ZIP文件路径
     */
    private $outputPath;

    /**
     * @var array 要忽略的文件/目录模式
     */
    private $ignorePatterns = [];

    /**
     * 构造函数
     *
     * @param string $sourcePath 源目录路径
     * @param string $outputPath 输出ZIP文件路径
     */
    public function __construct($sourcePath, $outputPath)
    {
        $this->sourcePath = rtrim($sourcePath, '/');
        $this->outputPath = $outputPath;
    }

    /**
     * 设置要忽略的文件/目录模式
     *
     * @param array $patterns 忽略模式数组（支持通配符）
     * @return $this 支持链式调用
     */
    public function setIgnorePatterns(array $patterns)
    {
        $this->ignorePatterns = $patterns;

        return $this;
    }

    /**
     * 添加单个忽略模式
     *
     * @param string $pattern 忽略模式（支持通配符）
     * @return $this 支持链式调用
     */
    public function addIgnorePattern($pattern)
    {
        $this->ignorePatterns[] = $pattern;

        return $this;
    }

    /**
     * 创建ZIP归档
     *
     * @return bool 是否成功
     */
    public function create()
    {
        $zip = new ZipArchive();
        if ($zip->open($this->outputPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return false;
        }

        $dirIterator = new RecursiveDirectoryIterator($this->sourcePath);
        $iterator    = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $file) {
            if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                continue;
            }

            $filePath = $file->getPathname();
            // 计算相对路径时，确保路径格式一致
            $relativePath = substr($filePath, strlen($this->sourcePath) + 1);

            // 检查是否应该忽略此文件/目录
            if ($this->shouldIgnore($relativePath)) {
                continue;
            }

            if ($file->isDir()) {
                $zip->addEmptyDir($relativePath);
            } else {
                $zip->addFile($filePath, $relativePath);
            }
        }

        return $zip->close();
    }

    /**
     * 检查文件或目录是否应该被忽略
     *
     * @param string $path 相对路径
     * @return bool 是否应该忽略
     */
    private function shouldIgnore($path)
    {
        if (empty($this->ignorePatterns)) {
            return false;
        }

        // 首先检查路径是否与任何忽略模式匹配（无论是文件还是目录）
        foreach ($this->ignorePatterns as $pattern) {
            // 处理目录通配符模式（如 .git/* 匹配 .git 目录下所有内容）
            if (substr($pattern, -2) === '/*') {
                $dirPattern = substr($pattern, 0, -2);
                if (strpos($path, $dirPattern . '/') === 0) {
                    return true;
                }
            }
            // 检查是否为目录匹配（不依赖于is_dir，而是根据路径结构判断）
            else {
                // 1. 路径完全等于模式 (如 path="node_modules", pattern="node_modules")
                if ($path === $pattern) {
                    return true;
                }

                // 2. 路径在模式目录内 (如 path="node_modules/package/file.js", pattern="node_modules")
                // 检查路径是否以模式开头并紧跟斜杠
                if (strpos($path, $pattern . '/') === 0) {
                    return true;
                }

                // 3. 处理文件通配符匹配
                $regexPattern = '#^' . str_replace(['*', '?'], ['.*', '.'], $pattern) . '$#';
                if (preg_match($regexPattern, $path)) {
                    return true;
                }
            }
        }

        return false;
    }
}

//// 使用示例
//$projectPath = '/Applications/ServBay/www/beikeshop/plugins/Alibaba';
//$outputPath  = '/Users/kevin/Desktop/mydemo/fanyi/Alibaba.zip';
//
//$zipper = new ZipArchiverService($projectPath, $outputPath);
//$zipper->setIgnorePatterns([
//   '*.log',           // 忽略所有日志文件
//   '.git',          // 忽略.git目录下的所有内容
//   'node_modules',  // 忽略node_modules目录
//   'tmp',            // 忽略tmp目录
//   '.idea',
//]);
//
//// 也可以单独添加忽略模式
//$zipper->addIgnorePattern('*.tmp');
//
//if ($zipper->create()) {
//   echo '项目已成功打包';
//} else {
//   echo '打包失败';
//}
