<?php
declare(strict_types=1);
class Auth {
    public static function register(string $name,string $email,string $password,string $confirm): ?string {
        $name=trim($name);$email=trim($email);
        if (mb_strlen($name)<2 || mb_strlen($name)>70) return 'Kasutajanimi peab olema 2–70 tähemärki.';
        if (!filter_var($email,FILTER_VALIDATE_EMAIL) || mb_strlen($email)>190) return 'Sisesta korrektne e-posti aadress.';
        if (strlen($password)<8) return 'Parool peab olema vähemalt 8 tähemärki.';
        if ($password!==$confirm) return 'Paroolid ei ühti.';
        try {
            $q=db()->prepare("INSERT INTO users (username,email,password_hash,role) VALUES (?,?,?,'user')");
            $q->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT)]);
        } catch (PDOException $e) {
            if ($e->getCode()==='23000') return 'Selle e-posti aadressiga on konto juba olemas.';
            throw $e;
        }
        return null;
    }
    public static function login(string $email,string $password): bool {
        $q=db()->prepare('SELECT id,username,password_hash,role FROM users WHERE email=? LIMIT 1');$q->execute([trim($email)]);$user=$q->fetch();
        if (!$user || !password_verify($password,$user['password_hash'])) return false;
        session_regenerate_id(true);
        $_SESSION['user_id']=(int)$user['id'];$_SESSION['username']=$user['username'];$_SESSION['role']=$user['role'];
        return true;
    }
    public static function logout(): void { $_SESSION=[];session_regenerate_id(true); }
}
