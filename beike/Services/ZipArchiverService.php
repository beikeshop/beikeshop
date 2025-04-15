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

        // 获取源目录的基本名称作为ZIP包内的顶层目录名
        $rootDirectory = basename($this->sourcePath);

        $dirIterator = new RecursiveDirectoryIterator($this->sourcePath);
        $iterator    = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $file) {
            if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                continue;
            }

            $filePath = $file->getPathname();
            // 计算原始相对路径
            $originalRelativePath = substr($filePath, strlen($this->sourcePath) + 1);

            // 检查是否应该忽略此文件/目录 (使用原始相对路径)
            if ($this->shouldIgnore($originalRelativePath)) {
                continue;
            }

            // 构建ZIP包内的完整路径（包含顶层目录）
            // 确保即使 originalRelativePath 为空（即源目录本身），也能正确处理
            $zipPath = $originalRelativePath ? $rootDirectory . '/' . $originalRelativePath : $rootDirectory;
            // 确保路径分隔符统一为 /
            $zipPath = str_replace('\\', '/', $zipPath); // 修正：处理反斜杠

            if ($file->isDir()) {
                // 添加空目录，注意要使用包含顶层目录的路径
                // 确保目录路径以 / 结尾，如果原始路径不为空
                if ($originalRelativePath) {
                    $zipPath = rtrim($zipPath, '/') . '/';
                }
                $zip->addEmptyDir($zipPath);
            } else {
                // 添加文件，注意要使用包含顶层目录的路径
                $zip->addFile($filePath, $zipPath);
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

        foreach ($this->ignorePatterns as $pattern) {
            if (str_starts_with($path, $pattern)) {
                return true;
            }

            $regexPattern = '#^' . str_replace(['*', '?'], ['.*', '.'], $pattern) . '$#';
            if (preg_match($regexPattern, $path)) {
                return true;
            }
        }

        return false;
    }
}
