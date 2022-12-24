<?php

// User.php

class User
{
    private $id;
    private $username;
    private $email;

    public function __construct(int $id, string $username, string $email)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}

// UserRepository.php

class UserRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getById(int $id): User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return new User($row['id'], $row['username'], $row['email']);
    }

    public function save(User $user): void
    {
        if ($user->getId() === null) {
            $this->insert($user);
        } else {
            $this->update($user);
        }
    }

    private function insert(User $user): void
    {
        $stmt = $this->db->prepare('INSERT INTO users (username, email) VALUES (:username, :email)');
        $stmt->execute([
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
        ]);
        $user->setId($this->db->lastInsertId());
    }

    private function update(User $user): void
    {
        $stmt = $this->db->prepare('UPDATE users SET username = :username, email = :email WHERE id = :id');
        $stmt->execute([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
        ]);
    }
}

// Example usage

$db = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');
$repository = new UserRepository($db);

$user = new User(null, 'john.doe', 'john.doe@example.com');
$repository->save($user); // Inserts a new user

$user->setUsername('jane.doe');
$repository->save($user); // Updates the existing user
