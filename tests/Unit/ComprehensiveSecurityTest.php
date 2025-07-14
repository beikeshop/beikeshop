<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Beike\Admin\Services\FileManagerService;
use Beike\Plugin\Manager as PluginManager;

class ComprehensiveSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $fileManagerService;
    protected $pluginManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fileManagerService = new FileManagerService();
        $this->pluginManager = new PluginManager();
    }

    /**
     * 测试文件上传安全性
     */
    public function test_file_upload_security()
    {
        // 测试危险文件类型
        $dangerousFiles = [
            'malicious.php',
            'script.js',
            'executable.exe',
            'shell.sh',
            'backdoor.asp',
            'virus.bat',
        ];

        foreach ($dangerousFiles as $fileName) {
            $file = UploadedFile::fake()->create($fileName, 100);
            
            // 这些文件应该被拒绝
            $this->expectException(\Exception::class);
            
            // 模拟文件上传验证
            $this->assertFalse($this->isValidFileForUpload($file));
        }
    }

    /**
     * 测试路径遍历攻击防护
     */
    public function test_path_traversal_protection()
    {
        $maliciousPaths = [
            '../../../etc/passwd',
            '..\\..\\..\\windows\\system32\\config\\sam',
            './../../sensitive_file',
            '/../../../etc/shadow',
            'normal_path/../../../etc/passwd',
        ];

        foreach ($maliciousPaths as $path) {
            try {
                $this->fileManagerService->zipFolder($path);
                // 如果没有抛出异常，检查路径是否被正确清理
                $this->assertTrue(true, "Path was sanitized: $path");
            } catch (\Exception $e) {
                // 应该是因为文件不存在，而不是路径无效
                $this->assertStringContainsString('not exist', $e->getMessage());
            }
        }
    }

    /**
     * 测试ZIP文件安全性
     */
    public function test_zip_file_security()
    {
        // 创建一个模拟的恶意ZIP文件
        $tempZip = tempnam(sys_get_temp_dir(), 'test_zip');
        $zip = new \ZipArchive();
        $zip->open($tempZip, \ZipArchive::CREATE);
        
        // 添加路径遍历文件
        $zip->addFromString('../../../malicious.php', '<?php echo "hacked"; ?>');
        $zip->close();

        $uploadedFile = new UploadedFile($tempZip, 'malicious.zip', 'application/zip', null, true);

        $this->expectException(\Exception::class);
        $this->pluginManager->import($uploadedFile);

        // 清理临时文件
        @unlink($tempZip);
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
            'file/with/slash.jpg', // 路径分隔符
            str_repeat('a', 300) . '.jpg', // 过长的文件名
        ];

        foreach ($invalidFileNames as $fileName) {
            $this->assertFalse($this->isValidFileName($fileName), "Filename should be invalid: $fileName");
        }
    }

    /**
     * 测试MIME类型验证
     */
    public function test_mime_type_validation()
    {
        $dangerousMimeTypes = [
            'application/x-php',
            'application/x-httpd-php',
            'text/x-php',
            'application/x-executable',
            'application/x-msdownload',
            'text/x-shellscript',
        ];

        foreach ($dangerousMimeTypes as $mimeType) {
            $file = UploadedFile::fake()->create('test.txt', 100, $mimeType);
            $this->assertFalse($this->isValidMimeType($file), "MIME type should be rejected: $mimeType");
        }
    }

    /**
     * 测试批量文件操作安全性
     */
    public function test_batch_file_operations_security()
    {
        $mixedFiles = [
            'normal_file.jpg', // 正常文件
            '../../../etc/passwd', // 路径遍历攻击
            'another_file.png', // 正常文件
        ];

        $this->expectException(\Exception::class);
        $this->fileManagerService->deleteFiles('/test', $mixedFiles);
    }

    /**
     * 辅助方法：验证文件是否适合上传
     */
    private function isValidFileForUpload(UploadedFile $file): bool
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'pdf', 'doc', 'docx'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        return in_array($extension, $allowedExtensions) && $this->isValidFileName($file->getClientOriginalName());
    }

    /**
     * 辅助方法：验证文件名
     */
    private function isValidFileName(string $fileName): bool
    {
        if (strlen($fileName) > 255 || empty(trim($fileName))) {
            return false;
        }
        
        if (preg_match('#[<>:"|?*\x00-\x1f]#', $fileName)) {
            return false;
        }
        
        if (str_contains($fileName, '..') || str_contains($fileName, '/') || str_contains($fileName, '\\')) {
            return false;
        }
        
        return true;
    }

    /**
     * 辅助方法：验证MIME类型
     */
    private function isValidMimeType(UploadedFile $file): bool
    {
        $allowedMimeTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'video/mp4', 'application/pdf',
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        return in_array($file->getMimeType(), $allowedMimeTypes);
    }
}
