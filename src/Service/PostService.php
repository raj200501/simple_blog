<?php

declare(strict_types=1);

namespace SimpleBlog\Service;

use SimpleBlog\Repository\PostRepository;

final class PostService
{
    public function __construct(private readonly PostRepository $repository)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        return $this->repository->all();
    }

    /**
     * @return array{success: bool, errors: array<string, string>, id?: int}
     */
    public function create(string $title, string $content): array
    {
        $errors = $this->validate($title, $content);
        if ($errors !== []) {
            return ['success' => false, 'errors' => $errors];
        }

        $id = $this->repository->create($title, $content);

        return ['success' => true, 'errors' => [], 'id' => $id];
    }

    /**
     * @return array<string, mixed>
     */
    public function find(int $id): array
    {
        return $this->repository->find($id);
    }

    /**
     * @return array{success: bool, errors: array<string, string>}
     */
    public function update(int $id, string $title, string $content): array
    {
        $errors = $this->validate($title, $content);
        if ($errors !== []) {
            return ['success' => false, 'errors' => $errors];
        }

        $this->repository->update($id, $title, $content);

        return ['success' => true, 'errors' => []];
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }

    /**
     * @return array<string, string>
     */
    private function validate(string $title, string $content): array
    {
        $errors = [];

        $title = trim($title);
        $content = trim($content);

        if ($title === '') {
            $errors['title'] = 'Title is required.';
        } elseif (mb_strlen($title) > 255) {
            $errors['title'] = 'Title must be 255 characters or fewer.';
        }

        if ($content === '') {
            $errors['content'] = 'Content is required.';
        }

        return $errors;
    }
}
