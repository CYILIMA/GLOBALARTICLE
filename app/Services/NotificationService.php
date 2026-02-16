<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    /**
     * Create a notification for a user
     */
    public static function create($userId, $type, $title, $message, $data = [])
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Notify about article publication
     */
    public static function articlePublished($userId, $article)
    {
        return self::create(
            $userId,
            'article_published',
            'Article Published',
            "Your article \"{$article->title}\" has been published successfully.",
            [
                'article_id' => $article->id,
                'link' => route('news.show', $article)
            ]
        );
    }

    /**
     * Notify Admin about new article pending review
     */
    public static function articlePendingReview($userId, $article)
    {
        return self::create(
            $userId,
            'article_pending',
            'Article Pending Review',
            "New article \"{$article->title}\" requires your approval.",
            [
                'article_id' => $article->id,
                'link' => route('news.edit', $article)
            ]
        );
    }

    /**
     * Notify about new comment
     */
    public static function newComment($userId, $comment, $article)
    {
        return self::create(
            $userId,
            'comment',
            'New Comment',
            "New comment on \"{$article->title}\" from {$comment->author_name}",
            [
                'comment_id' => $comment->id,
                'article_id' => $article->id,
                'link' => route('news.show', $article->slug) . '#comment-' . $comment->id
            ]
        );
    }

    /**
     * Notify about system warning
     */
    public static function systemWarning($userId, $title, $message, $data = [])
    {
        return self::create(
            $userId,
            'system_warning',
            $title,
            $message,
            $data
        );
    }

    /**
     * Notify about failed email
     */
    public static function emailFailed($userId, $recipient, $subject)
    {
        return self::create(
            $userId,
            'email_failed',
            'Email Delivery Failed',
            "Failed to send email to {$recipient}: {$subject}",
            ['recipient' => $recipient, 'subject' => $subject]
        );
    }
}
