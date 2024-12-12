<?php

class UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Регистрация нового пользователя.
     */
    public function registerUser($nickname, $email, $password, $avatarUrl = null) {
        $sql = "SELECT 1 FROM users WHERE nickname = :nickname";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['nickname' => $nickname]);
        if ($stmt->fetch()) {
            return -1; // Никнейм уже существует
        }

        $sql = "SELECT 1 FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            return -2; // Email уже существует
        }

        // Добавляем нового пользователя
        $sql = "INSERT INTO users (nickname, email, password, avatar_url) VALUES (:nickname, :email, :password, :avatar_url)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'nickname' => $nickname,
            'email' => $email,
            'password' => $password,
            'avatar_url' => $avatarUrl
        ]);

        return $this->pdo->lastInsertId(); // Возвращаем ID нового пользователя
    }
    /**
     * Авторизация пользователя.
     */
    public function loginUser(string $email, string $password): int
    {
        $sql = "CALL login_user(:email, :password, @user_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();

        $result = $this->pdo->query("SELECT @user_id AS user_id")->fetch(PDO::FETCH_ASSOC);
        return (int)$result['user_id'];
    }
}
?>
