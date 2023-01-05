<?php

declare(strict_types=1);

namespace DSKZPT\YouTube2News\Domain\Model\Dto;

class VideoDTO
{
    private string $videoId;

    private string $channelId = '';

    private string $title = '';

    private string $description = '';

    /** @var mixed[] */
    private array $thumbnails = [];

    private string $channelTitle = '';

    private \DateTimeImmutable $publishedAt;

    private ?\DateTimeImmutable $publishTime = null;

    private string $liveBroadcastContent = '';

    public function __construct(string $videoId, \DateTimeImmutable $publishedAt)
    {
        $this->videoId = $videoId;
        $this->publishedAt = $publishedAt;
    }

    public function getVideoId(): string
    {
        return $this->videoId;
    }

    public function getChannelId(): string
    {
        return $this->channelId;
    }

    public function setChannelId(string $channelId): self
    {
        $this->channelId = $channelId;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed[]
     */
    public function getThumbnails(): array
    {
        return $this->thumbnails;
    }

    /**
     * @param mixed[] $thumbnails
     */
    public function setThumbnails(array $thumbnails): self
    {
        $this->thumbnails = $thumbnails;

        return $this;
    }

    public function getChannelTitle(): string
    {
        return $this->channelTitle;
    }

    public function setChannelTitle(string $channelTitle): self
    {
        $this->channelTitle = $channelTitle;

        return $this;
    }

    public function getPublishedAt(): \DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getPublishTime(): ?\DateTimeImmutable
    {
        return $this->publishTime;
    }

    public function setPublishTime(\DateTimeImmutable $publishTime): self
    {
        $this->publishTime = $publishTime;

        return $this;
    }

    public function getLiveBroadcastContent(): string
    {
        return $this->liveBroadcastContent;
    }

    public function setLiveBroadcastContent(string $liveBroadcastContent): self
    {
        $this->liveBroadcastContent = $liveBroadcastContent;

        return $this;
    }
}
