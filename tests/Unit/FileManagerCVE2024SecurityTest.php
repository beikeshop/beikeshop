<?php

namespace Tests\Unit;

use Tests\TestCase;
use Beike\Admin\Services\FileManagerService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileManagerCVE2024SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $fileManagerService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fileManagerService = new FileManagerService();
    }

    /**
     * 测试 CVE-2024-8164: 无限制文件上传漏洞防护
     */
    public function test_cve_2024_8164_unrestricted_upload_protection()
    {
        // 测试危险的文件扩展名
        $dangerousFileNames = [
            'malicious.php',
            'script.js',
            'executable.exe',
            'shell.sh',
            'config.conf',
            'backdoor.asp',
            'virus.bat',
            'trojan.scr',
        ];

        foreach ($dangerousFileNames as $fileName) {
            try {
                $this->fileManagerService->updateName('/test/oldfile.jpg', $fileName);
                $this->fail("Expected exception for dangerous filename: $fileName");
            } catch (\Exception $e) {
                $this->assertStringContainsString('Invalid', $e->getMessage());
            }
        }
    }

    /**
     * 测试 CVE-2024-8163: 路径遍历漏洞防护
     */
    public function test_cve_2024_8163_path_traversal_protection()
    {
        // 测试路径遍历攻击模式
        $maliciousFiles = [
            '../../../etc/passwd',
            '..\\..\\..\\windows\\system32\\config\\sam',
            './../../sensitive_file',
            '/../../../etc/shadow',
            'normal_file/../../../etc/passwd',
        ];

        foreach ($maliciousFiles as $maliciousFile) {
            try {
                $this->fileManagerService->deleteFiles('/test', [$maliciousFile]);
                $this->fail("Expected exception for malicious file: $maliciousFile");
            } catch (\Exception $e) {
                $this->assertStringContainsString('Invalid', $e->getMessage());
            }
        }
    }

    /**
     * 测试文件名验证
     */
    public function test_filename_validation()
    {
        $invalidFileNames = [
            '', // 空文件名
            '   ', // 只有空格
            'file<script>alert(1)</script>.jpg', // XSS 尝试
            'file|dangerous.jpg', // 管道字符
            'file"quote.jpg', // 引号
            'file?query.jpg', // 问号
            'file*wildcard.jpg', // 通配符
            'CON.jpg', // Windows 保留名
            'PRN.txt', // Windows 保留名
            'file/with/slash.jpg', // 路径分隔符
            'file\\with\\backslash.jpg', // 反斜杠
            str_repeat('a', 300) . '.jpg', // 过长的文件名
        ];

        foreach ($invalidFileNames as $fileName) {
            try {
                $this->fileManagerService->updateName('/test/oldfile.jpg', $fileName);
                $this->fail("Expected exception for filename: $fileName");
            } catch (\Exception $e) {
                $this->assertStringContainsString('Invalid', $e->getMessage());
            }
        }
    }

    /**
     * 测试有效文件名应该被允许
     */
    public function test_valid_filenames_allowed()
    {
        $validFileNames = [
            'normal_file.jpg',
            'image-2024.png',
            'document_v1.pdf',
            'video.mp4',
            'presentation.pptx',
            'data.xlsx',
        ];

        foreach ($validFileNames as $fileName) {
            try {
                $this->fileManagerService->updateName('/test/oldfile.jpg', $fileName);
            } catch (\Exception $e) {
                // 如果失败，应该是因为文件不存在，而不是因为文件名无效
                $this->assertStringNotContainsString('Invalid filename', $e->getMessage());
                $this->assertStringNotContainsString('Invalid path', $e->getMessage());
            }
        }
    }

    /**
     * 测试批量删除文件的安全性
     */
    public function test_batch_file_deletion_security()
    {
        $mixedFiles = [
            'normal_file.jpg', // 正常文件
            '../../../etc/passwd', // 路径遍历攻击
            'another_file.png', // 正常文件
        ];

        try {
            $this->fileManagerService->deleteFiles('/test', $mixedFiles);
            $this->fail("Expected exception for mixed files with path traversal");
        } catch (\Exception $e) {
            $this->assertStringContainsString('Invalid', $e->getMessage());
        }
    }

    /**
     * 测试空字节注入防护
     */
    public function test_null_byte_injection_protection()
    {
        $nullByteFiles = [
            "file.jpg\x00.php",
            "image.png\x00.exe",
            "document.pdf\x00.sh",
        ];

        foreach ($nullByteFiles as $fileName) {
            try {
                $this->fileManagerService->updateName('/test/oldfile.jpg', $fileName);
                $this->fail("Expected exception for null byte injection: $fileName");
            } catch (\Exception $e) {
                $this->assertStringContainsString('Invalid', $e->getMessage());
            }
        }
    }
}
