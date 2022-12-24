<?php

// User.php

class User
{
    private $username;
    private $email;

    public function __construct(string $username, string $email)
    {
        $this->username = $username;
        $this->email = $email;
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

// UserService.php

class UserService
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function register(string $username, string $email): void
    {
        $user = new User($username, $email);
        $this->repository->save($user);
    }
}
