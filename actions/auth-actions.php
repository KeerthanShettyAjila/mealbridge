<?php
require '../includes/functions.php';check_csrf();$action=$_POST['action']??'';
try {
 if($action==='register'){
 $name=field('name',100);$email=field('email',190);$phone=field('phone',20);$city=field('city',100);$address=field('address');$password=field('password',200);$role=$_POST['role']??'';
 if(!filter_var($email,FILTER_VALIDATE_EMAIL)||!preg_match('/^[0-9+ ()-]{7,20}$/',$phone))throw new Exception('Enter a valid email and phone number.');
 if(!in_array($role,['donor','orphanage']))throw new Exception('Choose a valid account type.');
 if(strlen($password)<8||$password!==($_POST['confirm_password']??''))throw new Exception('Passwords must match and contain at least 8 characters.');
 if(query('SELECT id FROM users WHERE email=?',[$email])->fetch())throw new Exception('This email is already registered.');
 query('INSERT INTO users(name,email,password,role,phone,city,address) VALUES(?,?,?,?,?,?,?)',[$name,$email,password_hash($password,PASSWORD_DEFAULT),$role,$phone,$city,$address]);flash('Account created! Please wait for admin approval, then log in.');go('login.php');
 }
 if($action==='login'){
 $u=query('SELECT * FROM users WHERE email=?',[field('email',190)])->fetch();
 if(!$u||!password_verify($_POST['password']??'',$u['password']))throw new Exception('Email or password is incorrect.');
 if($u['status']!=='approved')throw new Exception($u['status']==='pending'?'Your account is awaiting admin approval.':'Your registration was rejected. Please contact the admin.');
 session_regenerate_id(true);$_SESSION['uid']=$u['id'];unset($_SESSION['csrf']);go($u['role'].'/dashboard.php');
 }
 throw new Exception('Unknown action.');
}catch(PDOException $e){flash('Unable to save. The email may already be registered.','error');}catch(Exception $e){flash($e->getMessage(),'error');}
go($action==='register'?'register.php':'login.php');
