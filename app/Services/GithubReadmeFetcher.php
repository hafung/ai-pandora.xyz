<?php

namespace App\Services;

use GuzzleHttp\Client;
use InvalidArgumentException;

class GithubReadmeFetcher {

    private string $owner;
    private string $repo;
    private string $branch;
    private ?string $error = null;
    private string $repoUrl;
    private array $readmeVariants = ['README.md', 'readme.md', 'Readme.md'];
    private Client $client;

    /**
     * 从 GitHub URL 或仓库信息初始化
     *
     * @param string $input GitHub URL 或 'owner/repo' 格式
     * @param string $branch 分支名称（可选）
     * @return self
     */
    public static function create(string $input, string $branch = 'main'): self {
        return new self($input, $branch);
    }

    public function __construct(string $input, string $branch = 'main') {
        $this->parseInput($input);
        $this->branch = $branch;
        $this->repoUrl = $input;
        $this->initializeClient();
    }

    /**
     * 初始化 Guzzle HTTP 客户端
     */
    private function initializeClient(): void {
        $this->client = new Client([
            'timeout' => 60,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 Edg/132.0.0.0',
            ],
            'http_errors' => false, // 不自动抛出异常，让我们可以处理不同的状态码
        ]);
    }

    /**
     * 解析输入的 GitHub URL 或仓库信息
     *
     * @param string $input
     * @throws InvalidArgumentException
     */
    private function parseInput(string $input): void {
        // 清理输入
        $input = trim($input);

        // 移除 .git 后缀（如果存在）
        $input = preg_replace('/\.git$/', '', $input);

        // 处理完整的 GitHub URL
        if (str_contains($input, 'github.com')) {
            $parsed = parse_url($input);
            $path = trim($parsed['path'] ?? '', '/');

            // 如果包含 tree/branch，处理并提取分支信息
            if (str_contains($path, '/tree/')) {
                $parts = explode('/tree/', $path);
                $path = $parts[0];
                $this->branch = $parts[1] ?? $this->branch;
            }

            $parts = explode('/', $path);

            if (count($parts) >= 2) {
                $this->owner = $parts[0];
                $this->repo = $parts[1];
                return;
            }
        }

        // 处理 'owner/repo' 格式
        $parts = explode('/', $input);
        if (count($parts) === 2) {
            $this->owner = $parts[0];
            $this->repo = $parts[1];
            return;
        }

        throw new InvalidArgumentException('Invalid GitHub repository format: ' . $input);
    }

    /**
     * 获取 README 内容
     *
     * @return string|null
     */
    public function fetch(): ?string {
        // 尝试在主分支找到任意README变体
        $content = $this->tryFetchReadmeFromBranch($this->branch);
        if ($content !== null) {
            return $content;
        }

        // 如果主分支失败，尝试其他常用分支
        $fallbackBranches = ['master', 'main', 'develop'];
        foreach ($fallbackBranches as $branch) {
            if ($branch === $this->branch) continue;

            $content = $this->tryFetchReadmeFromBranch($branch);
            if ($content !== null) {
                return $content;
            }
        }

        $this->error = "README not found in any common branch with any common filename";
        return null;
    }

    /**
     * 尝试从指定分支获取任意README变体
     *
     * @param string $branch
     * @return string|null
     */
    private function tryFetchReadmeFromBranch(string $branch): ?string {
        foreach ($this->readmeVariants as $readmeFile) {
            $content = $this->tryFetchFile($branch, $readmeFile);
            if ($content !== null) {
                return $content;
            }
        }

        return null;
    }

    /**
     * 尝试获取特定文件
     *
     * @param string $branch
     * @param string $filename
     * @return string|null
     */
    private function tryFetchFile(string $branch, string $filename): ?string {
        $url = "https://raw.githubusercontent.com/{$this->owner}/{$this->repo}/{$branch}/{$filename}";

        try {
            $response = $this->client->get($url);

            if ($response->getStatusCode() === 200) {
                return (string)$response->getBody();
            }

            // 记录最后一次尝试的错误状态码
            $this->error = "HTTP Status: " . $response->getStatusCode() . " for file {$filename} on branch {$branch}";

        } catch (\Exception $e) {
            $this->error = "Exception when fetching {$filename} from {$branch}: " . $e->getMessage();
        }

        return null;
    }

    /**
     * 获取最后的错误信息
     *
     * @return string|null
     */
    public function getError(): ?string {
        return $this->error;
    }

    /**
     * 获取仓库所有者
     *
     * @return string
     */
    public function getOwner(): string {
        return $this->owner;
    }

    /**
     * 获取仓库名称
     *
     * @return string
     */
    public function getRepo(): string {
        return $this->repo;
    }

    /**
     * 获取仓库URL
     *
     * @return string
     */
    public function getRepoUrl(): string {
        return $this->repoUrl;
    }

    /**
     * 获取当前使用的分支
     *
     * @return string
     */
    public function getBranch(): string {
        return $this->branch;
    }

    /**
     * 设置自定义的README文件名变体
     *
     * @param array $variants
     * @return self
     */
    public function setReadmeVariants(array $variants): self {
        if (!empty($variants)) {
            $this->readmeVariants = $variants;
        }
        return $this;
    }

    /**
     * 添加README文件名变体
     *
     * @param string $variant
     * @return self
     */
    public function addReadmeVariant(string $variant): self {
        if (!in_array($variant, $this->readmeVariants)) {
            $this->readmeVariants[] = $variant;
        }
        return $this;
    }
}
