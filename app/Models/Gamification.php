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

    private const ACHIEVEMENTS = [
        [
            'code' => 'login_streak_3',
            'title' => 'Início da Ofensiva',
            'description' => 'Faça login por 3 dias consecutivos.',
            'icon' => '🔥',
            'metric' => 'login_streak',
            'target' => 3,
        ],
        [
            'code' => 'login_streak_7',
            'title' => 'Semana Imparável',
            'description' => 'Faça login por 7 dias consecutivos.',
            'icon' => '⚡',
            'metric' => 'login_streak',
            'target' => 7,
        ],
        [
            'code' => 'tasks_created_50',
            'title' => 'Mestre do Planejamento',
            'description' => 'Crie 50 tarefas.',
            'icon' => '📝',
            'metric' => 'tasks_created',
            'target' => 50,
        ],
        [
            'code' => 'tasks_completed_25',
            'title' => 'Executor Focado',
            'description' => 'Conclua 25 tarefas.',
            'icon' => '✅',
            'metric' => 'tasks_completed',
            'target' => 25,
        ],
        [
            'code' => 'cards_answered_250',
            'title' => 'Memória de Aço',
            'description' => 'Responda 250 cards.',
            'icon' => '🧠',
            'metric' => 'cards_answered',
            'target' => 250,
        ],
        [
            'code' => 'deck_reviews_30',
            'title' => 'Maratonista de Revisões',
            'description' => 'Finalize 30 revisões de deck.',
            'icon' => '🏁',
            'metric' => 'deck_reviews',
            'target' => 30,
        ],

        [
            'code' => 'login_streak_14',
            'title' => 'Duas Semanas de Foco',
            'description' => 'Faça login por 14 dias consecutivos.',
            'icon' => '📆',
            'metric' => 'login_streak',
            'target' => 14,
        ],
        [
            'code' => 'tasks_created_100',
            'title' => 'Arquiteto do Fluxo',
            'description' => 'Crie 100 tarefas.',
            'icon' => '🏗️',
            'metric' => 'tasks_created',
            'target' => 100,
        ],
        [
            'code' => 'tasks_created_250',
            'title' => 'Fábrica de Metas',
            'description' => 'Crie 250 tarefas.',
            'icon' => '🏭',
            'metric' => 'tasks_created',
            'target' => 250,
        ],
        [
            'code' => 'tasks_completed_50',
            'title' => 'Produtividade em Alta',
            'description' => 'Conclua 50 tarefas.',
            'icon' => '🚀',
            'metric' => 'tasks_completed',
            'target' => 50,
        ],
        [
            'code' => 'tasks_completed_100',
            'title' => 'O Entregador',
            'description' => 'Conclua 100 tarefas.',
            'icon' => '🏆',
            'metric' => 'tasks_completed',
            'target' => 100,
        ],
        [
            'code' => 'cards_answered_500',
            'title' => 'Mente Treinada',
            'description' => 'Responda 500 cards.',
            'icon' => '🎯',
            'metric' => 'cards_answered',
            'target' => 500,
        ],
        [
            'code' => 'cards_answered_1000',
            'title' => 'Lenda dos Flashcards',
            'description' => 'Responda 1000 cards.',
            'icon' => '👑',
            'metric' => 'cards_answered',
            'target' => 1000,
        ],
        [
            'code' => 'deck_reviews_60',
            'title' => 'Ritmo Constante',
            'description' => 'Finalize 60 revisões de deck.',
            'icon' => '🔁',
            'metric' => 'deck_reviews',
            'target' => 60,
        ],
        [
            'code' => 'deck_reviews_120',
            'title' => 'Especialista em Revisão',
            'description' => 'Finalize 120 revisões de deck.',
            'icon' => '🎓',
            'metric' => 'deck_reviews',
            'target' => 120,
        ],
        [
            'code' => 'login_streak_30',
            'title' => 'Mês de Disciplina',
            'description' => 'Faça login por 30 dias consecutivos.',
            'icon' => '💎',
            'metric' => 'login_streak',
            'target' => 30,
        ],
    ];


    public static function onLogin(int $userId): void
    {
        self::ensureStatsRow($userId);

        $db = new Database(config('database'));
        $stats = self::getStats($userId);

        $timezone = self::resolveTimezone();
        $timezoneObject = new \DateTimeZone($timezone);
        $todayDate = new \DateTimeImmutable('now', new \DateTimeZone($timezone));
        $today = $todayDate->format('Y-m-d');
        $yesterday = $todayDate->modify('-1 day')->format('Y-m-d');

        $lastLoginDate = self::normalizeDate($stats['last_login_date'], $timezoneObject);

        if ($lastLoginDate === $today) {
            return;
        }

        if ($lastLoginDate !== null && $lastLoginDate > $today) {
            return;
        }

        $newStreak = 1;

        if ($lastLoginDate !== null) {
            $lastLoginDateObject = new \DateTimeImmutable($lastLoginDate, $timezoneObject);
            $daysDiff = (int) $lastLoginDateObject->diff($todayDate)->format('%a');

            if ($daysDiff === 1) {
                $newStreak = ((int) $stats['current_login_streak']) + 1;
            }
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
        self::evaluateAchievements($userId);
    }

    public static function onTaskCreated(int $userId, int $taskId): void
    {
        self::addXp($userId, self::XP_TASK_CREATED, 'task_created', 'Tarefa criada', $taskId);
        self::evaluateAchievements($userId);
    }

    public static function onTaskCompleted(int $userId, int $taskId): void
    {
        self::addXp($userId, self::XP_TASK_COMPLETED, 'task_completed', 'Tarefa concluída', $taskId);
        self::evaluateAchievements($userId);
    }

    public static function onDeckReviewCompleted(int $userId, int $deckId): void
    {
        self::addXp($userId, self::XP_REVIEW_COMPLETED, 'deck_review_completed', 'Revisão concluída', $deckId);
        self::evaluateAchievements($userId);
    }

    public static function getDashboardData(int $userId): array
    {
        self::ensureStatsRow($userId);
        self::evaluateAchievements($userId);

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

        $achievementsSummary = self::getAchievementsSummary($userId);
        $latestUnlockedAchievements = self::getLatestUnlockedAchievements($userId);

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
            'achievementsSummary' => $achievementsSummary,
            'latestUnlockedAchievements' => $latestUnlockedAchievements,
            'xpHistoryLabels' => $labels,
            'xpHistoryValues' => $values,
        ];
    }

    public static function getAchievementsBoardData(int $userId): array
    {
        self::ensureStatsRow($userId);
        self::evaluateAchievements($userId);

        $db = new Database(config('database'));
        $metrics = self::collectMetrics($userId);

        $unlockedRows = $db->query(
            query: 'SELECT achievement_code, unlocked_at
                    FROM user_achievements
                    WHERE user_id = :user_id',
            params: ['user_id' => $userId]
        )->fetchAll();

        $unlockedByCode = [];
        foreach ($unlockedRows as $row) {
            $unlockedByCode[$row['achievement_code']] = $row['unlocked_at'];
        }

        $achievements = [];
        foreach (self::ACHIEVEMENTS as $achievement) {
            $progressValue = min((int) ($metrics[$achievement['metric']] ?? 0), $achievement['target']);
            $achievements[] = [
                'code' => $achievement['code'],
                'title' => $achievement['title'],
                'description' => $achievement['description'],
                'icon' => $achievement['icon'],
                'target' => $achievement['target'],
                'progress' => $progressValue,
                'metricLabel' => self::metricLabel($achievement['metric']),
                'isUnlocked' => isset($unlockedByCode[$achievement['code']]),
                'unlockedAt' => $unlockedByCode[$achievement['code']] ?? null,
            ];
        }

        return [
            'total' => count($achievements),
            'unlocked' => count($unlockedByCode),
            'achievements' => $achievements,
        ];
    }

    private static function normalizeDate(mixed $date, \DateTimeZone $timezone): ?string
    {
        if (! is_string($date) || $date === '') {
            return null;
        }

        try {
            return (new \DateTimeImmutable($date, $timezone))->format('Y-m-d');
        } catch (\Exception) {
            return null;
        }
    }

    private static function resolveTimezone(): string
    {
        $timezone = config('app.timezone');

        if (! is_string($timezone) || $timezone === '') {
            return 'UTC';
        }

        return $timezone;
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

    private static function collectMetrics(int $userId): array
    {
        $db = new Database(config('database'));
        $stats = self::getStats($userId);

        $tasksCreated = (int) $db->query(
            query: 'SELECT COUNT(*) AS total FROM task WHERE idUser = :idUser',
            params: ['idUser' => $userId]
        )->fetch()['total'];

        $tasksCompleted = (int) $db->query(
            query: 'SELECT COUNT(*) AS total FROM task WHERE idUser = :idUser AND status = :status',
            params: ['idUser' => $userId, 'status' => 'completed']
        )->fetch()['total'];

        $cardsAnswered = (int) $db->query(
            query: 'SELECT COUNT(*) AS total FROM deck_revisao_card WHERE id_user = :id_user',
            params: ['id_user' => $userId]
        )->fetch()['total'];

        $deckReviews = (int) $db->query(
            query: 'SELECT COUNT(*) AS total FROM deck_revisao WHERE id_user = :id_user',
            params: ['id_user' => $userId]
        )->fetch()['total'];

        return [
            'tasks_created' => $tasksCreated,
            'tasks_completed' => $tasksCompleted,
            'cards_answered' => $cardsAnswered,
            'deck_reviews' => $deckReviews,
            'login_streak' => (int) ($stats['longest_login_streak'] ?? 0),
        ];
    }

    private static function evaluateAchievements(int $userId): void
    {
        $metrics = self::collectMetrics($userId);

        foreach (self::ACHIEVEMENTS as $achievement) {
            if ((int) ($metrics[$achievement['metric']] ?? 0) < $achievement['target']) {
                continue;
            }

            self::unlockAchievement(
                $userId,
                $achievement['code'],
                $achievement['title'],
                $achievement['description']
            );
        }
    }

    private static function unlockAchievement(int $userId, string $code, string $title, string $description): void
    {
        $db = new Database(config('database'));

        $exists = $db->query(
            query: 'SELECT id FROM user_achievements WHERE user_id = :user_id AND achievement_code = :achievement_code',
            params: [
                'user_id' => $userId,
                'achievement_code' => $code,
            ]
        )->fetch();

        if ($exists) {
            return;
        }

        $db->query(
            query: 'INSERT INTO user_achievements (user_id, achievement_code, achievement_title, achievement_description, unlocked_at)
                    VALUES (:user_id, :achievement_code, :achievement_title, :achievement_description, NOW())',
            params: [
                'user_id' => $userId,
                'achievement_code' => $code,
                'achievement_title' => $title,
                'achievement_description' => $description,
            ]
        );
    }

    private static function metricLabel(string $metric): string
    {
        return match ($metric) {
            'tasks_created' => 'tarefas criadas',
            'tasks_completed' => 'tarefas concluídas',
            'cards_answered' => 'cards respondidos',
            'deck_reviews' => 'revisões',
            'login_streak' => 'dias consecutivos',
            default => 'progresso',
        };
    }

    private static function getAchievementsSummary(int $userId): array
    {
        $db = new Database(config('database'));

        $unlocked = (int) $db->query(
            query: 'SELECT COUNT(*) AS total FROM user_achievements WHERE user_id = :user_id',
            params: ['user_id' => $userId]
        )->fetch()['total'];

        $total = count(self::ACHIEVEMENTS);
        $percentage = $total > 0 ? (int) round(($unlocked / $total) * 100) : 0;

        return [
            'unlocked' => $unlocked,
            'total' => $total,
            'percentage' => $percentage,
        ];
    }

    private static function getLatestUnlockedAchievements(int $userId, int $limit = 4): array
    {
        $db = new Database(config('database'));
        $safeLimit = max(1, $limit);

        $rows = $db->query(
            query: 'SELECT achievement_code, achievement_title, unlocked_at
                    FROM user_achievements
                    WHERE user_id = :user_id
                    ORDER BY unlocked_at DESC
                    LIMIT ' . $safeLimit,
            params: [
                'user_id' => $userId,
            ]
        )->fetchAll();

        $achievementsByCode = self::achievementsByCode();

        return array_map(static function (array $row) use ($achievementsByCode): array {
            $code = (string) $row['achievement_code'];
            return [
                'code' => $code,
                'title' => (string) $row['achievement_title'],
                'icon' => $achievementsByCode[$code]['icon'] ?? '🏅',
                'unlockedAt' => $row['unlocked_at'],
            ];
        }, $rows);
    }

    private static function achievementsByCode(): array
    {
        $indexed = [];
        foreach (self::ACHIEVEMENTS as $achievement) {
            $indexed[$achievement['code']] = $achievement;
        }

        return $indexed;
    }
}
