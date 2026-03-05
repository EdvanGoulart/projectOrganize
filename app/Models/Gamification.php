<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database;

class Gamification
{
    private const XP_TASK_CREATED = 15;
    private const XP_TASK_COMPLETED = 50;
    private const XP_REVIEW_COMPLETED = 40;
    private const XP_LOGIN_STREAK = 10;

    public static function onLogin(int $userId): void
    {
        self::ensureStatsRow($userId);

        $db = new Database(config('database'));
        $stats = self::getStats($userId);

        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        if ($stats['last_login_date'] === $today) {
            return;
        }

        $newStreak = 1;

        if ($stats['last_login_date'] === $yesterday) {
            $newStreak = ((int) $stats['current_login_streak']) + 1;
        }

        $longestStreak = max($newStreak, (int) $stats['longest_login_streak']);

        $db->query(
            query: 'UPDATE user_gamification_stats
                    SET current_login_streak = :current_login_streak,
                        longest_login_streak = :longest_login_streak,
                        last_login_date = :last_login_date,
                        updated_at = NOW()
                    WHERE user_id = :user_id',
            params: [
                'user_id' => $userId,
                'current_login_streak' => $newStreak,
                'longest_login_streak' => $longestStreak,
                'last_login_date' => $today,
            ]
        );

        self::addXp($userId, self::XP_LOGIN_STREAK, 'login_streak', 'Login diário consecutivo', null);
    }

    public static function onTaskCreated(int $userId, int $taskId): void
    {
        self::addXp($userId, self::XP_TASK_CREATED, 'task_created', 'Tarefa criada', $taskId);
    }

    public static function onTaskCompleted(int $userId, int $taskId): void
    {
        self::addXp($userId, self::XP_TASK_COMPLETED, 'task_completed', 'Tarefa concluída', $taskId);
    }

    public static function onDeckReviewCompleted(int $userId, int $deckId): void
    {
        self::addXp($userId, self::XP_REVIEW_COMPLETED, 'deck_review_completed', 'Revisão concluída', $deckId);
    }

    public static function getDashboardData(int $userId): array
    {
        self::ensureStatsRow($userId);

        $db = new Database(config('database'));
        $stats = self::getStats($userId);

        $xp = (int) ($stats['total_xp'] ?? 0);
        $levelData = self::calculateLevel($xp);

        $tasksCompleted = (int) $db->query(
            query: 'SELECT COUNT(*) AS total FROM task WHERE idUser = :idUser AND status = :status',
            params: ['idUser' => $userId, 'status' => 'completed']
        )->fetch()['total'];

        $deckReviews = (int) $db->query(
            query: 'SELECT COUNT(*) AS total FROM deck_revisao WHERE id_user = :id_user',
            params: ['id_user' => $userId]
        )->fetch()['total'];

        $cardsAnswered = (int) $db->query(
            query: 'SELECT COUNT(*) AS total FROM deck_revisao_card WHERE id_user = :id_user',
            params: ['id_user' => $userId]
        )->fetch()['total'];

        $recentAchievements = $db->query(
            query: 'SELECT description, created_at
                    FROM user_xp_events
                    WHERE user_id = :user_id
                    ORDER BY id DESC
                    LIMIT 5',
            params: ['user_id' => $userId]
        )->fetchAll();

        $xpHistoryRows = $db->query(
            query: 'SELECT DATE(created_at) AS day, SUM(xp_amount) AS xp
                    FROM user_xp_events
                    WHERE user_id = :user_id
                      AND created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                    GROUP BY DATE(created_at)
                    ORDER BY day ASC',
            params: ['user_id' => $userId]
        )->fetchAll();

        $xpHistoryMap = [];
        foreach ($xpHistoryRows as $row) {
            $xpHistoryMap[$row['day']] = (int) $row['xp'];
        }

        $labels = [];
        $values = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} day"));
            $labels[] = date('d/m', strtotime($date));
            $values[] = $xpHistoryMap[$date] ?? 0;
        }

        return [
            'xp' => $xp,
            'level' => $levelData['level'],
            'xpCurrentLevel' => $levelData['xpCurrentLevel'],
            'xpNextLevel' => $levelData['xpNextLevel'],
            'tasksCompleted' => $tasksCompleted,
            'deckReviews' => $deckReviews,
            'cardsAnswered' => $cardsAnswered,
            'currentLoginStreak' => (int) ($stats['current_login_streak'] ?? 0),
            'longestLoginStreak' => (int) ($stats['longest_login_streak'] ?? 0),
            'recentAchievements' => $recentAchievements,
            'xpHistoryLabels' => $labels,
            'xpHistoryValues' => $values,
        ];
    }

    private static function addXp(int $userId, int $xpAmount, string $eventType, string $description, ?int $referenceId): void
    {
        self::ensureStatsRow($userId);

        $db = new Database(config('database'));

        $db->query(
            query: 'INSERT INTO user_xp_events (user_id, event_type, xp_amount, description, reference_id, created_at)
                    VALUES (:user_id, :event_type, :xp_amount, :description, :reference_id, NOW())',
            params: [
                'user_id' => $userId,
                'event_type' => $eventType,
                'xp_amount' => $xpAmount,
                'description' => $description,
                'reference_id' => $referenceId,
            ]
        );

        $db->query(
            query: 'UPDATE user_gamification_stats
                    SET total_xp = total_xp + :xp_amount,
                        updated_at = NOW()
                    WHERE user_id = :user_id',
            params: [
                'user_id' => $userId,
                'xp_amount' => $xpAmount,
            ]
        );
    }

    private static function ensureStatsRow(int $userId): void
    {
        $db = new Database(config('database'));

        $db->query(
            query: 'INSERT INTO user_gamification_stats (user_id, total_xp, current_login_streak, longest_login_streak, last_login_date, created_at, updated_at)
                    SELECT :user_id, 0, 0, 0, NULL, NOW(), NOW()
                    WHERE NOT EXISTS (
                        SELECT 1
                        FROM user_gamification_stats
                        WHERE user_id = :user_id_check
                    )',
            params: [
                'user_id' => $userId,
                'user_id_check' => $userId,
            ]
        );
    }

    private static function getStats(int $userId): array
    {
        $db = new Database(config('database'));

        $stats = $db->query(
            query: 'SELECT * FROM user_gamification_stats WHERE user_id = :user_id',
            params: ['user_id' => $userId]
        )->fetch();

        return $stats ?: [
            'total_xp' => 0,
            'current_login_streak' => 0,
            'longest_login_streak' => 0,
            'last_login_date' => null,
        ];
    }

    private static function calculateLevel(int $xp): array
    {
        $xpPerLevel = 100;
        $level = intdiv($xp, $xpPerLevel) + 1;
        $xpCurrentLevel = $xp % $xpPerLevel;

        return [
            'level' => $level,
            'xpCurrentLevel' => $xpCurrentLevel,
            'xpNextLevel' => $xpPerLevel,
        ];
    }
}
